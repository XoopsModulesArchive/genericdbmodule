<?php

$dirname = basename(dirname(__DIR__));

eval(
    'function xoops_module_install_' . $dirname . '($module){
    return xgdb_oninstall($module, "' . $dirname . '");
}'
);

if (!function_exists('xgdb_oninstall')) {
    /**
     * @param $module
     * @param $dirname
     * @return bool
     */
    function xgdb_oninstall($module, $dirname)
    {
        global $ret, $xoopsConfig, $xoopsUser;

        $ret           = [];
        $myts          = MyTextSanitizer::getInstance();
        $xoopsDB       = XoopsDatabaseFactory::getDatabaseConnection();
        $tplfile_tbl   = $xoopsDB->prefix('tplfile');
        $tplsource_tbl = $xoopsDB->prefix('tplsource');
        $newblocks_tbl = $xoopsDB->prefix('newblocks');
        $mid           = $module->getVar('mid');

        if (!mkdir($concurrentDirectory = XOOPS_UPLOAD_PATH . '/' . $dirname, 0777) && !is_dir($concurrentDirectory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }
        $file = fopen(XOOPS_UPLOAD_PATH . '/' . $dirname . '/.htaccess', 'wb');
        flock($file, LOCK_EX);
        //        fputs($file, 'SetEnvIf Referer "^' . XOOPS_URL . "/modules/" . $dirname . '/(.+\.php)?" ref_ok' . "\n");
        fwrite($file, "order deny,allow\n");
        fwrite($file, "deny from all\n");
        //        fputs($file, "allow from env=ref_ok\n");
        flock($file, LOCK_UN);
        fclose($file);

        $sql_file_path = XOOPS_ROOT_PATH . '/modules/' . $dirname . '/sql/mysql.sql';
        $prefix        = $xoopsDB->prefix() . '_' . $dirname;
        if (is_file($sql_file_path)) {
            $ret[] = 'SQL file found at <b>' . htmlspecialchars($sql_file_path, ENT_QUOTES | ENT_HTML5) . '</b>.<br> Creating tables...<br>';
            require_once XOOPS_ROOT_PATH . '/class/database/sqlutility.php';
            $sql_query = fread(fopen($sql_file_path, 'rb'), filesize($sql_file_path));
            $sql_query = trim($sql_query);
            SqlUtility::splitMySqlFile($pieces, $sql_query);
            $created_tables = [];
            $error          = false;
            foreach ($pieces as $piece) {
                $prefixed_query = SqlUtility::prefixQuery($piece, $prefix);
                if (!$prefixed_query) {
                    $ret[] = '<b>' . htmlspecialchars($piece, ENT_QUOTES | ENT_HTML5) . '</b> is not a valid SQL!<br>';
                    $error = true;
                    break;
                }
                if (!$xoopsDB->query($prefixed_query[0])) {
                    $ret[] = htmlspecialchars($xoopsDB->error(), ENT_QUOTES | ENT_HTML5);
                    $error = true;
                    break;
                }
                if (in_array($prefixed_query[4], $created_tables, true)) {
                    $ret[] = '&nbsp;&nbsp;Data inserted to table <b>' . htmlspecialchars($prefix . '_' . $prefixed_query[4], ENT_QUOTES | ENT_HTML5) . '</b>.<br>';
                } else {
                    $ret[]            = '&nbsp;&nbsp;Table <b>' . htmlspecialchars($prefix . '_' . $prefixed_query[4], ENT_QUOTES | ENT_HTML5) . '</b> created.<br>';
                    $created_tables[] = $prefixed_query[4];
                }
            }
        }

        $res       = $xoopsDB->query('SELECT groupid FROM ' . $xoopsDB->prefix('groups') . ' ORDER BY groupid ASC');
        $gidstring = '|';
        while ([$groupid] = $xoopsDB->fetchRow($res)) {
            $gidstring .= $groupid . '|';
        }
        $xoopsDB->query("UPDATE `$prefix" . "_xgdb_item` SET show_gids = '$gidstring'");

        $tplfileHandler = xoops_getHandler('tplfile');
        $template_dir   = XOOPS_ROOT_PATH . '/modules/' . $dirname . '/templates';
        if ($dirHandler = @opendir($template_dir . '/')) {
            $ret[] = 'Adding templates...<br>';
            while (false !== ($template_file = readdir($dirHandler))) {
                if (0 === mb_strpos($template_file, '.')) {
                    continue;
                }

                if ('index.html' === $template_file) {
                    continue;
                }

                $xgdb_template_file = $dirname . '_' . $template_file;
                $template_file_path = $template_dir . '/' . $template_file;
                if (is_file($template_file_path)) {
                    $mtime    = (int)@filemtime($template_file_path);
                    $template = $tplfileHandler->create();
                    $template->setVar('tpl_source', file_get_contents($template_file_path), true);
                    $template->setVar('tpl_refid', $mid);
                    $template->setVar('tpl_tplset', 'default');
                    $template->setVar('tpl_file', $xgdb_template_file);
                    $template->setVar('tpl_desc', '', true);
                    $template->setVar('tpl_module', $dirname);
                    $template->setVar('tpl_lastmodified', $mtime);
                    $template->setVar('tpl_lastimported', 0);
                    $template->setVar('tpl_type', 'module');

                    if ($tplfileHandler->insert($template)) {
                        $template_id            = $template->getVar('tpl_id');
                        $ret[]                  = '&nbsp;&nbsp;Template <b>' . htmlspecialchars($xgdb_template_file, ENT_QUOTES | ENT_HTML5) . '</b> added to the database. (ID: <b>' . $template_id . '</b>)<br>';
                        $error_reporting        = error_reporting(0);
                        $error_reporting_result = xoops_template_touch($template_id);
                        error_reporting($error_reporting);
                        if ($error_reporting_result) {
                            $ret[] = '&nbsp;&nbsp;Template <b>' . htmlspecialchars($xgdb_template_file, ENT_QUOTES | ENT_HTML5) . '</b> compiled.</span><br>';
                        } else {
                            $ret[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Failed compiling template <b>' . htmlspecialchars($xgdb_template_file, ENT_QUOTES | ENT_HTML5) . '</b>.</span><br>';
                        }
                    } else {
                        $ret[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not insert template <b>' . htmlspecialchars($xgdb_template_file, ENT_QUOTES | ENT_HTML5) . '</b> to the database.</span><br>';
                    }
                }
            }
            closedir($dirHandler);
        }

        $blocks = $module->getInfo('blocks');
        foreach ($blocks as $func_num => $block) {
            $template_dir       .= '/blocks';
            $xgdb_template_file = $block['template'];
            $template_file      = mb_substr($xgdb_template_file, mb_strlen($dirname) + 1, mb_strlen($xgdb_template_file));
            $template_file_path = "$template_dir/$template_file";
            if (is_file($template_file_path)) {
                $sql = "SELECT bid FROM $newblocks_tbl WHERE mid = $mid AND func_num = $func_num AND show_func = '" . addslashes($block['show_func']) . "' AND func_file = '" . addslashes($block['file']) . "'";
                $res = $xoopsDB->query($sql);
                [$bid] = $xoopsDB->fetchRow($res);
                $xoopsDB->query("UPDATE $newblocks_tbl SET template = '" . addslashes($xgdb_template_file) . "' WHERE bid = $bid");

                $sql = "SELECT tpl_id FROM $tplfile_tbl WHERE tpl_tplset = 'default' AND tpl_file = '" . addslashes($xgdb_template_file) . "' AND tpl_module = '" . addslashes($dirname) . "' AND tpl_type = 'block'";
                $res = $xoopsDB->query($sql);
                [$tpl_id] = $xoopsDB->fetchRow($res);
                $xoopsDB->query("DELETE FROM $tplfile_tbl WHERE tpl_id = $tpl_id");
                $xoopsDB->query("DELETE FROM $tplsource_tbl WHERE tpl_id = $tpl_id");

                $mtime    = (int)@filemtime($template_file_path);
                $template = $tplfileHandler->create();
                $template->setVar('tpl_source', file_get_contents($template_file_path), true);
                $template->setVar('tpl_refid', $bid);
                $template->setVar('tpl_tplset', 'default');
                $template->setVar('tpl_file', $xgdb_template_file);
                $template->setVar('tpl_desc', '', true);
                $template->setVar('tpl_module', $dirname);
                $template->setVar('tpl_lastmodified', $mtime);
                $template->setVar('tpl_lastimported', 0);
                $template->setVar('tpl_type', 'block');

                if ($tplfileHandler->insert($template)) {
                    $template_id            = $template->getVar('tpl_id');
                    $ret[]                  = '&nbsp;&nbsp;Template <b>' . htmlspecialchars($xgdb_template_file, ENT_QUOTES | ENT_HTML5) . '</b> added to the database. (ID: <b>' . $template_id . '</b>)<br>';
                    $error_reporting        = error_reporting(0);
                    $error_reporting_result = xoops_template_touch($template_id);
                    error_reporting($error_reporting);
                    if ($error_reporting_result) {
                        $ret[] = '&nbsp;&nbsp;Template <b>' . htmlspecialchars($xgdb_template_file, ENT_QUOTES | ENT_HTML5) . '</b> compiled.</span><br>';
                    } else {
                        $ret[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Failed compiling template <b>' . htmlspecialchars($xgdb_template_file, ENT_QUOTES | ENT_HTML5) . '</b>.</span><br>';
                    }
                } else {
                    $ret[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not insert template <b>' . htmlspecialchars($xgdb_template_file, ENT_QUOTES | ENT_HTML5) . '</b> to the database.</span><br>';
                }
            }
        }

        $affix = mb_strtoupper(3 <= mb_strlen($dirname) ? mb_substr($dirname, 0, 3) : $dirname);
        if (!extension_loaded('mbstring')) {
            $ret[] = '<span style="color: red; ">' . constant('_MI_' . $affix . '_MBSTRING_DISABLE_ERR') . '</span>';
        }
        if (extension_loaded('gd')) {
            $gd_infos = gd_info();
            if (!checkGDSupport()) {
                $ret[] = '<span style="color: red; ">' . constant('_MI_' . $affix . '_GD_NOT_SUPPORTED_ERR') . '</span>';
            }
        } else {
            $ret[] = '<span style="color: red; ">' . constant('_MI_' . $affix . '_GD_DISABLE_ERR') . '</span>';
        }

        return true;
    }

    /**
     * Returns whether GD (gif, jpeg, png) is supported.
     *
     * @return Bool True if Boolean GD (gif, jpeg, png) is supported, otherwise false
     */
    function checkGDSupport()
    {
        $gd_infos = gd_info();
        if (!$gd_infos['GIF Read Support'] || !$gd_infos['GIF Create Support']) {
            return false;
        }

        if (isset($gd_infos['JPG Support']) && !$gd_infos['JPG Support']) {
            return false;
        }

        if (isset($gd_infos['JPEG Support']) && !$gd_infos['JPEG Support']) {
            return false;
        }

        if (!$gd_infos['PNG Support']) {
            return false;
        }

        return true;
    }
}

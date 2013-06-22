<?php

$dirname = basename(dirname(dirname(__FILE__)));

eval('function xoops_module_install_' . $dirname . '($module){
    return xgdb_oninstall($module, "' . $dirname . '");
}');

if (!function_exists('xgdb_oninstall')) {
    function xgdb_oninstall($module, $dirname) {
        global $ret, $xoopsConfig, $xoopsUser;
        $myts = &MyTextSanitizer::getInstance();
        $xoopsDB = &Database::getInstance();
        $tplfile_tbl = $xoopsDB->prefix("tplfile");
        $tplsource_tbl = $xoopsDB->prefix("tplsource");
        $newblocks_tbl = $xoopsDB->prefix("newblocks");
        $mid = $module->getVar('mid');

        @mkdir(XOOPS_UPLOAD_PATH . "/" . $dirname, 0777);
        $file = fopen(XOOPS_UPLOAD_PATH . "/" . $dirname . "/.htaccess", "w");
        flock($file, LOCK_EX);
        //        fputs($file, 'SetEnvIf Referer "^' . XOOPS_URL . "/modules/" . $dirname . '/(.+\.php)?" ref_ok' . "\n");
        fputs($file, "order deny,allow\n");
        fputs($file, "deny from all\n");
        //        fputs($file, "allow from env=ref_ok\n");
        flock($file, LOCK_UN);
        fclose($file);

        $sql_file_path = XOOPS_ROOT_PATH . '/modules/' . $dirname . '/sql/mysql.sql';
        $prefix = $xoopsDB->prefix() . '_' . $dirname;
        if (file_exists($sql_file_path)) {
            $ret[] = "SQL file found at <b>" . $myts->htmlSpecialChars($sql_file_path) . "</b>.<br /> Creating tables...<br />";
            include_once XOOPS_ROOT_PATH . '/class/database/sqlutility.php';
            $sql_query = fread(fopen($sql_file_path, 'r'), filesize($sql_file_path));
            $sql_query = trim($sql_query);
            SqlUtility::splitMySqlFile($pieces, $sql_query);
            $created_tables = array();
            $error = false;
            foreach ($pieces as $piece) {
                $prefixed_query = SqlUtility::prefixQuery($piece, $prefix);
                if (!$prefixed_query) {
                    $ret[] = "<b>" . $myts->htmlSpecialChars($piece) . "</b> is not a valid SQL!<br />";
                    $error = true;
                    break;
                }
                if (!$xoopsDB->query($prefixed_query[0])) {
                    $ret[] = $myts->htmlSpecialChars($xoopsDB->error());
                    $error = true;
                    break;
                } else {
                    if (!in_array($prefixed_query[4], $created_tables)) {
                        $ret[] = '&nbsp;&nbsp;Table <b>' . $myts->htmlSpecialChars($prefix . '_' . $prefixed_query[4]) . '</b> created.<br />';
                        $created_tables[] = $prefixed_query[4];
                    } else {
                        $ret[] = '&nbsp;&nbsp;Data inserted to table <b>' . $myts->htmlSpecialChars($prefix . '_' . $prefixed_query[4]) . '</b>.<br />';
                    }
                }
            }
        }

        $res = $xoopsDB->query("SELECT groupid FROM " . $xoopsDB->prefix('groups') . " ORDER BY groupid ASC");
        $gidstring = '|';
        while (list($groupid) = $xoopsDB->fetchRow($res)) {
            $gidstring .= $groupid . '|';
        }
        $xoopsDB->query("UPDATE `$prefix" . "_xgdb_item` SET show_gids = '$gidstring'");

        $tplfile_handler = &xoops_gethandler('tplfile');
        $template_dir = XOOPS_ROOT_PATH . '/modules/' . $dirname . '/templates';
        if ($dir_handler = @opendir($template_dir . '/')) {
            $ret[] = 'Adding templates...<br />';
            while (($template_file = readdir($dir_handler)) !== false) {
                if (substr($template_file, 0, 1) == '.') continue;
                elseif ($template_file == 'index.html') continue;

                $xgdb_template_file = $dirname . '_' . $template_file;
                $template_file_path = $template_dir . '/' . $template_file;
                if (is_file($template_file_path)) {
                    $mtime = intval(@filemtime($template_file_path));
                    $template = &$tplfile_handler->create();
                    $template->setVar('tpl_source', file_get_contents($template_file_path), true);
                    $template->setVar('tpl_refid', $mid);
                    $template->setVar('tpl_tplset', 'default');
                    $template->setVar('tpl_file', $xgdb_template_file);
                    $template->setVar('tpl_desc', '', true);
                    $template->setVar('tpl_module', $dirname);
                    $template->setVar('tpl_lastmodified', $mtime);
                    $template->setVar('tpl_lastimported', 0);
                    $template->setVar('tpl_type', 'module');

                    if (!$tplfile_handler->insert($template)) {
                        $ret[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not insert template <b>' . $myts->htmlSpecialChars($xgdb_template_file) . '</b> to the database.</span><br />';
                    } else {
                        $template_id = $template->getVar('tpl_id');
                        $ret[] = '&nbsp;&nbsp;Template <b>' . $myts->htmlSpecialChars($xgdb_template_file) . '</b> added to the database. (ID: <b>' . $template_id . '</b>)<br />';
                        $error_reporting = error_reporting(0);
                        $error_reporting_result = xoops_template_touch($template_id);
                        error_reporting($error_reporting);
                        if (!$error_reporting_result) {
                            $ret[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Failed compiling template <b>' . $myts->htmlSpecialChars($xgdb_template_file) . '</b>.</span><br />';
                        } else {
                            $ret[] = '&nbsp;&nbsp;Template <b>' . $myts->htmlSpecialChars($xgdb_template_file) . '</b> compiled.</span><br />';
                        }
                    }
                }
            }
            closedir($dir_handler);
        }

        $blocks = $module->getInfo('blocks');
        foreach ($blocks as $func_num => $block) {
            $template_dir .= '/blocks';
            $xgdb_template_file = $block['template'];
            $template_file = substr($xgdb_template_file, strlen($dirname) + 1, strlen($xgdb_template_file));
            $template_file_path = "$template_dir/$template_file";
            if (file_exists($template_file_path)) {
                $sql = "SELECT bid FROM $newblocks_tbl WHERE mid = $mid AND func_num = $func_num AND show_func = '" . addslashes($block['show_func']) . "' AND func_file = '" . addslashes($block['file']) . "'";
                $res = $xoopsDB->query($sql);
                list($bid) = $xoopsDB->fetchRow($res);
                $xoopsDB->query("UPDATE $newblocks_tbl SET template = '" . addslashes($xgdb_template_file) . "' WHERE bid = $bid");

                $sql = "SELECT tpl_id FROM $tplfile_tbl WHERE tpl_tplset = 'default' AND tpl_file = '" . addslashes($xgdb_template_file) . "' AND tpl_module = '" . addslashes($dirname) . "' AND tpl_type = 'block'";
                $res = $xoopsDB->query($sql);
                list($tpl_id) = $xoopsDB->fetchRow($res);
                $xoopsDB->query("DELETE FROM $tplfile_tbl WHERE tpl_id = $tpl_id");
                $xoopsDB->query("DELETE FROM $tplsource_tbl WHERE tpl_id = $tpl_id");

                $mtime = intval(@filemtime($template_file_path));
                $template = &$tplfile_handler->create();
                $template->setVar('tpl_source', file_get_contents($template_file_path), true);
                $template->setVar('tpl_refid', $bid);
                $template->setVar('tpl_tplset', 'default');
                $template->setVar('tpl_file', $xgdb_template_file);
                $template->setVar('tpl_desc', '', true);
                $template->setVar('tpl_module', $dirname);
                $template->setVar('tpl_lastmodified', $mtime);
                $template->setVar('tpl_lastimported', 0);
                $template->setVar('tpl_type', 'block');

                if (!$tplfile_handler->insert($template)) {
                    $ret[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not insert template <b>' . $myts->htmlSpecialChars($xgdb_template_file) . '</b> to the database.</span><br />';
                } else {
                    $template_id = $template->getVar('tpl_id');
                    $ret[] = '&nbsp;&nbsp;Template <b>' . $myts->htmlSpecialChars($xgdb_template_file) . '</b> added to the database. (ID: <b>' . $template_id . '</b>)<br />';
                    $error_reporting = error_reporting(0);
                    $error_reporting_result = xoops_template_touch($template_id);
                    error_reporting($error_reporting);
                    if (!$error_reporting_result) {
                        $ret[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Failed compiling template <b>' . $myts->htmlSpecialChars($xgdb_template_file) . '</b>.</span><br />';
                    } else {
                        $ret[] = '&nbsp;&nbsp;Template <b>' . $myts->htmlSpecialChars($xgdb_template_file) . '</b> compiled.</span><br />';
                    }
                }
            }
        }

        $affix = strtoupper(strlen($dirname) >= 3 ? substr($dirname, 0, 3) : $dirname);
        if (!extension_loaded('mbstring')) {
            $ret[] = '<font color="red">' . constant('_MI_' . $affix . '_MBSTRING_DISABLE_ERR') . '</font>';
        }
        if (!extension_loaded('gd')) {
            $ret[] = '<font color="red">' . constant('_MI_' . $affix . '_GD_DISABLE_ERR') . '</font>';
        } else {
            $gd_infos = gd_info();
            if (!checkGDSupport()) {
                $ret[] = '<font color="red">' . constant('_MI_' . $affix . '_GD_NOT_SUPPORTED_ERR') . '</font>';
            }
        }

        return true;
    }

    /**
     * GD(gif、jpeg、png)をサポートしているかどうかを返す.
     *
     * @return Boolean GD(gif、jpeg、png)をサポートしている場合はtrue
     */
    function checkGDSupport() {
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

?>
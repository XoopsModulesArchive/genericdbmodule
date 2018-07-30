<?php

$dirname = basename(dirname(__DIR__));

eval('function xoops_module_update_' . $dirname . '($module, $prev_version){
    return xgdb_onupdate($module, $prev_version, "' . $dirname . '");
}');

if (!function_exists('xgdb_onupdate')) {
    function xgdb_onupdate($module, $prev_version, $dirname)
    {
        global $msgs, $xoopsConfig, $xoopsUser;
        $myts = MyTextSanitizer::getInstance();
        if (!is_array($msgs)) {
            $msgs = [];
        }
        $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();
        $tplfile_tbl = $xoopsDB->prefix('tplfile');
        $tplsource_tbl = $xoopsDB->prefix('tplsource');
        $data_tbl = $xoopsDB->prefix($dirname . '_xgdb_data');
        $his_tbl = $xoopsDB->prefix($dirname . '_xgdb_his');
        $item_tbl = $xoopsDB->prefix($dirname . '_xgdb_item');
        $newblocks_tbl = $xoopsDB->prefix('newblocks');
        $mid = $module->getVar('mid');
        $module_upload_dir = XOOPS_UPLOAD_PATH . '/' . $dirname;

        $tplfile_handler = xoops_gethandler('tplfile');
        $template_dir = XOOPS_ROOT_PATH . '/modules/' . $dirname . '/templates';
        include_once XOOPS_ROOT_PATH . '/class/template.php';
        $msgs[] = 'Updating templates...';
        if ($dir_handler = @opendir($template_dir . '/')) {
            while (false !== ($template_file = readdir($dir_handler))) {
                if ('.' == mb_substr($template_file, 0, 1)) {
                    continue;
                } elseif ('index.html' == $template_file) {
                    continue;
                }

                $xgdb_template_file = $dirname . '_' . $template_file;
                $template_file_path = $template_dir . '/' . $template_file;
                if (is_file($template_file_path)) {
                    $mtime = intval(@filemtime($template_file_path));
                    $template = $tplfile_handler->create();
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
                        $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not insert template <b>' . $myts->htmlSpecialChars($xgdb_template_file) . '</b> to the database.</span>';
                    } else {
                        $template_id = $template->getVar('tpl_id');
                        $msgs[] = '&nbsp;&nbsp;Template <b>' . $myts->htmlSpecialChars($xgdb_template_file) . '</b> inserted to the database.';
                        if ('default' == $xoopsConfig['template_set']) {
                            $error_reporting = error_reporting(0);
                            $error_reporting_result = xoops_template_touch($template_id);
                            error_reporting($error_reporting);
                            if (!$error_reporting_result) {
                                $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not recompile template <b>' . $myts->htmlSpecialChars($xgdb_template_file) . '</b>.</span>';
                            } else {
                                $msgs[] = '&nbsp;&nbsp;Template <b>' . $myts->htmlSpecialChars($xgdb_template_file) . '</b> recompiled.</span>';
                            }
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
            $template_file = mb_substr($xgdb_template_file, mb_strlen($dirname) + 1, mb_strlen($xgdb_template_file));
            $template_file_path = "$template_dir/$template_file";
            if (file_exists($template_file_path)) {
                $sql = "SELECT bid FROM $newblocks_tbl WHERE mid = $mid AND func_num = $func_num AND show_func = '" . addslashes($block['show_func']) . "' AND func_file = '" . addslashes($block['file']) . "'";
                $res = $xoopsDB->query($sql);
                [$bid] = $xoopsDB->fetchRow($res);
                $xoopsDB->query("UPDATE $newblocks_tbl SET template = '" . addslashes($xgdb_template_file) . "' WHERE bid = $bid");

                $sql = "SELECT tpl_id FROM $tplfile_tbl WHERE tpl_tplset = 'default' AND tpl_file = '" . addslashes($xgdb_template_file) . "' AND tpl_module = '" . addslashes($dirname) . "' AND tpl_type = 'block'";
                $res = $xoopsDB->query($sql);
                [$tpl_id] = $xoopsDB->fetchRow($res);
                $xoopsDB->query("DELETE FROM $tplfile_tbl WHERE tpl_id = $tpl_id");
                $xoopsDB->query("DELETE FROM $tplsource_tbl WHERE tpl_id = $tpl_id");

                $mtime = intval(@filemtime($template_file_path));
                $template = $tplfile_handler->create();
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
                    $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not update template <b>' . $myts->htmlSpecialChars($xgdb_template_file) . '</b>.</span>';
                } else {
                    $msgs[] = '&nbsp;&nbsp;Template <b>' . $myts->htmlSpecialChars($xgdb_template_file) . '</b> updated.';
                    if ('default' == $xoopsConfig['template_set']) {
                        $template_id = $template->getVar('tpl_id');
                        $error_reporting = error_reporting(0);
                        $error_reporting_result = xoops_template_touch($template_id);
                        error_reporting($error_reporting);
                        if (!$error_reporting_result) {
                            $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not recompile template <b>' . $myts->htmlSpecialChars($xgdb_template_file) . '</b>.</span>';
                        } else {
                            $msgs[] = '&nbsp;&nbsp;Template <b>' . $myts->htmlSpecialChars($xgdb_template_file) . '</b> recompiled.';
                        }
                    }
                }
            }
        }

        if (30 > $prev_version) {
            // ���åץ?�ɥǥ��쥯�ȥ��ľ��󥯶ػ�������ɲ�
            $file = fopen(XOOPS_UPLOAD_PATH . '/' . $dirname . '/.htaccess', 'w');
            flock($file, LOCK_EX);
            fwrite($file, "order deny,allow\n");
            fwrite($file, "deny from all\n");
            flock($file, LOCK_UN);
            fclose($file);

            // ���åץ?�ɥե�����Υե�����̾��URL���󥳡��ɤ�����Τ��Ѵ�
            $res_item = $xoopsDB->query("SELECT name FROM $item_tbl WHERE type = 'file' OR type = 'image'");
            while ([$col_name] = $xoopsDB->fetchRow($res_item)) {
                $res_data = $xoopsDB->query("SELECT id, $col_name FROM $data_tbl WHERE $col_name IS NOT NULL AND $col_name != ''");
                while ([$id, $file_name] = $xoopsDB->fetchRow($res_data)) {
                    $real_file_name = urlencode("$id-$col_name-$file_name");
                    @copy($module_upload_dir . '/' . $file_name, $module_upload_dir . '/' . $real_file_name);
                }
            }

            // xgdb_item�ơ��֥�Υ����̾ambiguous��search_cond���ѹ�
            $xoopsDB->query("ALTER TABLE `$item_tbl` CHANGE `ambiguous` `search_cond` TINYINT(1) UNSIGNED NULL DEFAULT NULL;");

            // xgdb_item�ơ��֥��disp_cond�������ɲ�
            $xoopsDB->query("ALTER TABLE `$item_tbl` ADD `disp_cond` TINYINT(1) UNSIGNED NULL AFTER `input_desc`;");
        }

        if (40 > $prev_version) {
            // xgdb_item�ơ��֥��show_gids�������ɲ�
            $xoopsDB->query("ALTER TABLE `$item_tbl` ADD `show_gids` VARCHAR(255) AFTER `required`;");

            // xgdb_item�ơ��֥��show_gids�����˽��ǡ���������
            $res = $xoopsDB->query('SELECT groupid FROM ' . $xoopsDB->prefix('groups') . ' ORDER BY groupid ASC');
            $gidstring = '|';
            while ([$groupid] = $xoopsDB->fetchRow($res)) {
                $gidstring .= $groupid . '|';
            }
            $xoopsDB->query("UPDATE `$item_tbl` SET show_gids = '$gidstring'");
        }

        if (50 > $prev_version) {
            // ���ͤ��ĥƥ����ȥܥå�������type��ƥ����ȥܥå���(����)���Ѵ�
            $res_item = $xoopsDB->query("SELECT name FROM $item_tbl WHERE type = 'text' AND (value_type = 'int' OR value_type = 'float')");
            while ([$col_name] = $xoopsDB->fetchRow($res_item)) {
                $xoopsDB->query("UPDATE `$item_tbl` SET type = 'number' WHERE name = '" . addslashes($col_name) . "'");
            }

            // ʸ������ĥƥ����ȥܥå�������type��ƥ����ȥܥå���(ʸ����)���Ѵ�
            $res_item = $xoopsDB->query("SELECT name FROM $item_tbl WHERE type = 'text' AND value_type = 'string'");
            while ([$col_name] = $xoopsDB->fetchRow($res_item)) {
                $xoopsDB->query("UPDATE `$item_tbl` SET value_type = NULL WHERE name = '" . addslashes($col_name) . "'");
            }

            // xgdb_data�ơ��֥�Υ����̾id��did���ѹ�
            $xoopsDB->query("ALTER TABLE `$data_tbl` CHANGE `id` `did` INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT;");

            // xgdb_item�ơ��֥��list_link�����̾����
            $xoopsDB->query("ALTER TABLE `$item_tbl` DROP `list_link`;");

            // xgdb_data�ơ��֥��update_uid�����̾����
            $xoopsDB->query("ALTER TABLE `$data_tbl` DROP `update_uid`;");

            // xgdb_data�ơ��֥��update_date�����̾����
            $xoopsDB->query("ALTER TABLE `$data_tbl` DROP `update_date`;");

            // xgdb_item�ơ��֥��xgdb_name�������ɲ�
            $xoopsDB->query("ALTER TABLE `$item_tbl` ADD `xgdb_name` VARCHAR(255);");

            // ��������ơ��֥�����
            $item_defs = [];
            $res_item = $xoopsDB->query("SELECT name, type, value_type FROM $item_tbl");
            while ($item_def = $xoopsDB->fetchArray($res_item)) {
                $item_defs[$item_def['name']] = $item_def;
            }

            $create_his_sql = "CREATE TABLE $his_tbl (";
            $create_his_sql .= 'hid INT UNSIGNED NOT NULL AUTO_INCREMENT';
            $create_his_sql .= ', did INT UNSIGNED NOT NULL';
            $create_his_sql .= ', operation VARCHAR(255) NOT NULL';
            $create_his_sql .= ', update_uid INT UNSIGNED NOT NULL';
            $create_his_sql .= ', update_date DATETIME NOT NULL';

            foreach ($item_defs as $item_name => $item_def) {
                if ('did' === $item_name || 'add_uid' === $item_name || 'add_date' === $item_name) {
                    continue;
                }

                $create_his_sql .= ', ' . $item_name;

                if ('text' == $item_def['type']) {
                    $create_his_sql .= ' VARCHAR(255)';
                } elseif ('tarea' == $item_def['type'] || 'xtarea' == $item_def['type']) {
                    $create_his_sql .= ' TEXT';
                } elseif ('file' == $item_def['type'] || 'image' == $item_def['type']) {
                    $create_his_sql .= ' VARCHAR(255)';
                } elseif (isset($item_def['value_type']) && 'string' == $item_def['value_type']) {
                    $create_his_sql .= ' VARCHAR(255)';
                } elseif (isset($item_def['value_type']) && 'int' == $item_def['value_type']) {
                    $create_his_sql .= ' INT';
                } elseif (isset($item_def['value_type']) && 'float' == $item_def['value_type']) {
                    $create_his_sql .= ' FLOAT';
                } elseif ('date' == $item_def['type']) {
                    $create_his_sql .= ' DATE';
                }
            }
            $create_his_sql .= ', PRIMARY KEY(hid)';
            $create_his_sql .= ') ENGINE=MyISAM;';
            $xoopsDB->query($create_his_sql);

            // ��������ơ��֥�˽��ǡ�������Ͽ
            $res_data = $xoopsDB->query("SELECT * FROM $data_tbl");
            while ($row_data = $xoopsDB->fetchArray($res_data)) {
                $his_insert_sql = "INSERT INTO $his_tbl (did, operation, update_uid, update_date";

                foreach ($item_defs as $item_name => $item_def) {
                    $his_insert_sql .= ', ' . $item_name;
                }

                $his_insert_sql .= ') VALUES(' . $row_data['did'];
                $his_insert_sql .= " , 'trans'";
                $his_insert_sql .= ', ' . $row_data['add_uid'];
                $his_insert_sql .= ", '" . $row_data['add_date'] . "'";

                foreach ($item_defs as $item_def) {
                    if ('' === $row_data[$item_name]) {
                        $his_insert_sql .= ', NULL';
                    } else {
                        $his_insert_sql .= ", '" . $row_data[$item_name] . "'";
                    }
                }

                $his_insert_sql .= ')';
                $xoopsDB->query($his_insert_sql);
            }
        }

        return true;
    }
}

<?php

$dirname = basename(dirname(dirname(__FILE__)));

eval('function xoops_module_update_' . $dirname . '($module, $prev_version){
    return xgdb_onupdate($module, $prev_version, "' . $dirname . '");
}');

if (!function_exists('xgdb_onupdate')) {
    function xgdb_onupdate($module, $prev_version, $dirname) {
        global $msgs, $xoopsConfig, $xoopsUser;
        $myts = &MyTextSanitizer::getInstance();
        if (!is_array($msgs)) $msgs = array();
        $xoopsDB = &Database::getInstance();
        $tplfile_tbl = $xoopsDB->prefix("tplfile");
        $tplsource_tbl = $xoopsDB->prefix("tplsource");
        $data_tbl = $xoopsDB->prefix($dirname . '_xgdb_data');
        $his_tbl = $xoopsDB->prefix($dirname . '_xgdb_his');
        $item_tbl = $xoopsDB->prefix($dirname . '_xgdb_item');
        $newblocks_tbl = $xoopsDB->prefix("newblocks");
        $mid = $module->getVar('mid');
        $module_upload_dir = XOOPS_UPLOAD_PATH . '/' . $dirname;

        $tplfile_handler = &xoops_gethandler('tplfile');
        $template_dir = XOOPS_ROOT_PATH . '/modules/' . $dirname . '/templates';
        include_once XOOPS_ROOT_PATH . '/class/template.php';
        $msgs[] = 'Updating templates...';
        if ($dir_handler = @opendir($template_dir . '/')) {
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
                        $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not insert template <b>' . $myts->htmlSpecialChars($xgdb_template_file) . '</b> to the database.</span>';
                    } else {
                        $template_id = $template->getVar('tpl_id');
                        $msgs[] = '&nbsp;&nbsp;Template <b>' . $myts->htmlSpecialChars($xgdb_template_file) . '</b> inserted to the database.';
                        if ($xoopsConfig['template_set'] == 'default') {
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
                    $msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not update template <b>' . $myts->htmlSpecialChars($xgdb_template_file) . '</b>.</span>';
                } else {
                    $msgs[] = '&nbsp;&nbsp;Template <b>' . $myts->htmlSpecialChars($xgdb_template_file) . '</b> updated.';
                    if ($xoopsConfig['template_set'] == 'default') {
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

        if ($prev_version < 30) {
            // アップロードディレクトリに直リンク禁止設定を追加
            $file = fopen(XOOPS_UPLOAD_PATH . "/" . $dirname . "/.htaccess", "w");
            flock($file, LOCK_EX);
            fputs($file, "order deny,allow\n");
            fputs($file, "deny from all\n");
            flock($file, LOCK_UN);
            fclose($file);

            // アップロードファイルのファイル名をURLエンコードしたものに変換
            $res_item = $xoopsDB->query("SELECT name FROM $item_tbl WHERE type = 'file' OR type = 'image'");
            while (list($col_name) = $xoopsDB->fetchRow($res_item)) {
                $res_data = $xoopsDB->query("SELECT id, $col_name FROM $data_tbl WHERE $col_name IS NOT NULL AND $col_name != ''");
                while (list($id, $file_name) = $xoopsDB->fetchRow($res_data)) {
                    $real_file_name = urlencode("$id-$col_name-$file_name");
                    @copy($module_upload_dir . '/' . $file_name, $module_upload_dir . '/' . $real_file_name);
                }
            }

            // xgdb_itemテーブルのカラム名ambiguousをsearch_condに変更
            $xoopsDB->query("ALTER TABLE `$item_tbl` CHANGE `ambiguous` `search_cond` TINYINT(1) UNSIGNED NULL DEFAULT NULL;");

            // xgdb_itemテーブルにdisp_condカラムを追加
            $xoopsDB->query("ALTER TABLE `$item_tbl` ADD `disp_cond` TINYINT(1) UNSIGNED NULL AFTER `input_desc`;");
        }

        if ($prev_version < 40) {
            // xgdb_itemテーブルにshow_gidsカラムを追加
            $xoopsDB->query("ALTER TABLE `$item_tbl` ADD `show_gids` VARCHAR(255) AFTER `required`;");

            // xgdb_itemテーブルのshow_gidsカラムに初期データを設定
            $res = $xoopsDB->query("SELECT groupid FROM " . $xoopsDB->prefix('groups') . " ORDER BY groupid ASC");
            $gidstring = '|';
            while (list($groupid) = $xoopsDB->fetchRow($res)) {
                $gidstring .= $groupid . '|';
            }
            $xoopsDB->query("UPDATE `$item_tbl` SET show_gids = '$gidstring'");
        }

        if ($prev_version < 50) {
            // 数値を持つテキストボックス型のtypeをテキストボックス(数値)に変換
            $res_item = $xoopsDB->query("SELECT name FROM $item_tbl WHERE type = 'text' AND (value_type = 'int' OR value_type = 'float')");
            while (list($col_name) = $xoopsDB->fetchRow($res_item)) {
                $xoopsDB->query("UPDATE `$item_tbl` SET type = 'number' WHERE name = '" . addslashes($col_name) . "'");
            }

            // 文字列を持つテキストボックス型のtypeをテキストボックス(文字列)に変換
            $res_item = $xoopsDB->query("SELECT name FROM $item_tbl WHERE type = 'text' AND value_type = 'string'");
            while (list($col_name) = $xoopsDB->fetchRow($res_item)) {
                $xoopsDB->query("UPDATE `$item_tbl` SET value_type = NULL WHERE name = '" . addslashes($col_name) . "'");
            }

            // xgdb_dataテーブルのカラム名idをdidに変更
            $xoopsDB->query("ALTER TABLE `$data_tbl` CHANGE `id` `did` INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT;");

            // xgdb_itemテーブルのlist_linkカラム名を削除
            $xoopsDB->query("ALTER TABLE `$item_tbl` DROP `list_link`;");

            // xgdb_dataテーブルのupdate_uidカラム名を削除
            $xoopsDB->query("ALTER TABLE `$data_tbl` DROP `update_uid`;");

            // xgdb_dataテーブルのupdate_dateカラム名を削除
            $xoopsDB->query("ALTER TABLE `$data_tbl` DROP `update_date`;");

            // xgdb_itemテーブルにxgdb_nameカラムを追加
            $xoopsDB->query("ALTER TABLE `$item_tbl` ADD `xgdb_name` VARCHAR(255);");

            // 更新履歴テーブルを作成
            $item_defs = array();
            $res_item = $xoopsDB->query("SELECT name, type, value_type FROM $item_tbl");
            while ($item_def = $xoopsDB->fetchArray($res_item)) {
                $item_defs[$item_def['name']] = $item_def;
            }

            $create_his_sql = "CREATE TABLE $his_tbl (";
            $create_his_sql .= "hid INT UNSIGNED NOT NULL AUTO_INCREMENT";
            $create_his_sql .= ", did INT UNSIGNED NOT NULL";
            $create_his_sql .= ", operation VARCHAR(255) NOT NULL";
            $create_his_sql .= ", update_uid INT UNSIGNED NOT NULL";
            $create_his_sql .= ", update_date DATETIME NOT NULL";

            foreach ($item_defs as $item_name => $item_def) {
                if ($item_name === 'did' || $item_name === 'add_uid' || $item_name === 'add_date') continue;

                $create_his_sql .= ', ' . $item_name;

                if ($item_def['type'] == 'text') {
                    $create_his_sql .= " VARCHAR(255)";
                } elseif ($item_def['type'] == 'tarea' || $item_def['type'] == 'xtarea') {
                    $create_his_sql .= " TEXT";
                } elseif ($item_def['type'] == 'file' || $item_def['type'] == 'image') {
                    $create_his_sql .= " VARCHAR(255)";
                } elseif (isset($item_def['value_type']) && $item_def['value_type'] == 'string') {
                    $create_his_sql .= " VARCHAR(255)";
                } elseif (isset($item_def['value_type']) && $item_def['value_type'] == 'int') {
                    $create_his_sql .= " INT";
                } elseif (isset($item_def['value_type']) && $item_def['value_type'] == 'float') {
                    $create_his_sql .= " FLOAT";
                } elseif ($item_def['type'] == 'date') {
                    $create_his_sql .= " DATE";
                }
            }
            $create_his_sql .= ", PRIMARY KEY(hid)";
            $create_his_sql .= ") ENGINE=MyISAM;";
            $xoopsDB->query($create_his_sql);

            // 更新履歴テーブルに初期データを登録
            $res_data = $xoopsDB->query("SELECT * FROM $data_tbl");
            while ($row_data = $xoopsDB->fetchArray($res_data)) {
                $his_insert_sql = "INSERT INTO $his_tbl (did, operation, update_uid, update_date";

                foreach ($item_defs as $item_name => $item_def) {
                    $his_insert_sql .= ', ' . $item_name;
                }

                $his_insert_sql .= ') VALUES(' . $row_data['did'];
                $his_insert_sql .= " , 'trans'";
                $his_insert_sql .= ", " . $row_data['add_uid'];
                $his_insert_sql .= ", '" . $row_data['add_date'] . "'";

                foreach ($item_defs as $item_def) {
                    if ($row_data[$item_name] === '') {
                        $his_insert_sql .= ", NULL";
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

?>
<?php

require_once '../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/header.php';
require_once './include/common.php';
$xoopsOption['template_main'] = $dirname . '_xgdb_update.html';

$op = isset($_POST['op']) ? $_POST['op'] : '';
$did = isset($_POST['did']) ? intval($_POST['did']) : 0;
if (isset($_POST['cancel'])) {
    header('Location: ' . $module_url . '/detail.php?did=' . $did);
}

// 存在チェック
$sql = "SELECT d.*, u.uname FROM $data_tbl AS d LEFT OUTER JOIN $users_tbl AS u ON d.add_uid = u.uid WHERE d.did = $did";
$res = $xoopsDB->query($sql);
if ($xoopsDB->getRowsNum($res) == 0) {
    redirect_header($module_url . '/index.php', 5, getMDConst('_NO_ERR_MSG'));
}

// 権限チェック
$row = $xoopsDB->fetchArray($res);
if (!checkPerm($gids, $cfg_manage_gids) && $uid != $row['add_uid']) {
    redirect_header($module_url . '/index.php', 5, getMDConst('_PERM_ERR_MSG'));
}

$errors = array();
$uploaded_file_defs = array();
$delete_file_names = array();
$update_item_defs = getDefs($item_defs, 'update');

// 更新処理
if ($op == 'update') {
    // トークンチェック
    if (!XoopsMultiTokenHandler::quickValidate($dirname . '_update')) {
        $errors[] = getMDConst('_TOKEN_ERR_MSG');
    }

    // 入力値初期化
    foreach ($update_item_defs as $item_name => $item_def) {
        $$item_name = initInput($item_def, $item_name, $update_item_defs, $uploaded_file_defs, $errors, 'update');
    }
    if (isset($_POST['delete_file_names'])) $delete_file_names = $_POST['delete_file_names'];

    // 重複チェック
    $dup_item_defs = getDefs($item_defs, 'duplicate');
    if (count($dup_item_defs) > 0) {
        foreach ($dup_item_defs as $item_name => $item_def) {
            checkDuplicate($$item_name, $item_name, $update_item_defs, $errors, $did);
        }
    }

    // エラーなしの場合、更新処理
    if (count($errors) == 0) {
        $datetime = date('Y-m-d H:i:s');
        $update_data_sql = "UPDATE $data_tbl SET ";
        foreach ($update_item_defs as $item_name => $item_def) {
            // ファイル、画像がある場合
            if ($item_def['type'] == 'file' || $item_def['type'] == 'image') {
                if (isset($delete_file_names[$item_name]) && $delete_file_names[$item_name] !== '') {
                    $update_data_sql .= $item_name . " = '', ";
                    $delete_file_names[$item_name] = $row[$item_name];
                } elseif (isset($uploaded_file_defs[$item_name]) && $uploaded_file_defs[$item_name] !== '') {
                    $file_name = $_FILES[$item_name]['name'];
                    $enc_file_name = getRealFileName($did, $item_name, $file_name);
                    if (!move_uploaded_file($_FILES[$item_name]['tmp_name'], $module_upload_dir . '/' . $enc_file_name)) {
                        $errors[] = sprintf(getMDConst('_FILE_TYPE_ERR_MSG'), $myts->htmlSpecialChars($_FILES[$item_name]['type']), $item_def['caption']);
                        $update_item_defs[$item_name]['error'] = '<br />' . sprintf(getMDConst('_FILE_TYPE_ERR_MSG'), $myts->htmlSpecialChars($_FILES[$item_name]['type']), $item_def['caption']);
                        break;
                    } else {
                        if ($file_name !== $row[$item_name]) $delete_file_names[$item_name] = $row[$item_name];
                        if ($item_def['type'] == 'image') {
                            resizeImage($module_upload_dir . '/' . $enc_file_name, $item_def['max_image_size']);
                        }
                    }
                    $update_data_sql .= $item_name . " = '" . addslashes($file_name) . "', ";
                }
            } elseif (($item_def['type'] == 'cbox' || $item_def['type'] == 'mselect') && is_array($$item_name)) {
                $update_data_sql .= $item_name . " = '" . addslashes(array2string($$item_name)) . "', ";
            } else {
                if ($$item_name === '') {
                    $update_data_sql .= $item_name . " = NULL, ";
                } else {
                    $update_data_sql .= $item_name . " = '" . addslashes($$item_name) . "', ";
                }
            }
        }
        $update_data_sql = substr($update_data_sql, 0, -2) . " WHERE did = $did";

        // 更新処理成功の場合、更新履歴を追加し、古いファイルを削除して詳細ページへリダイレクト
        if ($xoopsDB->query($update_data_sql)) {
            // 更新履歴追加
            $insert_his_sql = "INSERT INTO $his_tbl (did, operation, update_uid, update_date";
            foreach ($item_defs as $item_name => $item_def) {
                $insert_his_sql .= ', ' . $item_name;
            }
            $insert_his_sql .= ") VALUES($did, 'update', $uid, '$datetime'";
            foreach ($item_defs as $item_name => $item_def) {
                if (array_key_exists($item_name, $update_item_defs)) {
                    if ($item_def['type'] == 'file' || $item_def['type'] == 'image') {
                        if (isset($delete_file_names[$item_name]) && $delete_file_names[$item_name] !== '') {
                            $insert_his_sql .= ", NULL";
                        } elseif (isset($uploaded_file_defs[$item_name]) && $uploaded_file_defs[$item_name] !== '') {
                            $file_name = $_FILES[$item_name]['name'];
                            $enc_file_name = getRealFileName($did, $item_name, $file_name);
                            $insert_his_sql .= ", '" . addslashes($file_name) . "'";
                        } else {
                            $insert_his_sql .= ", '" . addslashes($row[$item_name]) . "'";
                        }
                    } elseif (($item_def['type'] == 'cbox' || $item_def['type'] == 'mselect') && is_array($$item_name)) {
                        $insert_his_sql .= ", '" . addslashes(array2string($$item_name)) . "'";
                    } else {
                        if ($$item_name === '') {
                            $insert_his_sql .= ", NULL";
                        } else {
                            $insert_his_sql .= ", '" . addslashes($$item_name) . "'";
                        }
                    }
                } else {
                    if ($row[$item_name] !== '') {
                        $insert_his_sql .= ", NULL";
                    } else {
                        $insert_his_sql .= ", '" . $row[$item_name] . "'";
                    }
                }
            }
            $insert_his_sql .= ')';
            $xoopsDB->query($insert_his_sql);

            foreach ($delete_file_names as $item_name => $delete_file_name) {
                @unlink($module_upload_dir . '/' . getRealFileName($did, $item_name, $delete_file_name));
            }

            $extra_tags = array('DID' => $did);
            $notification_handler = &xoops_gethandler('notification');
            $notification_handler->triggerEvent('change', $did, 'update', $extra_tags);

            redirect_header($module_url . '/detail.php?did=' . $did, 5, getMDConst('_UPDATE_MSG'));
        } else {
            $errors[] = getMDConst('_SYSTEM_ERR_MSG');
        }
    }
} else {
    foreach ($update_item_defs as $item_name => $item_def) {
        // 初期表示処理
        if ($item_def['type'] == 'cbox' || $item_def['type'] == 'mselect') {
            $$item_name = string2array($row[$item_name]);
        } elseif (isset($item_def['value_type']) && $item_def['value_type'] == 'float') {
            $$item_name = sanitize($row[$item_name], $item_def);
        } else {
            $$item_name = $row[$item_name];
        }
        $update_item_defs[$item_name]['raw'] = $$item_name;
    }
}

// 表示値割り当て
foreach ($row as $key => $value) {
    if ($key == 'did' || $key == 'add_uid' || $key == 'uname') {
        $item_defs[$key]['value'] = $myts->htmlSpecialChars($value);
    } elseif ($key == 'add_date') {
        $item_defs[$key]['value'] = date($cfg_date_format . ' ' . $cfg_time_format, strtotime($value));
    } elseif (!isset($item_defs[$key])) {
        continue;
    }
}
$xoopsTpl->assign('item_defs', $item_defs);

// トークン生成
$token = &XoopsMultiTokenHandler::quickCreate($dirname . '_update');
$xoopsTpl->assign('token', $token->getHtml());

// フォーム生成
foreach ($update_item_defs as $item_name => $update_item_def) {
    if (($update_item_def['type'] == 'image' || $update_item_def['type'] == 'file') && isset($row[$item_name]) && $row[$item_name] !== '') {
        if ($update_item_def['type'] == 'image') {
            $update_item_defs[$item_name]['width'] = getImageWidth($module_upload_dir . '/' . getRealFileName($did, $item_name, $row[$item_name]), $cfg_main_img_wd);
        }
        $update_item_defs[$item_name]['current_value'] = $myts->htmlSpecialChars($row[$item_name]);
    }
}

makeInputForms($update_item_defs);
$xoopsTpl->assign('update_item_defs', $update_item_defs);
$xoopsTpl->assign('errors', $errors);

require_once XOOPS_ROOT_PATH . '/footer.php';

?>

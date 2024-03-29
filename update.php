<?php

$dirname                                 = basename(__DIR__);
$GLOBALS['xoopsOption']['template_main'] = $dirname . '_xgdb_update.tpl';

require_once dirname(__DIR__, 2) . '/mainfile.php';
require_once XOOPS_ROOT_PATH . '/header.php';
require_once __DIR__ . '/include/common.php';

$op  = $_POST['op'] ?? '';
$did = isset($_POST['did']) ? (int)$_POST['did'] : 0;
if (isset($_POST['cancel'])) {
    header('Location: ' . $module_url . '/detail.php?did=' . $did);
}

// Existence check
$sql = "SELECT d.*, u.uname FROM $data_tbl AS d LEFT OUTER JOIN $users_tbl AS u ON d.add_uid = u.uid WHERE d.did = $did";
$res = $xoopsDB->query($sql);
if (0 === $xoopsDB->getRowsNum($res)) {
    redirect_header($module_url . '/index.php', 5, getMDConst('_NO_ERR_MSG'));
}

// Authorization check
$row = $xoopsDB->fetchArray($res);
if (!checkPerm($gids, $cfg_manage_gids) && $uid != $row['add_uid']) {
    redirect_header($module_url . '/index.php', 5, getMDConst('_PERM_ERR_MSG'));
}

$errors             = [];
$uploaded_file_defs = [];
$delete_file_names  = [];
$update_item_defs   = getDefs($item_defs, 'update');

// Update process
if ('update' === $op) {
    // Token check
    if (!XoopsMultiTokenHandler::quickValidate($dirname . '_update')) {
        $errors[] = getMDConst('_TOKEN_ERR_MSG');
    }

    // Input value initialization
    foreach ($update_item_defs as $item_name => $item_def) {
        $$item_name = initInput($item_def, $item_name, $update_item_defs, $uploaded_file_defs, $errors, 'update');
    }
    if (isset($_POST['delete_file_names'])) {
        $delete_file_names = $_POST['delete_file_names'];
    }

    // double check
    $dup_item_defs = getDefs($item_defs, 'duplicate');
    if (0 < count($dup_item_defs)) {
        foreach ($dup_item_defs as $item_name => $item_def) {
            checkDuplicate($$item_name, $item_name, $update_item_defs, $errors, $did);
        }
    }

    // If there is no error, update process
    if (0 === count($errors)) {
        $datetime        = date('Y-m-d H:i:s');
        $update_data_sql = "UPDATE $data_tbl SET ";
        foreach ($update_item_defs as $item_name => $item_def) {
            // If there are files and images
            if ('file' === $item_def['type'] || 'image' === $item_def['type']) {
                if (isset($delete_file_names[$item_name]) && '' !== $delete_file_names[$item_name]) {
                    $update_data_sql               .= $item_name . " = '', ";
                    $delete_file_names[$item_name] = $row[$item_name];
                } elseif (isset($uploaded_file_defs[$item_name]) && '' !== $uploaded_file_defs[$item_name]) {
                    $file_name     = $_FILES[$item_name]['name'];
                    $enc_file_name = getRealFileName($did, $item_name, $file_name);
                    if (!move_uploaded_file($_FILES[$item_name]['tmp_name'], $module_upload_dir . '/' . $enc_file_name)) {
                        $errors[]                              = sprintf(getMDConst('_FILE_TYPE_ERR_MSG'), htmlspecialchars($_FILES[$item_name]['type'], ENT_QUOTES | ENT_HTML5), $item_def['caption']);
                        $update_item_defs[$item_name]['error'] = '<br>' . sprintf(getMDConst('_FILE_TYPE_ERR_MSG'), htmlspecialchars($_FILES[$item_name]['type'], ENT_QUOTES | ENT_HTML5), $item_def['caption']);
                        break;
                    }
                    if ($file_name !== $row[$item_name]) {
                        $delete_file_names[$item_name] = $row[$item_name];
                    }
                    if ('image' === $item_def['type']) {
                        resizeImage($module_upload_dir . '/' . $enc_file_name, $item_def['max_image_size']);
                    }

                    $update_data_sql .= $item_name . " = '" . addslashes($file_name) . "', ";
                }
            } elseif (('cbox' === $item_def['type'] || 'mselect' === $item_def['type']) && is_array($$item_name)) {
                $update_data_sql .= $item_name . " = '" . addslashes(array2string($$item_name)) . "', ";
            } elseif ('' === $$item_name) {
                $update_data_sql .= $item_name . ' = NULL, ';
            } else {
                $update_data_sql .= $item_name . " = '" . addslashes($$item_name) . "', ";
            }
        }
        $update_data_sql = mb_substr($update_data_sql, 0, -2) . " WHERE did = $did";

        // If the update process is successful, add the update history, delete the old file and redirect to the detail page.
        if ($xoopsDB->query($update_data_sql)) {
            // Update history added
            $insert_his_sql = "INSERT INTO $his_tbl (did, operation, update_uid, update_date";
            foreach ($item_defs as $item_name => $item_def) {
                $insert_his_sql .= ', ' . $item_name;
            }
            $insert_his_sql .= ") VALUES($did, 'update', $uid, '$datetime'";
            foreach ($item_defs as $item_name => $item_def) {
                if (array_key_exists($item_name, $update_item_defs)) {
                    if ('file' === $item_def['type'] || 'image' === $item_def['type']) {
                        if (isset($delete_file_names[$item_name]) && '' !== $delete_file_names[$item_name]) {
                            $insert_his_sql .= ', NULL';
                        } elseif (isset($uploaded_file_defs[$item_name]) && '' !== $uploaded_file_defs[$item_name]) {
                            $file_name      = $_FILES[$item_name]['name'];
                            $enc_file_name  = getRealFileName($did, $item_name, $file_name);
                            $insert_his_sql .= ", '" . addslashes($file_name) . "'";
                        } else {
                            $insert_his_sql .= ", '" . addslashes($row[$item_name]) . "'";
                        }
                    } elseif (('cbox' === $item_def['type'] || 'mselect' === $item_def['type']) && is_array($$item_name)) {
                        $insert_his_sql .= ", '" . addslashes(array2string($$item_name)) . "'";
                    } elseif ('' === $$item_name) {
                        $insert_his_sql .= ', NULL';
                    } else {
                        $insert_his_sql .= ", '" . addslashes($$item_name) . "'";
                    }
                } elseif ('' !== $row[$item_name]) {
                    $insert_his_sql .= ', NULL';
                } else {
                    $insert_his_sql .= ", '" . $row[$item_name] . "'";
                }
            }
            $insert_his_sql .= ')';
            $xoopsDB->query($insert_his_sql);

            foreach ($delete_file_names as $item_name => $delete_file_name) {
                @unlink($module_upload_dir . '/' . getRealFileName($did, $item_name, $delete_file_name));
            }

            $extra_tags          = ['DID' => $did];
            $notificationHandler = xoops_getHandler('notification');
            $notificationHandler->triggerEvent('change', $did, 'update', $extra_tags);

            redirect_header($module_url . '/detail.php?did=' . $did, 5, getMDConst('_UPDATE_MSG'));
        } else {
            $errors[] = getMDConst('_SYSTEM_ERR_MSG');
        }
    }
} else {
    foreach ($update_item_defs as $item_name => $item_def) {
        // Initial display processing
        if ('cbox' === $item_def['type'] || 'mselect' === $item_def['type']) {
            $$item_name = string2array($row[$item_name]);
        } elseif (isset($item_def['value_type']) && 'float' === $item_def['value_type']) {
            $$item_name = sanitize($row[$item_name], $item_def);
        } else {
            $$item_name = $row[$item_name];
        }
        $update_item_defs[$item_name]['raw'] = $$item_name;
    }
}

// Display value assignment
foreach ($row as $key => $value) {
    if ('did' === $key || 'add_uid' === $key || 'uname' === $key) {
        $item_defs[$key]['value'] = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5);
    } elseif ('add_date' === $key) {
        $item_defs[$key]['value'] = date($cfg_date_format . ' ' . $cfg_time_format, strtotime($value));
    } elseif (!isset($item_defs[$key])) {
    }
}
$xoopsTpl->assign('item_defs', $item_defs);

// Token generation
$token = XoopsMultiTokenHandler::quickCreate($dirname . '_update');
$xoopsTpl->assign('token', $token->getHtml());

// Form generation
foreach ($update_item_defs as $item_name => $update_item_def) {
    if (('image' === $update_item_def['type'] || 'file' === $update_item_def['type']) && isset($row[$item_name]) && '' !== $row[$item_name]) {
        if ('image' === $update_item_def['type']) {
            $update_item_defs[$item_name]['width'] = getImageWidth($module_upload_dir . '/' . getRealFileName($did, $item_name, $row[$item_name]), $cfg_main_img_wd);
        }
        $update_item_defs[$item_name]['current_value'] = htmlspecialchars($row[$item_name], ENT_QUOTES | ENT_HTML5);
    }
}

makeInputForms($update_item_defs);
$xoopsTpl->assign('update_item_defs', $update_item_defs);
$xoopsTpl->assign('errors', $errors);

require_once XOOPS_ROOT_PATH . '/footer.php';

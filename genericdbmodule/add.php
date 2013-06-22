<?php

require_once '../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/header.php';
require_once './include/common.php';
require_once './class/token.php';
$xoopsOption['template_main'] = $dirname . '_xgdb_add.html';

// ���¥����å�
if ($uid && !checkPerm($gids, $cfg_add_gids)) {
    redirect_header($module_url . '/index.php', 5, getMDConst('_PERM_ERR_MSG'));
} elseif (!$uid && !$cfg_add_guest) {
    redirect_header($module_url . '/index.php', 5, getMDConst('_PERM_ERR_MSG'));
}

$op = isset($_POST['op']) ? $_POST['op'] : '';

$errors = array();
$uploaded_file_defs = array();
$upload_file_names = array();

// ��Ͽ����
if ($op == 'add') {
    // �ȡ���������å�
    if (!XoopsMultiTokenHandler::quickValidate($dirname . '_add')) {
        $errors[] = getMDConst('_TOKEN_ERR_MSG');
    }

    // �����ͽ����
    foreach ($item_defs as $item_name => $item_def) {
        $$item_name = initInput($item_def, $item_name, $item_defs, $uploaded_file_defs, $errors, 'add');
    }

    // ��ʣ�����å�
    $dup_item_defs = getDefs($item_defs, 'duplicate');
    if (count($dup_item_defs) > 0) {
        foreach ($dup_item_defs as $item_name => $item_def) {
            checkDuplicate($$item_name, $item_name, $update_item_defs, $errors);
        }
    }

    // ���顼�ʤ��ξ�硢��Ͽ����
    if (count($errors) == 0) {
        $datetime = date('Y-m-d H:i:s');

        $insert_data_sql = "INSERT INTO $data_tbl (add_uid, add_date, ";
        $insert_his_sql = "INSERT INTO $his_tbl (did, operation, update_uid, update_date, ";
        foreach ($item_defs as $item_name => $item_def) {
            $insert_data_sql .= $item_name . ', ';
            $insert_his_sql .= $item_name . ', ';
        }
        $insert_data_sql = substr($insert_data_sql, 0, -2) . ") VALUES(";
        $insert_his_sql = substr($insert_his_sql, 0, -2) . ") VALUES(";
        $insert_data_sql .= "$uid, '$datetime', ";
        $insert_his_sql .= "%d, 'add', $uid, '$datetime', ";
        foreach ($item_defs as $item_name => $item_def) {
            if (($item_def['type'] == 'cbox' || $item_def['type'] == 'mselect') && is_array($$item_name)) {
                $insert_data_sql .= "'" . addslashes(array2string($$item_name)) . "', ";
                $insert_his_sql .= "'" . addslashes(array2string($$item_name)) . "', ";
            } else {
                if ($$item_name === '') {
                    $insert_data_sql .= 'NULL, ';
                    $insert_his_sql .= 'NULL, ';
                } else {
                    $insert_data_sql .= "'" . addslashes($$item_name) . "', ";
                    $insert_his_sql .= "'" . addslashes($$item_name) . "', ";
                }
            }
        }
        $insert_data_sql = substr($insert_data_sql, 0, -2) . ")";
        $insert_his_sql = substr($insert_his_sql, 0, -2) . ")";

        // ��ϿSQL���������ξ��
        if ($xoopsDB->query($insert_data_sql)) {
            $did = $xoopsDB->getInsertId();

            // ���������ɲ�
            $insert_his_sql = sprintf($insert_his_sql, $did);
            $xoopsDB->query($insert_his_sql);

            // �ե����롢������������
            if (count($uploaded_file_defs) > 0) {
                $update_sql = "UPDATE $data_tbl SET ";
                foreach ($uploaded_file_defs as $item_name => $item_def) {
                    $file_name = $_FILES[$item_name]['name'];
                    $enc_file_name = getRealFileName($did, $item_name, $file_name);
                    $update_sql .= "$item_name = '" . addslashes($file_name) . "', ";
                    if (!move_uploaded_file($_FILES[$item_name]['tmp_name'], $module_upload_dir . '/' . $enc_file_name)) {
                        $errors[] = sprintf(getMDConst('_FILE_TYPE_ERR_MSG'), $myts->htmlSpecialChars($_FILES[$item_name]['type']), $item_def['caption']);
                        $item_defs[$item_name]['error'] = '<br />' . sprintf(getMDConst('_FILE_TYPE_ERR_MSG'), $myts->htmlSpecialChars($_FILES[$item_name]['type']), $item_def['caption']);
                        break;
                    } else {
                        $upload_file_names[] = $enc_file_name;
                        if ($item_def['type'] == 'image') {
                            resizeImage($module_upload_dir . '/' . $enc_file_name, $item_def['max_image_size']);
                        }
                    }
                }

                // �ե�����ط��ν����ǥ��顼�ʤ��ξ��
                if (count($errors) == 0) {
                    $update_sql = substr($update_sql, 0, -2) . " WHERE did = $did";
                    if (!$xoopsDB->query($update_sql)) {
                        $xoopsDB->query("DELETE FROM $main_tbl WHERE did = $did");
                    }
                } else {
                    // ��Ͽ�������Ԥξ�硢���åץ��ɤ��줿�ե��������
                    foreach ($upload_file_names as $upload_file_name) {
                        @unlink($module_upload_dir . '/' . $upload_file_name);
                    }
                }
            }

            // �ܺٲ��̤إ�����쥯��
            $extra_tags = array('DID' => $did);
            $notification_handler = &xoops_gethandler('notification');
            $notification_handler->triggerEvent('global', 0, 'add', $extra_tags);

            redirect_header($module_url . '/detail.php?did=' . $did, 5, getMDConst('_ADD_MSG'));
        } else {
            // ��ϿSQL�������Ԥξ��
            $errors[] = getMDConst('_SYSTEM_ERR_MSG');
        }
    }
} else {
    // ���ɽ������
    foreach ($item_defs as $item_name => $item_def) {
        $$item_name = $item_def['default'];
        $item_defs[$item_name]['raw'] = $$item_name;
    }
}

// �ȡ���������
$token = &XoopsMultiTokenHandler::quickCreate($dirname . '_add');
$xoopsTpl->assign('token', $token->getHtml());

// �ե���������
makeInputForms($item_defs);
$xoopsTpl->assign('item_defs', $item_defs);
$xoopsTpl->assign('errors', $errors);

require_once XOOPS_ROOT_PATH . '/footer.php';

?>

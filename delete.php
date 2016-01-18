<?php

require_once '../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/header.php';
require_once './include/common.php';
$xoopsOption['template_main'] = $dirname . '_xgdb_delete.html';

$op = isset($_POST['op']) ? $_POST['op'] : '';
$did = isset($_POST['did']) ? intval($_POST['did']) : 0;

// ¸�ߥ����å�
$sql = "SELECT d.*, u.uname FROM $data_tbl AS d LEFT OUTER JOIN $users_tbl AS u ON d.add_uid = u.uid WHERE d.did = $did";
$res = $xoopsDB->query($sql);
if ($xoopsDB->getRowsNum($res) == 0) {
    redirect_header($module_url . '/index.php', 5, getMDConst('_NO_ERR_MSG'));
}

// ���¥����å�
$row = $xoopsDB->fetchArray($res);
if (!checkPerm($gids, $cfg_manage_gids) && $uid != $row['add_uid']) {
    redirect_header($module_url . '/index.php', 5, getMDConst('_PERM_ERR_MSG'));
}

$errors = array();
if ($op == 'delete') {
    // �ȡ���������å�
    if (!XoopsMultiTokenHandler::quickValidate($dirname . '_delete')) {
        $errors[] = getMDConst('_TOKEN_ERR_MSG');
    } else {
        if (!$xoopsDB->query("DELETE FROM $data_tbl WHERE did = $did")) {
            $errors[] = getMDConst('_SYSTEM_ERR_MS');
        } else {
            // ���������ɲ�
            $datetime = date('Y-m-d H:i:s');
            $insert_his_sql = "INSERT INTO $his_tbl (did, operation, update_uid, update_date";
            foreach ($item_defs as $item_name => $item_def) {
                $insert_his_sql .= ', ' . $item_name;
            }
            $insert_his_sql .= ") VALUES($did, 'delete', $uid, '$datetime'";
            foreach ($item_defs as $item_name => $item_def) {
                if ($row[$item_name] !== '') {
                    $insert_his_sql .= ", NULL";
                } else {
                    $insert_his_sql .= ", '" . $row[$item_name] . "'";
                }
            }
            $insert_his_sql .= ')';
            $xoopsDB->query($insert_his_sql);

            foreach ($item_defs as $item_name => $item_def) {
                if ($item_def['type'] == 'file' || $item_def['type'] == 'image') {
                    @unlink($module_upload_dir . '/' . getRealFileName($did, $item_name, $row[$item_name]));
                }
            }

            $extra_tags = array('DID' => $did);
            $notification_handler = &xoops_gethandler('notification');
            $notification_handler->triggerEvent('change', $did, 'delete', $extra_tags);
            $notification_handler->unsubscribeByItem($xoopsModule->getVar('mid'), 'change', $did);

            redirect_header($module_url . '/index.php', 5, getMDConst('_DELETE_MSG'));
        }
    }
} else {
    // ɽ���ͳ������
    assignDetail($row, $item_defs, $dirname);
    $xoopsTpl->assign('item_defs', $item_defs);
}

// �ȡ���������
$token = &XoopsMultiTokenHandler::quickCreate($dirname . '_delete');
$xoopsTpl->assign('token', $token->getHtml());

$xoopsTpl->assign('errors', $errors);

require_once XOOPS_ROOT_PATH . '/footer.php';

?>

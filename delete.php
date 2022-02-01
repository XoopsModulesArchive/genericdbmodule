<?php

require_once dirname(__DIR__, 2) . '/mainfile.php';
require_once XOOPS_ROOT_PATH . '/header.php';
require_once __DIR__ . '/include/common.php';
$GLOBALS['xoopsOption']['template_main'] = $dirname . '_xgdb_delete.tpl';

$op  = $_POST['op'] ?? '';
$did = isset($_POST['did']) ? (int)$_POST['did'] : 0;

// Existence check
$sql = "SELECT d.*, u.uname FROM $data_tbl AS d LEFT OUTER JOIN $users_tbl AS u ON d.add_uid = u.uid WHERE d.did = $did";
$res = $xoopsDB->query($sql);
if (0 === $xoopsDB->getRowsNum($res)) {
    redirect_header($module_url . '/index.php', 5, getMDConst('_NO_ERR_MSG'));
}

// Authorization check
$row = $xoopsDB->fetchArray($res);
if (!checkPerm($gids, $cfg_manage_gids) && $uid !== $row['add_uid']) {
    redirect_header($module_url . '/index.php', 5, getMDConst('_PERM_ERR_MSG'));
}

$errors = [];
if ('delete' === $op) {
    // Token check
    if (XoopsMultiTokenHandler::quickValidate($dirname . '_delete')) {
        if ($xoopsDB->query("DELETE FROM $data_tbl WHERE did = $did")) {
            // Update history added
            $datetime       = date('Y-m-d H:i:s');
            $insert_his_sql = "INSERT INTO $his_tbl (did, operation, update_uid, update_date";
            foreach ($item_defs as $item_name => $item_def) {
                $insert_his_sql .= ', ' . $item_name;
            }
            $insert_his_sql .= ") VALUES($did, 'delete', $uid, '$datetime'";
            foreach ($item_defs as $item_name => $item_def) {
                if ('' !== $row[$item_name]) {
                    $insert_his_sql .= ', NULL';
                } else {
                    $insert_his_sql .= ", '" . $row[$item_name] . "'";
                }
            }
            $insert_his_sql .= ')';
            $xoopsDB->query($insert_his_sql);

            foreach ($item_defs as $item_name => $item_def) {
                if ('file' === $item_def['type'] || 'image' === $item_def['type']) {
                    @unlink($module_upload_dir . '/' . getRealFileName($did, $item_name, $row[$item_name]));
                }
            }

            $extra_tags          = ['DID' => $did];
            $notificationHandler = xoops_getHandler('notification');
            $notificationHandler->triggerEvent('change', $did, 'delete', $extra_tags);
            $notificationHandler->unsubscribeByItem($xoopsModule->getVar('mid'), 'change', $did);

            redirect_header($module_url . '/index.php', 5, getMDConst('_DELETE_MSG'));
        } else {
            $errors[] = getMDConst('_SYSTEM_ERR_MS');
        }
    } else {
        $errors[] = getMDConst('_TOKEN_ERR_MSG');
    }
} else {
    // Display value assignment
    assignDetail($row, $item_defs, $dirname);
    $xoopsTpl->assign('item_defs', $item_defs);
}

// Token generation
$token = XoopsMultiTokenHandler::quickCreate($dirname . '_delete');
$xoopsTpl->assign('token', $token->getHtml());

$xoopsTpl->assign('errors', $errors);

require_once XOOPS_ROOT_PATH . '/footer.php';

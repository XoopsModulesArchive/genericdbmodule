<?php

require_once dirname(__DIR__, 2) . '/mainfile.php';
require_once XOOPS_ROOT_PATH . '/header.php';
require XOOPS_ROOT_PATH . '/include/comment_view.php';
require_once __DIR__ . '/include/common.php';
$GLOBALS['xoopsOption']['template_main'] = $dirname . '_xgdb_his_detail.tpl';

// Authorization check
if ($uid && !checkPerm($gids, $cfg_his_gids)) {
    redirect_header($module_url . '/index.php', 5, getMDConst('_PERM_ERR_MSG'));
} elseif (!$uid && !$cfg_his_guest) {
    redirect_header($module_url . '/index.php', 5, getMDConst('_PERM_ERR_MSG'));
}

if (isset($_POST['op'])) {
    $op = $_POST['op'];
} elseif (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = '';
}
$xoopsTpl->assign('op', htmlspecialchars($op, ENT_QUOTES | ENT_HTML5));

// Existence check
$hid     = isset($_GET['hid']) ? (int)$_GET['hid'] : 0;
$his_sql = "SELECT h.*, u.uname FROM $his_tbl AS h LEFT OUTER JOIN $users_tbl AS u ON h.update_uid = u.uid WHERE h.hid = $hid";
$his_res = $xoopsDB->query($his_sql);
if (0 == $xoopsDB->getRowsNum($his_res)) {
    redirect_header($module_url . '/index.php', 5, getMDConst('_NO_ERR_MSG'));
}

$his_row = $xoopsDB->fetchArray($his_res);
$did     = $his_row['did'];
$xoopsTpl->assign('did', $did);

$operation_raw = $his_row['operation'];
$xoopsTpl->assign('operation_raw', htmlspecialchars($operation_raw, ENT_QUOTES | ENT_HTML5));
$xoopsTpl->assign('operation', htmlspecialchars(getOperation($operation_raw), ENT_QUOTES | ENT_HTML5));

// History display value allocation
assignDetail($his_row, $item_defs, $dirname);
$xoopsTpl->assign('item_defs', $item_defs);

// If the operation is an update, assign the contents before the change
if ('update' === $operation_raw) {
    $bef_his_sql = 'SELECT h.*, u.uname ';
    $bef_his_sql .= "FROM $his_tbl AS h LEFT OUTER JOIN $users_tbl AS u ON h.update_uid = u.uid ";
    $bef_his_sql .= "WHERE h.did = $did AND h.hid < $hid ";
    $bef_his_sql .= 'ORDER BY hid DESC LIMIT 1';
    $bef_his_res = $xoopsDB->query($bef_his_sql);
    $bef_his_row = $xoopsDB->fetchArray($bef_his_res);

    assignDetail($bef_his_row, $item_defs, $dirname);
    $xoopsTpl->assign('bef_item_defs', $item_defs);
}

require_once XOOPS_ROOT_PATH . '/footer.php';

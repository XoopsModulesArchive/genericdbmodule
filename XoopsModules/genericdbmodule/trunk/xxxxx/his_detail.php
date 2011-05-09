<?php

require_once '../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/header.php';
include XOOPS_ROOT_PATH . '/include/comment_view.php';
require_once './include/common.php';
$xoopsOption['template_main'] = $dirname . '_xgdb_his_detail.html';

// 権限チェック
if ($uid && !checkPerm($gids, $cfg_his_gids)) {
    redirect_header($module_url . '/index.php', 5, getMDConst('_PERM_ERR_MSG'));
} elseif (!$uid && !$cfg_his_guest) {
    redirect_header($module_url . '/index.php', 5, getMDConst('_PERM_ERR_MSG'));
}

if (isset($_POST['op'])) $op = $_POST['op'];
elseif (isset($_GET['op'])) $op = $_GET['op'];
else $op = '';
$xoopsTpl->assign('op', $myts->htmlSpecialChars($op));

// 存在チェック
$hid = isset($_GET['hid']) ? intval($_GET['hid']) : 0;
$his_sql = "SELECT h.*, u.uname FROM $his_tbl AS h LEFT OUTER JOIN $users_tbl AS u ON h.update_uid = u.uid WHERE h.hid = $hid";
$his_res = $xoopsDB->query($his_sql);
if ($xoopsDB->getRowsNum($his_res) == 0) {
    redirect_header($module_url . '/index.php', 5, getMDConst('_NO_ERR_MSG'));
}

$his_row = $xoopsDB->fetchArray($his_res);
$did = $his_row['did'];
$xoopsTpl->assign('did', $did);

$operation_raw = $his_row['operation'];
$xoopsTpl->assign('operation_raw', $myts->htmlSpecialChars($operation_raw));
$xoopsTpl->assign('operation', $myts->htmlSpecialChars(getOperation($operation_raw)));

// 履歴の表示値割り当て
assignDetail($his_row, $item_defs, $dirname);
$xoopsTpl->assign('item_defs', $item_defs);

// 操作が更新の場合、変更前の内容を割り当て
if ($operation_raw == 'update') {
    $bef_his_sql = "SELECT h.*, u.uname ";
    $bef_his_sql .= "FROM $his_tbl AS h LEFT OUTER JOIN $users_tbl AS u ON h.update_uid = u.uid ";
    $bef_his_sql .= "WHERE h.did = $did AND h.hid < $hid ";
    $bef_his_sql .= "ORDER BY hid DESC LIMIT 1";
    $bef_his_res = $xoopsDB->query($bef_his_sql);
    $bef_his_row = $xoopsDB->fetchArray($bef_his_res);

    assignDetail($bef_his_row, $item_defs, $dirname);
    $xoopsTpl->assign('bef_item_defs', $item_defs);
}

require_once XOOPS_ROOT_PATH . '/footer.php';

?>

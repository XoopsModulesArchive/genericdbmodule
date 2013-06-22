<?php

require_once '../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/header.php';
include XOOPS_ROOT_PATH . '/include/comment_view.php';
require_once './include/common.php';
$xoopsOption['template_main'] = $dirname . '_xgdb_detail.html';

// 存在チェック
$did = isset($_GET['did']) ? intval($_GET['did']) : 0;
$sql = "SELECT d.*, u.uname FROM $data_tbl AS d LEFT OUTER JOIN $users_tbl AS u ON d.add_uid = u.uid WHERE d.did = $did";
$res = $xoopsDB->query($sql);
if ($xoopsDB->getRowsNum($res) == 0) {
    redirect_header($module_url . '/index.php', 5, getMDConst('_NO_ERR_MSG'));
}

// 表示値割り当て
$row = $xoopsDB->fetchArray($res);
assignDetail($row, $item_defs, $dirname);
$xoopsTpl->assign('item_defs', $item_defs);

$perm = checkPerm($gids, $cfg_manage_gids) || $uid == $row['add_uid'] ? true : false;
$xoopsTpl->assign('perm', $perm);

$his_perm = false;
if ($uid && checkPerm($gids, $cfg_his_gids)) $his_perm = true;
elseif (!$uid && $cfg_his_guest) $his_perm = true;
$xoopsTpl->assign('his_perm', $his_perm);
if ($his_perm) $xoopsTpl->assign('histories', getHistories($did));

require_once XOOPS_ROOT_PATH . '/footer.php';

?>

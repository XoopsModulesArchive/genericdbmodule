<?php

$dirname                                 = basename(__DIR__);
$GLOBALS['xoopsOption']['template_main'] = $dirname . '_xgdb_detail.tpl';

require_once dirname(__DIR__, 2) . '/mainfile.php';
require_once XOOPS_ROOT_PATH . '/header.php';
require XOOPS_ROOT_PATH . '/include/comment_view.php';
require_once __DIR__ . '/include/common.php';

// Existence check
$did = isset($_GET['did']) ? (int)$_GET['did'] : 0;
$sql = "SELECT d.*, u.uname FROM $data_tbl AS d LEFT OUTER JOIN $users_tbl AS u ON d.add_uid = u.uid WHERE d.did = $did";
$res = $xoopsDB->query($sql);
if (0 === $xoopsDB->getRowsNum($res)) {
    redirect_header($module_url . '/index.php', 5, getMDConst('_NO_ERR_MSG'));
}

// Display value assignment
$row = $xoopsDB->fetchArray($res);
assignDetail($row, $item_defs, $dirname);
$xoopsTpl->assign('item_defs', $item_defs);

$perm = (checkPerm($gids, $cfg_manage_gids) || $uid == $row['add_uid']);
$xoopsTpl->assign('perm', $perm);

$his_perm = false;
if ($uid && checkPerm($gids, $cfg_his_gids)) {
    $his_perm = true;
} elseif (!$uid && $cfg_his_guest) {
    $his_perm = true;
}
$xoopsTpl->assign('his_perm', $his_perm);
if ($his_perm) {
    $xoopsTpl->assign('histories', getHistories($did));
}

require_once XOOPS_ROOT_PATH . '/footer.php';

<?php

require_once '../../../include/cp_header.php';
require_once './include/common.php';

$errors = array();
if (!extension_loaded('mbstring')) {
    $errors[] = getAMConst('_MBSTRING_DISABLE_ERR');
}
if (!extension_loaded('gd')) {
    $errors[] = getAMConst('_GD_DISABLE_ERR');
} else {
    $gd_infos = gd_info();
    if (!checkGDSupport()) {
        $errors[] = getAMConst('_GD_NOT_SUPPORTED_ERR');
    }
}

xoops_cp_header();

$items = array();
$res = $xoopsDB->query("SELECT * FROM $item_tbl ORDER BY `sequence` ASC, `iid` ASC");
while ($row = $xoopsDB->fetchArray($res)) {
    $item = array();
    $item['iid'] = $row['iid'];
    $item['name'] = $myts->htmlSpecialChars($row['name']);
    $item['caption'] = $myts->htmlSpecialChars($row['caption']);
    $item['type'] = $myts->htmlSpecialChars($row['type']);
    $item['type_title'] = $types[$row['type']];
    $item['required'] = $row['required'];
    $item['sequence'] = $row['sequence'];
    $item['search'] = $row['search'];
    $item['list'] = $row['list'];
    $item['add'] = $row['add'];
    $item['update'] = $row['update'];
    $item['detail'] = $row['detail'];
    $item['duplicate'] = $row['duplicate'];
    $items[] = $item;
}
$xoopsTpl->assign('items', $items);

$type_item_def = array('options' => array_flip($types), 'type' => 'select', 'value_type' => 'string');
$item_add_msg = sprintf($admin_consts['_ITEM_ADD_MSG'], makeSelectForm('type', $type_item_def, ''));
$xoopsTpl->assign('item_add_msg', $item_add_msg);
$xoopsTpl->assign('errors', $errors);
//----------------------mb
$xoopsTpl->assign('_ITEM_ADD', getAMConst('_ITEM_ADD'));
$xoopsTpl->assign('_ITEM', getAMConst('_ITEM'));
$xoopsTpl->assign('_LIST', getAMConst('_LIST'));
$xoopsTpl->assign('_DETAIL', getAMConst('_DETAIL'));
$xoopsTpl->assign('_UPDATE', getAMConst('_UPDATE'));
$xoopsTpl->assign('_EDIT', getAMConst('_EDIT'));
$xoopsTpl->assign('_DELETE', getAMConst('_DELETE'));

$xoopsTpl->assign('_NAME', getAMConst('_NAME'));
$xoopsTpl->assign('_CAPTION', getAMConst('_CAPTION'));
$xoopsTpl->assign('_TYPE', getAMConst('_TYPE'));
$xoopsTpl->assign('_REQUIRED', getAMConst('_REQUIRED'));
$xoopsTpl->assign('_SEQUENCE', getAMConst('_SEQUENCE'));
$xoopsTpl->assign('_SEARCH', getAMConst('_SEARCH'));
$xoopsTpl->assign('_ADD', getAMConst('_ADD'));
$xoopsTpl->assign('_DUPLICATE', getAMConst('_DUPLICATE'));
$xoopsTpl->assign('_OPERATION', getAMConst('_OPERATION'));
$xoopsTpl->assign('_YES_MARK', getAMConst('_YES_MARK'));





$xoopsTpl->display(XOOPS_ROOT_PATH . '/modules/' . $dirname . '/templates/admin/xgdb_admin_index.html');

xoops_cp_footer();

?>

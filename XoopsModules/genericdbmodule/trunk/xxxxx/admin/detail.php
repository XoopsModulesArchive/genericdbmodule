<?php

require_once '../../../include/cp_header.php';
require_once './include/common.php';

// 存在チェック
$iid = isset($_GET['iid']) ? intval($_GET['iid']) : 0;
if ($iid < 1) {
    redirect_header($module_url . '/admin/index.php', 5, $admin_consts['_NO_ERR_MSG']);
}
$res = $xoopsDB->query("SELECT * FROM $item_tbl WHERE iid = " . $iid);
if ($xoopsDB->getRowsNum($res) == 0) {
    redirect_header($module_url . '/admin/index.php', 5, $admin_consts['_NO_ERR_MSG']);
}

xoops_cp_header();

$row = $xoopsDB->fetchArray($res);

$item_defs = getAdminItemDefs($row['type']);

foreach ($item_defs as $item_name => $item_def) {
    if ($item_name == 'name' || $item_name == 'caption' || $item_name == 'xgdb_name') {
        $item_defs[$item_name]['value'] = $myts->htmlSpecialChars($row[$item_name]);
    } elseif ($item_name == 'type') {
        $item_defs[$item_name]['value'] = $types[$row[$item_name]];
    } elseif ($item_name == 'required' || $item_name == 'site_search' || $item_name == 'duplicate') {
        $item_defs[$item_name]['value'] = $row[$item_name] ? $admin_consts['_YES'] : $admin_consts['_NO'];
    } elseif ($item_name == 'show_gids') {
        $item_defs[$item_name]['value'] = gidstring2brgroup($row[$item_name]);
    } elseif ($item_name == 'sequence' || $item_name == 'value_range_min' || $item_name == 'value_range_max' || $item_name == 'size' || $item_name == 'max_length' || $item_name == 'rows' || $item_name == 'cols' || $item_name == 'max_file_size' || $item_name == 'max_image_size') {
        $item_defs[$item_name]['value'] = sanitize($row[$item_name], $item_def);
    } elseif ($item_name == 'search' || $item_name == 'list' || $item_name == 'add' || $item_name == 'update' || $item_name == 'detail' || $item_name == 'disp_cond') {
        $item_defs[$item_name]['value'] = $row[$item_name] ? $admin_consts['_DISP'] : $admin_consts['_NOT_DISP'];
    } elseif ($item_name == 'value_type') {
        $item_defs[$item_name]['value'] = $value_types[$row[$item_name]];
    } elseif ($item_name == 'default') {
        if ($row['type'] == 'text' || $row['type'] == 'number' || $row['type'] == 'radio' || $row['type'] == 'select' || $row['type'] == 'tarea' || $row['type'] == 'xtarea') {
            $item_defs[$item_name]['value'] = $myts->htmlSpecialChars($row[$item_name]);
        } elseif ($row['type'] == 'cbox' || $row['type'] == 'mselect') {
            $item_defs[$item_name]['value'] = array2brstring(nl2array($row[$item_name]));
        }
    } elseif ($item_name == 'options') {
        $item_defs[$item_name]['value'] = array2brstring(nl2array($row[$item_name]));
    } elseif ($item_name == 'option_br' || $item_name == 'html' || $item_name == 'smily' || $item_name == 'xcode' || $item_name == 'image' || $item_name == 'br') {
        $item_defs[$item_name]['value'] = $row[$item_name] ? $admin_consts['_ENABLE'] : $admin_consts['_DISABLE'];
    } elseif ($item_name == 'allowed_exts' || $item_name == 'allowed_mimes') {
        $item_defs[$item_name]['value'] = nl2br($myts->htmlSpecialChars($row[$item_name]));
    } elseif ($item_name == 'search_desc' || $item_name == 'input_desc' || $item_name == 'show_desc') {
        $item_defs[$item_name]['value'] = $myts->displayTarea($row[$item_name]);
    } elseif ($item_name == 'search_cond') {
        if ($row['type'] === 'text' || $row['type'] === 'file' || $row['type'] === 'image') {
            $item_defs[$item_name]['value'] = $row[$item_name] ? $admin_consts['_COMP_MATCH'] : $admin_consts['_PART_MATCH'];
        } elseif ($row['type'] === 'cbox' || $row['type'] === 'mselect') {
            $item_defs[$item_name]['value'] = $row[$item_name] ? $admin_consts['_AND_MATCH'] : $admin_consts['_OR_MATCH'];
        }
    }
}

$xoopsTpl->assign('item_defs', $item_defs);
$xoopsTpl->assign('iid', $iid);
//------------------
$xoopsTpl->assign('_EDIT', getAMConst('_EDIT'));
$xoopsTpl->assign('_DELETE', getAMConst('_DELETE'));
$xoopsTpl->assign('_RETURN_LIST', getAMConst('_RETURN_LIST'));


$xoopsTpl->display(XOOPS_ROOT_PATH . '/modules/' . $dirname . '/templates/admin/xgdb_admin_detail.html');

xoops_cp_footer();

?>
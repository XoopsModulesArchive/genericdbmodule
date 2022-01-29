<?php

require_once dirname(__DIR__, 3) . '/include/cp_header.php';
require_once __DIR__ . '/include/common.php';

// Existence check
$iid = isset($_GET['iid']) ? (int)$_GET['iid'] : 0;
if (1 > $iid) {
    redirect_header($module_url . '/admin/index.php', 5, $admin_consts['_NO_ERR_MSG']);
}
$res = $xoopsDB->query("SELECT * FROM $item_tbl WHERE iid = " . $iid);
if (0 == $xoopsDB->getRowsNum($res)) {
    redirect_header($module_url . '/admin/index.php', 5, $admin_consts['_NO_ERR_MSG']);
}

xoops_cp_header();

$row = $xoopsDB->fetchArray($res);

$item_defs = getAdminItemDefs($row['type']);

foreach ($item_defs as $item_name => $item_def) {
    if ('name' === $item_name || 'caption' === $item_name || 'xgdb_name' === $item_name) {
        $item_defs[$item_name]['value'] = htmlspecialchars($row[$item_name], ENT_QUOTES | ENT_HTML5);
    } elseif ('type' === $item_name) {
        $item_defs[$item_name]['value'] = $types[$row[$item_name]];
    } elseif ('required' === $item_name || 'site_search' === $item_name || 'duplicate' === $item_name) {
        $item_defs[$item_name]['value'] = $row[$item_name] ? $admin_consts['_YES'] : $admin_consts['_NO'];
    } elseif ('show_gids' === $item_name) {
        $item_defs[$item_name]['value'] = gidstring2brgroup($row[$item_name]);
    } elseif ('sequence' === $item_name || 'value_range_min' === $item_name || 'value_range_max' === $item_name || 'size' === $item_name || 'max_length' === $item_name || 'rows' === $item_name || 'cols' === $item_name || 'max_file_size' === $item_name || 'max_image_size' === $item_name) {
        $item_defs[$item_name]['value'] = sanitize($row[$item_name], $item_def);
    } elseif ('search' === $item_name || 'list' === $item_name || 'add' === $item_name || 'update' === $item_name || 'detail' === $item_name || 'disp_cond' === $item_name) {
        $item_defs[$item_name]['value'] = $row[$item_name] ? $admin_consts['_DISP'] : $admin_consts['_NOT_DISP'];
    } elseif ('value_type' === $item_name) {
        $item_defs[$item_name]['value'] = $value_types[$row[$item_name]];
    } elseif ('default' === $item_name) {
        if ('text' === $row['type'] || 'number' === $row['type'] || 'radio' === $row['type'] || 'select' === $row['type'] || 'tarea' === $row['type'] || 'xtarea' === $row['type']) {
            $item_defs[$item_name]['value'] = htmlspecialchars($row[$item_name], ENT_QUOTES | ENT_HTML5);
        } elseif ('cbox' === $row['type'] || 'mselect' === $row['type']) {
            $item_defs[$item_name]['value'] = array2brstring(nl2array($row[$item_name]));
        }
    } elseif ('options' === $item_name) {
        $item_defs[$item_name]['value'] = array2brstring(nl2array($row[$item_name]));
    } elseif ('option_br' === $item_name || 'html' === $item_name || 'smily' === $item_name || 'xcode' === $item_name || 'image' === $item_name || 'br' === $item_name) {
        $item_defs[$item_name]['value'] = $row[$item_name] ? $admin_consts['_ENABLE'] : $admin_consts['_DISABLE'];
    } elseif ('allowed_exts' === $item_name || 'allowed_mimes' === $item_name) {
        $item_defs[$item_name]['value'] = nl2br(htmlspecialchars($row[$item_name], ENT_QUOTES | ENT_HTML5));
    } elseif ('search_desc' === $item_name || 'input_desc' === $item_name || 'show_desc' === $item_name) {
        $item_defs[$item_name]['value'] = $myts->displayTarea($row[$item_name]);
    } elseif ('search_cond' === $item_name) {
        if ('text' === $row['type'] || 'file' === $row['type'] || 'image' === $row['type']) {
            $item_defs[$item_name]['value'] = $row[$item_name] ? $admin_consts['_COMP_MATCH'] : $admin_consts['_PART_MATCH'];
        } elseif ('cbox' === $row['type'] || 'mselect' === $row['type']) {
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

$xoopsTpl->display(XOOPS_ROOT_PATH . '/modules/' . $dirname . '/templates/admin/xgdb_admin_detail.tpl');

xoops_cp_footer();

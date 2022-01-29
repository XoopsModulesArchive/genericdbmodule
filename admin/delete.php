<?php

require_once dirname(__DIR__, 3) . '/include/cp_header.php';
require_once __DIR__ . '/include/common.php';

$op  = $_POST['op'] ?? '';
$iid = isset($_POST['iid']) ? (int)$_POST['iid'] : 0;
if ('' == $iid) {
    $iid = isset($_GET['iid']) ? (int)$_GET['iid'] : 0;
}

// Existence check
if (1 > $iid) {
    redirect_header($module_url . '/admin/index.php', 5, $admin_consts['_NO_ERR_MSG']);
}
$res = $xoopsDB->query("SELECT * FROM $item_tbl WHERE iid = " . $iid);
$row = $xoopsDB->fetchArray($res);
if (0 == $xoopsDB->getRowsNum($res)) {
    redirect_header($module_url . '/admin/index.php', 5, $admin_consts['_NO_ERR_MSG']);
}

if ('delete' === $op) {
    $res = $xoopsDB->query("SELECT * FROM $item_tbl");
    if (1 == $xoopsDB->getRowsNum($res)) {
        redirect_header($module_url . '/admin/detail.php?iid=' . $iid, 5, $admin_consts['_LAST_ERR_MSG']);
    } elseif (XoopsMultiTokenHandler::quickValidate($dirname . '_delete')) {
        if ($xoopsDB->query("DELETE FROM $item_tbl WHERE iid = $iid")) {
            if ('file' === $row['type'] || 'image' === $row['type']) {
                $res = $xoopsDB->query('SELECT did, ' . $row['name'] . " FROM $data_tbl");
                while ([$did, $file_name] = $xoopsDB->fetchRow($res)) {
                    @unlink($module_upload_dir . '/' . getRealFileName($did, $row['name'], $file_name));
                }
            }
            if ($xoopsDB->query("ALTER TABLE $data_tbl DROP `" . $row['name'] . '`')) {
                if ($xoopsDB->query("ALTER TABLE $his_tbl DROP `" . $row['name'] . '`')) {
                    redirect_header($module_url . '/admin/index.php', 5, $admin_consts['_DELETE_MSG']);
                } else {
                    redirect_header($module_url . '/admin/index.php', 5, $admin_consts['_SYSTEM_ERR_MS']);
                }
            } else {
                redirect_header($module_url . '/admin/index.php', 5, $admin_consts['_SYSTEM_ERR_MS']);
            }
        } else {
            redirect_header($module_url . '/admin/index.php', 5, $admin_consts['_SYSTEM_ERR_MS']);
        }
    } else {
        redirect_header($module_url . '/admin/detail.php?iid=' . $iid, 5, $admin_consts['_TOKEN_ERR_MSG']);
    }
} else {
    xoops_cp_header();

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

    $token = XoopsMultiTokenHandler::quickCreate($dirname . '_delete');
    $xoopsTpl->assign('token', $token->getHtml());

    $xoopsTpl->display(XOOPS_ROOT_PATH . '/modules/' . $dirname . '/templates/admin/xgdb_admin_delete.tpl');

    xoops_cp_footer();
}

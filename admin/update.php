<?php

require_once dirname(__DIR__, 3) . '/include/cp_header.php';
require_once __DIR__ . '/include/common.php';
require_once dirname(__DIR__) . '/class/token.php';

$op  = $_POST['op'] ?? '';
$iid = isset($_POST['iid']) ? (int)$_POST['iid'] : 0;
if ('' == $iid) {
    $iid = isset($_GET['iid']) ? (int)$_GET['iid'] : 0;
}
if (isset($_POST['cancel'])) {
    header('Location: ' . $module_url . '/admin/detail.php?iid=' . $iid);
}

if (1 > $iid) {
    redirect_header($module_url . '/admin/index.php', 5, getAMConst('_NO_ERR_MSG'));
}
$res = $xoopsDB->query("SELECT * FROM $item_tbl WHERE iid = $iid");
if (0 == $xoopsDB->getRowsNum($res)) {
    redirect_header($module_url . '/admin/index.php', 5, getAMConst('_NO_ERR_MSG'));
}
$row = $xoopsDB->fetchArray($res);

$errors    = [];
$item_defs = getAdminItemDefs($row['type']);

if ('update' === $op) {
    // Token check
    if (!XoopsMultiTokenHandler::quickValidate($dirname . '_update')) {
        $errors[] = getAMConst('_TOKEN_ERR_MSG');
    }

    foreach ($item_defs as $item_name => $item_def) {
        $$item_name = '';
        if ('name' === $item_name || 'type' === $item_name || 'value_type' === $item_name) {
            $$item_name = $row[$item_name];
        } elseif (isset($_POST[$item_name]) && '' !== $_POST[$item_name]) {
            $$item_name = $_POST[$item_name];
            if ('number' === $item_def['type'] && isset($item_def['value_range_min']) && $$item_name < $item_def['value_range_min']) {
                $errors[]                       = sprintf(getAMConst('_RANGE_ERR_MSG'), $item_def['caption'], getRangeText($item_def['value_range_min'], $item_def['value_range_max']));
                $item_defs[$item_name]['error'] = '<br>' . sprintf(getAMConst('_RANGE_ERR_MSG'), $item_def['caption'], getRangeText($item_def['value_range_min'], $item_def['value_range_max']));
            } elseif ('number' === $item_def['type'] && isset($item_def['value_range_max']) && $$item_name > $item_def['value_range_max']) {
                $errors[]                       = sprintf(getAMConst('_RANGE_ERR_MSG'), $item_def['caption'], getRangeText($item_def['value_range_min'], $item_def['value_range_max']));
                $item_defs[$item_name]['error'] = '<br>' . sprintf(getAMConst('_RANGE_ERR_MSG'), $item_def['caption'], getRangeText($item_def['value_range_min'], $item_def['value_range_max']));
            }
        } elseif ($item_def['required']) {
            $errors[]                       = sprintf(getAMConst('_REQ_ERR_MSG'), $item_def['caption']);
            $item_defs[$item_name]['error'] = '<br>' . sprintf(getAMConst('_REQ_ERR_MSG'), $item_def['caption']);
        }
    }

    if (0 == count($errors)) {
        $sql = "UPDATE $item_tbl SET ";
        foreach ($item_defs as $item_name => $item_def) {
            if ('name' === $item_name) {
                continue;
            }
            if ('' === $$item_name) {
                $sql .= '`' . addslashes($item_name) . '` = NULL, ';
            } elseif ('show_gids' === $item_name) {
                $sql .= '`' . addslashes($item_name) . "` = '|" . addslashes(array2string($$item_name)) . "|', ";
            } else {
                $sql .= '`' . addslashes($item_name) . "` = '" . addslashes($$item_name) . "', ";
            }
        }
        $sql = mb_substr($sql, 0, -2);
        $sql .= " WHERE iid = $iid";
        if ($xoopsDB->query($sql)) {
            redirect_header($module_url . '/admin/detail.php?iid=' . $iid, 5, getAMConst('_UPDATE_MSG'));
        }
        $errors[] = getAMConst('_SYSTEM_ERR_MSG');
    }
} else {
    foreach ($item_defs as $item_name => $item_def) {
        $$item_name = $row[$item_name];
    }
}

xoops_cp_header();

foreach ($item_defs as $item_name => $item_def) {
    if ('name' === $item_name) {
        $item_defs[$item_name]['required'] = false;
        $item_defs[$item_name]['value']    = htmlspecialchars($$item_name, ENT_QUOTES | ENT_HTML5);
    } elseif ('type' === $item_name) {
        $item_defs[$item_name]['required'] = false;
        $item_defs[$item_name]['value']    = $types[$$item_name];
    } elseif ('value_type' === $item_name) {
        $item_defs[$item_name]['required'] = false;
        $item_defs[$item_name]['value']    = $value_types[$$item_name];
    } elseif ('text' === $item_def['type'] || 'number' === $item_def['type']) {
        $item_defs[$item_name]['value'] = makeTextForm($item_name, $item_def, $$item_name);
    } elseif ('cbox' === $item_def['type']) {
        $item_defs[$item_name]['value'] = makeCboxForm($item_name, $item_def, $$item_name);
    } elseif ('radio' === $item_def['type']) {
        $item_defs[$item_name]['value'] = makeRadioForm($item_name, $item_def, $$item_name);
    } elseif ('select' === $item_def['type']) {
        $item_defs[$item_name]['value'] = makeSelectForm($item_name, $item_def, $$item_name);
    } elseif ('mselect' === $item_def['type']) {
        $item_defs[$item_name]['value'] = makeMSelectForm($item_name, $item_def, $$item_name);
    } elseif ('tarea' === $item_def['type']) {
        $item_defs[$item_name]['value'] = makeTAreaForm($item_name, $item_def, $$item_name);
    } elseif ('xtarea' === $item_def['type']) {
        $item_defs[$item_name]['value'] = makeXTAreaForm($item_name, $item_def, $$item_name);
    }
}
$xoopsTpl->assign('iid', $iid);
$xoopsTpl->assign('type', htmlspecialchars($row['type'], ENT_QUOTES | ENT_HTML5));
$xoopsTpl->assign('item_defs', $item_defs);
$token = XoopsMultiTokenHandler::quickCreate($dirname . '_update');
$xoopsTpl->assign('token', $token->getHtml());
$xoopsTpl->assign('errors', $errors);
//--------------------------
$xoopsTpl->assign('_UPDATE', getAMConst('_UPDATE'));
$xoopsTpl->assign('_ITEM', getAMConst('_ITEM'));
$xoopsTpl->assign('_CANCEL', getAMConst('_CANCEL'));

$xoopsTpl->display(XOOPS_ROOT_PATH . '/modules/' . $dirname . '/templates/admin/xgdb_admin_update.tpl');

xoops_cp_footer();

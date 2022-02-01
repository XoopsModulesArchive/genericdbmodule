<?php

require_once dirname(__DIR__, 3) . '/include/cp_header.php';
require_once __DIR__ . '/include/common.php';
require_once dirname(__DIR__) . '/class/token.php';

$op   = $_POST['op'] ?? '';
$type = $_POST['type'] ?? '';
if (isset($_POST['cancel'])) {
    header('Location: ' . $module_url . '/admin/');
}

if ('' === $type || !array_key_exists($type, $types)) {
    redirect_header($module_url . '/admin/index.php', 5, $admin_consts['_DATA_TYPE_ERR_MSG']);
}

$errors    = [];
$item_defs = getAdminItemDefs($type);
unset($item_defs['type']);

if ('add' === $op) {
    // Token check
    if (!XoopsMultiTokenHandler::quickValidate($dirname . '_add')) {
        $errors[] = getAMConst('_TOKEN_ERR_MSG');
    }

    foreach ($item_defs as $item_name => $item_def) {
        $$item_name = '';
        if (isset($_POST[$item_name]) && '' !== $_POST[$item_name]) {
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

    if (0 === count($errors)) {
        if (checkColumnName($name)) {
            $res = $xoopsDB->query("SELECT * FROM $item_tbl WHERE name = 'xgdb_" . addslashes($name) . "'");
            if (0 < $xoopsDB->getRowsNum($res)) {
                $errors[]                   = sprintf(getAMConst('_DUPLICATE_ERR_MSG'), $item_defs['name']['caption']);
                $item_defs['name']['error'] = '<br>' . sprintf(getAMConst('_DUPLICATE_ERR_MSG'), $item_defs['name']['caption']);
            }
        } else {
            $errors[]                   = sprintf(getAMConst('_NAME_ERR_MSG'), $item_defs['name']['caption']);
            $item_defs['name']['error'] = '<br>' . sprintf(getAMConst('_NAME_ERR_MSG'), $item_defs['name']['caption']);
        }

        if (0 === count($errors)) {
            $sql = "INSERT INTO $item_tbl (`type`, ";
            foreach ($item_defs as $item_name => $item_def) {
                $sql .= '`' . $item_name . '`, ';
            }
            $sql = mb_substr($sql, 0, -2);
            $sql .= ") VALUES('" . addslashes($type) . "', ";
            foreach ($item_defs as $item_name => $item_def) {
                if ('name' === $item_name) {
                    $sql .= "'xgdb_" . addslashes($$item_name) . "', ";
                } elseif ('' === $$item_name && !$item_def['required']) {
                    $sql .= 'NULL, ';
                } elseif ('show_gids' === $item_name) {
                    $sql .= "'|" . addslashes(array2string($$item_name)) . "|', ";
                } else {
                    $sql .= "'" . addslashes($$item_name) . "', ";
                }
            }
            $sql = mb_substr($sql, 0, -2);
            $sql .= ')';
            if ($xoopsDB->query($sql)) {
                $iid = $xoopsDB->getInsertId();

                $sql = "ALTER TABLE $data_tbl ADD `xgdb_" . addslashes($name) . '` ';
                if ('text' === $type) {
                    $sql .= 'VARCHAR(255)';
                } elseif ('tarea' === $type || 'xtarea' === $type) {
                    $sql .= 'TEXT';
                } elseif ('file' === $type || 'image' === $type) {
                    $sql .= 'VARCHAR(255)';
                } elseif (isset($value_type) && 'string' === $value_type) {
                    $sql .= 'VARCHAR(255)';
                } elseif (isset($value_type) && 'int' === $value_type) {
                    $sql .= 'INT';
                } elseif (isset($value_type) && 'float' === $value_type) {
                    $sql .= 'FLOAT';
                } elseif ('date' === $type) {
                    $sql .= 'DATE';
                }

                if ($xoopsDB->query($sql)) {
                    $sql = str_replace($data_tbl, $his_tbl, $sql);
                    if ($xoopsDB->query($sql)) {
                        redirect_header($module_url . '/admin/detail.php?iid=' . $iid, 5, getAMConst('_ADD_MSG'));
                    }
                }
            }
            $errors[] = getAMConst('_SYSTEM_ERR_MSG');
        }
    }
} else {
    foreach ($item_defs as $item_name => $item_def) {
        if ('show_gids' === $item_name) {
            $$item_name = [1, 2, 3];
        } else {
            $$item_name = '';
        }
    }
}

xoops_cp_header();

foreach ($item_defs as $item_name => $item_def) {
    if ('text' === $item_def['type'] || 'number' === $item_def['type']) {
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
$xoopsTpl->assign('item_defs', $item_defs);
$xoopsTpl->assign('type_title', $types[$type]);
$xoopsTpl->assign('type', htmlspecialchars($type, ENT_QUOTES | ENT_HTML5));
$token = XoopsMultiTokenHandler::quickCreate($dirname . '_add');
$xoopsTpl->assign('token', $token->getHtml());
$xoopsTpl->assign('errors', $errors);
//-------------------mb
$xoopsTpl->assign('_CANCEL', getAMConst('_CANCEL'));
$xoopsTpl->assign('_ADD', getAMConst('_ADD'));

$xoopsTpl->display(XOOPS_ROOT_PATH . '/modules/' . $dirname . '/templates/admin/xgdb_admin_add.tpl');

xoops_cp_footer();

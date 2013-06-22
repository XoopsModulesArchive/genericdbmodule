<?php

require_once '../../../include/cp_header.php';
require_once './include/common.php';
require_once '../class/token.php';

$op = isset($_POST['op']) ? $_POST['op'] : '';
$type = isset($_POST['type']) ? $_POST['type'] : '';
if (isset($_POST['cancel'])) {
    header('Location: ' . $module_url . '/admin/');
}

if ($type == '' || !array_key_exists($type, $types)) {
    redirect_header($module_url . '/admin/index.php', 5, $admin_consts['_DATA_TYPE_ERR_MSG']);
}

$errors = array();
$item_defs = getAdminItemDefs($type);
unset($item_defs['type']);

if ($op == 'add') {
    // トークンチェック
    if (!XoopsMultiTokenHandler::quickValidate($dirname . '_add')) {
        $errors[] = getAMConst('_TOKEN_ERR_MSG');
    }

    foreach ($item_defs as $item_name => $item_def) {
        $$item_name = '';
        if (isset($_POST[$item_name]) && $_POST[$item_name] !== '') {
            $$item_name = $_POST[$item_name];
            if ($item_def['type'] == 'number' && isset($item_def['value_range_min']) && $$item_name < $item_def['value_range_min']) {
                $errors[] = sprintf(getAMConst('_RANGE_ERR_MSG'), $item_def['caption'], getRangeText($item_def['value_range_min'], $item_def['value_range_max']));
                $item_defs[$item_name]['error'] = '<br />' . sprintf(getAMConst('_RANGE_ERR_MSG'), $item_def['caption'], getRangeText($item_def['value_range_min'], $item_def['value_range_max']));
            } elseif ($item_def['type'] == 'number' && isset($item_def['value_range_max']) && $$item_name > $item_def['value_range_max']) {
                $errors[] = sprintf(getAMConst('_RANGE_ERR_MSG'), $item_def['caption'], getRangeText($item_def['value_range_min'], $item_def['value_range_max']));
                $item_defs[$item_name]['error'] = '<br />' . sprintf(getAMConst('_RANGE_ERR_MSG'), $item_def['caption'], getRangeText($item_def['value_range_min'], $item_def['value_range_max']));
            }
        } else {
            if ($item_def['required']) {
                $errors[] = sprintf(getAMConst('_REQ_ERR_MSG'), $item_def['caption']);
                $item_defs[$item_name]['error'] = '<br />' . sprintf(getAMConst('_REQ_ERR_MSG'), $item_def['caption']);
            }
        }
    }

    if (count($errors) == 0) {
        if (!checkColumnName($name)) {
            $errors[] = sprintf(getAMConst('_NAME_ERR_MSG'), $item_defs['name']['caption']);
            $item_defs['name']['error'] = '<br />' . sprintf(getAMConst('_NAME_ERR_MSG'), $item_defs['name']['caption']);
        } else {
            $res = $xoopsDB->query("SELECT * FROM $item_tbl WHERE name = 'xgdb_" . addslashes($name) . "'");
            if ($xoopsDB->getRowsNum($res) > 0) {
                $errors[] = sprintf(getAMConst('_DUPLICATE_ERR_MSG'), $item_defs['name']['caption']);
                $item_defs['name']['error'] = '<br />' . sprintf(getAMConst('_DUPLICATE_ERR_MSG'), $item_defs['name']['caption']);
            }
        }

        if (count($errors) == 0) {
            $sql = "INSERT INTO $item_tbl (`type`, ";
            foreach ($item_defs as $item_name => $item_def) {
                $sql .= '`' . $item_name . '`, ';
            }
            $sql = substr($sql, 0, -2);
            $sql .= ") VALUES('" . addslashes($type) . "', ";
            foreach ($item_defs as $item_name => $item_def) {
                if ($item_name == 'name') {
                    $sql .= "'xgdb_" . addslashes($$item_name) . "', ";
                } else {
                    if ($$item_name === '' && !$item_def['required']) {
                        $sql .= "NULL, ";
                    } elseif ($item_name == 'show_gids') {
                        $sql .= "'|" . addslashes(array2string($$item_name)) . "|', ";
                    } else {
                        $sql .= "'" . addslashes($$item_name) . "', ";
                    }
                }
            }
            $sql = substr($sql, 0, -2);
            $sql .= ')';
            if ($xoopsDB->query($sql)) {
                $iid = $xoopsDB->getInsertId();

                $sql = "ALTER TABLE $data_tbl ADD `xgdb_" . addslashes($name) . "` ";
                if ($type == 'text') {
                    $sql .= "VARCHAR(255)";
                } elseif ($type == 'tarea' || $type == 'xtarea') {
                    $sql .= "TEXT";
                } elseif ($type == 'file' || $type == 'image') {
                    $sql .= "VARCHAR(255)";
                } elseif (isset($value_type) && $value_type == 'string') {
                    $sql .= "VARCHAR(255)";
                } elseif (isset($value_type) && $value_type == 'int') {
                    $sql .= "INT";
                } elseif (isset($value_type) && $value_type == 'float') {
                    $sql .= "FLOAT";
                } elseif ($type == 'date') {
                    $sql .= "DATE";
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
        if ($item_name == 'show_gids') $$item_name = array(1, 2, 3);
        else $$item_name = '';
    }
}

xoops_cp_header();

foreach ($item_defs as $item_name => $item_def) {
    if ($item_def['type'] == 'text' || $item_def['type'] == 'number') {
        $item_defs[$item_name]['value'] = makeTextForm($item_name, $item_def, $$item_name);
    } elseif ($item_def['type'] == 'cbox') {
        $item_defs[$item_name]['value'] = makeCboxForm($item_name, $item_def, $$item_name);
    } elseif ($item_def['type'] == 'radio') {
        $item_defs[$item_name]['value'] = makeRadioForm($item_name, $item_def, $$item_name);
    } elseif ($item_def['type'] == 'select') {
        $item_defs[$item_name]['value'] = makeSelectForm($item_name, $item_def, $$item_name);
    } elseif ($item_def['type'] == 'mselect') {
        $item_defs[$item_name]['value'] = makeMSelectForm($item_name, $item_def, $$item_name);
    } elseif ($item_def['type'] == 'tarea') {
        $item_defs[$item_name]['value'] = makeTAreaForm($item_name, $item_def, $$item_name);
    } elseif ($item_def['type'] == 'xtarea') {
        $item_defs[$item_name]['value'] = makeXTAreaForm($item_name, $item_def, $$item_name);
    }
}
$xoopsTpl->assign('item_defs', $item_defs);
$xoopsTpl->assign('type_title', $types[$type]);
$xoopsTpl->assign('type', $myts->htmlSpecialChars($type));
$token = &XoopsMultiTokenHandler::quickCreate($dirname . '_add');
$xoopsTpl->assign('token', $token->getHtml());
$xoopsTpl->assign('errors', $errors);
//-------------------mb
$xoopsTpl->assign('_CANCEL', getAMConst('_CANCEL'));
$xoopsTpl->assign('_ADD', getAMConst('_ADD'));

$xoopsTpl->display(XOOPS_ROOT_PATH . '/modules/' . $dirname . '/templates/admin/xgdb_admin_add.html');

xoops_cp_footer();

?>
<?php

$dirname = basename(dirname(dirname(__FILE__)));

eval('function ' . $dirname . '_xgdb_new_show($options){
    return xgdb_new_show("' . $dirname . '", $options);
}');

eval('function ' . $dirname . '_xgdb_new_edit($options){
    return xgdb_new_edit("' . $dirname . '", $options);
}');

if (!function_exists('xgdb_new_show')) {
    function xgdb_new_show($dirname, $options) {
        global $xoopsConfig, $xoopsUser;
        $block = array();
        include XOOPS_ROOT_PATH . '/modules/' . $dirname . '/blocks/include/common.php';
        $list_num = empty($options[0]) ? 0 : intval($options[0]);

        $sql = "SELECT d.*, u.uname FROM $data_tbl AS d LEFT OUTER JOIN $users_tbl AS u ON d.add_uid = u.uid ORDER BY d.add_date DESC";
        $res = $xoopsDB->query($sql, $list_num);
        $info = array();
        while ($row = $xoopsDB->fetchArray($res)) {
            foreach ($row as $key => $value) {
                if ($key == 'did' || $key == 'add_uid' || $key == 'uname') {
                    $info[$key] = $myts->htmlSpecialChars($value);
                } elseif ($key == 'add_date') {
                    $info[$key] = date($cfg_date_format . ' ' . $cfg_time_format, strtotime($value));
                } elseif (!isset($item_defs[$key])) {
                    continue;
                } elseif ($item_defs[$key]['type'] == 'text' || $item_defs[$key]['type'] == 'number' || $item_defs[$key]['type'] == 'radio' || $item_defs[$key]['type'] == 'select' || $item_defs[$key]['type'] == 'date') {
                    $info[$key] = sanitize($value, $item_defs[$key]);
                } elseif ($item_defs[$key]['type'] == 'cbox' || $item_defs[$key]['type'] == 'mselect') {
                    $values = string2array($value);
                    $info[$key] = '';
                    foreach ($values as $value) {
                        $info[$key] .= sanitize($value, $item_defs[$key]) . '<br />';
                    }
                } elseif ($item_defs[$key]['type'] == 'tarea' || $item_defs[$key]['type'] == 'xtarea') {
                    $info[$key] = $myts->displayTarea($value, $item_defs[$key]['html'], $item_defs[$key]['smily'], $item_defs[$key]['xcode'], $item_defs[$key]['image'], $item_defs[$key]['br']);
                }
            }
            $block['infos'][] = $info;
        }
        $block['dirname'] = $dirname;
        return $block;
    }
}

if (!function_exists('xgdb_new_edit')) {
    function xgdb_new_edit($dirname, $options) {
        $affix = strtoupper(strlen($dirname) >= 3 ? substr($dirname, 0, 3) : $dirname);
        $list_num = empty($options[0]) ? 0 : intval($options[0]);

        $ret = constant('_MB_' . $affix . '_SHOW_NUM');
        $ret .= ' <input type="text" size="3" ';
        $ret .= 'name="options[0]" value="' . $list_num . '" />';

        return $ret;
    }
}

?>
<?php

$dirname = basename(dirname(__DIR__));

eval('function ' . $dirname . '_xgdb_new_show($options){
    return xgdb_new_show("' . $dirname . '", $options);
}');

eval('function ' . $dirname . '_xgdb_new_edit($options){
    return xgdb_new_edit("' . $dirname . '", $options);
}');

if (!function_exists('xgdb_new_show')) {
    /**
     * @param $dirname
     * @param $options
     * @return array
     */
    function xgdb_new_show($dirname, $options)
    {
        global $xoopsConfig, $xoopsUser;
        $block = [];
        require XOOPS_ROOT_PATH . '/modules/' . $dirname . '/blocks/include/common.php';
        $list_num = empty($options[0]) ? 0 : (int)$options[0];

        $sql  = "SELECT d.*, u.uname FROM $data_tbl AS d LEFT OUTER JOIN $users_tbl AS u ON d.add_uid = u.uid ORDER BY d.add_date DESC";
        $res  = $xoopsDB->query($sql, $list_num);
        $info = [];
        while (false !== ($row = $xoopsDB->fetchArray($res))) {
            foreach ($row as $key => $value) {
                if ('did' === $key || 'add_uid' === $key || 'uname' === $key) {
                    $info[$key] = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5);
                } elseif ('add_date' === $key) {
                    $info[$key] = date($cfg_date_format . ' ' . $cfg_time_format, strtotime($value));
                } elseif (!isset($item_defs[$key])) {
                    continue;
                } elseif ('text' === $item_defs[$key]['type'] || 'number' === $item_defs[$key]['type'] || 'radio' === $item_defs[$key]['type'] || 'select' === $item_defs[$key]['type'] || 'date' === $item_defs[$key]['type']) {
                    $info[$key] = sanitize($value, $item_defs[$key]);
                } elseif ('cbox' === $item_defs[$key]['type'] || 'mselect' === $item_defs[$key]['type']) {
                    $values     = string2array($value);
                    $info[$key] = '';
                    foreach ($values as $value) {
                        $info[$key] .= sanitize($value, $item_defs[$key]) . '<br>';
                    }
                } elseif ('tarea' === $item_defs[$key]['type'] || 'xtarea' === $item_defs[$key]['type']) {
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
    /**
     * @param $dirname
     * @param $options
     * @return string
     */
    function xgdb_new_edit($dirname, $options)
    {
        $affix    = mb_strtoupper(3 <= mb_strlen($dirname) ? mb_substr($dirname, 0, 3) : $dirname);
        $list_num = empty($options[0]) ? 0 : (int)$options[0];

        $ret = constant('_MB_' . $affix . '_SHOW_NUM');
        $ret .= ' <input type="text" size="3" ';
        $ret .= 'name="options[0]" value="' . $list_num . '">';

        return $ret;
    }
}
?>

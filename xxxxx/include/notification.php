<?php

$dirname = basename(dirname(dirname(__FILE__)));

eval('function ' . $dirname . '_xgdb_notify($category, $item_id){
    return xgdb_notify("' . $dirname . '", $category, $item_id);}');

if (!function_exists('xgdb_notify')) {
    function xgdb_notify($dirname, $category, $item_id) {
        $item['name'] = '';
        $item['url'] = '';
        if ($category == 'change') {
            $module_handler = &xoops_gethandler('module');
            $xoopsModule = &$module_handler->getByDirname($dirname);

            $item['name'] = $xoopsModule->getVar('name');
            $item['url'] = XOOPS_URL . '/modules/' . $dirname . '/detail.php?did=' . intval($item_id);
        }
        return $item;
    }
}

?>
<?php

$dirname = basename(dirname(__DIR__));

eval(
    'function ' . $dirname . '_xgdb_notify($category, $item_id){
    return xgdb_notify("' . $dirname . '", $category, $item_id);}'
);

if (!function_exists('xgdb_notify')) {
    /**
     * @param $dirname
     * @param $category
     * @param $item_id
     * @return array
     */
    function xgdb_notify($dirname, $category, $item_id)
    {
        $item['name'] = '';
        $item['url']  = '';
        if ('change' === $category) {
            $moduleHandler = xoops_getHandler('module');
            $xoopsModule   = $moduleHandler->getByDirname($dirname);

            $item['name'] = $xoopsModule->getVar('name');
            $item['url']  = XOOPS_URL . '/modules/' . $dirname . '/detail.php?did=' . (int)$item_id;
        }

        return $item;
    }
}

?>

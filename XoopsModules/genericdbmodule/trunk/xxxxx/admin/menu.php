<?php

$dirname = basename(dirname(dirname(__FILE__)));
$affix = strtoupper(strlen($dirname) >= 3 ? substr($dirname, 0, 3) : $dirname);

$adminmenucount = 0;
$adminmenu[$adminmenucount]['title'] = constant('_MI_' . $affix . '_ITEM_MANAGE_MENU');
$adminmenu[$adminmenucount]['link'] = 'admin/index.php';

$adminmenu[++$adminmenucount]['title'] = constant('_MI_' . $affix . '_MOD_UPDATE_MENU');
$adminmenu[$adminmenucount]['link'] =  '../system/admin.php?fct=modulesadmin&op=update&module=' . $dirname;
$adminmenu[$adminmenucount]['absolute'] = 1;

?>
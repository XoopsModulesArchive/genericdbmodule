<?php

defined('XOOPS_ROOT_PATH') or die('XOOPS root path not defined');

$module_handler = xoops_gethandler('module');
$module = $module_handler->getByDirname(basename(dirname(__DIR__)));
$pathIcon32 = '../../' . $module->getInfo('icons32');
xoops_loadLanguage('modinfo', $module->dirname());

$pathModuleAdmin = XOOPS_ROOT_PATH . '/' . $module->getInfo('dirmoduleadmin') . '/moduleadmin';
if (!file_exists($fileinc = $pathModuleAdmin . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/' . 'main.php')) {
    $fileinc = $pathModuleAdmin . '/language/english/main.php';
}
include_once $fileinc;

$dirname = basename(dirname(__DIR__));
$affix = mb_strtoupper(3 <= mb_strlen($dirname) ? mb_substr($dirname, 0, 3) : $dirname);

$adminmenu = [];
$i = 0;
$adminmenu[$i]['title'] = _AM_MODULEADMIN_HOME;
$adminmenu[$i]['link'] = 'admin/index.php';
$adminmenu[$i]['icon'] = $pathIcon32 . '/home.png';

$adminmenu[++$i]['title'] = constant('_MI_' . $affix . '_ITEM_MANAGE_MENU');
$adminmenu[$i]['link'] = 'admin/main.php';
$adminmenu[$i]['icon'] = $pathIcon32 . '/manage.png';

$adminmenu[++$i]['title'] = _AM_MODULEADMIN_ABOUT;
$adminmenu[$i]['link'] = 'admin/about.php';
$adminmenu[$i]['icon'] = $pathIcon32 . '/about.png';

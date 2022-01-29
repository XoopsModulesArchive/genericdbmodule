<?php

// Read the function definition file
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
require_once __DIR__ . '/functions.php';

// Initialize variables
$dirname           = basename(dirname(__DIR__, 2));
$affix             = mb_strtoupper(3 <= mb_strlen($dirname) ? mb_substr($dirname, 0, 3) : $dirname);
$myts              = MyTextSanitizer::getInstance();
$data_tbl          = $xoopsDB->prefix($dirname . '_xgdb_data');
$his_tbl           = $xoopsDB->prefix($dirname . '_xgdb_his');
$item_tbl          = $xoopsDB->prefix($dirname . '_xgdb_item');
$module_upload_dir = XOOPS_UPLOAD_PATH . '/' . $dirname;
$module_url        = XOOPS_URL . '/modules/' . $dirname;
require_once XOOPS_ROOT_PATH . '/class/template.php';
$original_theme_fromfile       = $xoopsConfig['theme_fromfile'];
$xoopsConfig['theme_fromfile'] = 1;
$xoopsTpl                      = new XoopsTpl();
$xoopsConfig['theme_fromfile'] = $original_theme_fromfile;
$types                         = [
    'text'    => getAMConst('_TYPE_TEXT'),
    'number'  => getAMConst('_TYPE_NUM'),
    'cbox'    => getAMConst('_TYPE_CBOX'),
    'radio'   => getAMConst('_TYPE_RADIO'),
    'select'  => getAMConst('_TYPE_SELECT'),
    'mselect' => getAMConst('_TYPE_MSELECT'),
    'tarea'   => getAMConst('_TYPE_TAREA'),
    'xtarea'  => getAMConst('_TYPE_XTAREA'),
    'file'    => getAMConst('_TYPE_FILE'),
    'image'   => getAMConst('_TYPE_IMAGE'),
    'date'    => getAMConst('_TYPE_DATE'),
];
$value_types                   = [
    'string' => getAMConst('_STRING'),
    'int'    => getAMConst('_INTEGER'),
    'float'  => getAMConst('_FLOAT'),
];

if (function_exists('mb_language')) {
    mb_language(_LANGCODE);
}
if (function_exists('mb_regex_encoding')) {
    mb_regex_encoding(_CHARSET);
}

// Assign constants and variables to templates
if (!isset($admin_consts)) {
    require XOOPS_ROOT_PATH . '/modules/' . $dirname . '/language/' . $xoopsConfig['language'] . '/admin.php';
}
foreach ($admin_consts as $key => $value) {
    $xoopsTpl->assign($key, $value);
}

$configHandler = xoops_getHandler('config');
$moduleHandler = xoops_getHandler('module');
$xoopsModule   = $moduleHandler->getByDirname($dirname);
$modulename    = $xoopsModule->getVar('name');
$xoopsTpl->assign('modulename', htmlspecialchars($modulename, ENT_QUOTES | ENT_HTML5));

// Disable magic quotes for GP variable values
//if (get_magic_quotes_gpc()) {
//    $_POST = array_map('stripSlashesDeep', $_POST);
//    $_GET = array_map('stripSlashesDeep', $_GET);
//}

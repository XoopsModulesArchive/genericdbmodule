<?php

// 関数定義ファイルを読み込み
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
require_once 'functions.php';

// 変数を初期化
$dirname = basename(dirname(dirname(dirname(__FILE__))));
$affix = strtoupper(strlen($dirname) >= 3 ? substr($dirname, 0, 3) : $dirname);
$myts = &MyTextSanitizer::getInstance();
$data_tbl = $xoopsDB->prefix($dirname . '_xgdb_data');
$his_tbl = $xoopsDB->prefix($dirname . '_xgdb_his');
$item_tbl = $xoopsDB->prefix($dirname . '_xgdb_item');
$module_upload_dir = XOOPS_UPLOAD_PATH . '/' . $dirname;
$module_url = XOOPS_URL . '/modules/' . $dirname;
require_once XOOPS_ROOT_PATH . '/class/template.php';
$original_theme_fromfile = $xoopsConfig['theme_fromfile'];
$xoopsConfig['theme_fromfile'] = 1;
$xoopsTpl = new XoopsTpl();
$xoopsConfig['theme_fromfile'] = $original_theme_fromfile;
$types = array(
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
        'date'    => getAMConst('_TYPE_DATE'));
$value_types = array(
        'string' => getAMConst('_STRING'),
        'int'    => getAMConst('_INTEGER'),
        'float'  => getAMConst('_FLOAT'));

if (function_exists('mb_language')) mb_language(_LANGCODE);
if (function_exists('mb_regex_encoding')) mb_regex_encoding(_CHARSET);

// 定数と変数をテンプレートに割り当てる
if (!isset($admin_consts)) include XOOPS_ROOT_PATH . '/modules/' . $dirname . '/language/' . $xoopsConfig['language'] . '/admin.php';
foreach ($admin_consts as $key => $value) {
    $xoopsTpl->assign($key, $value);
}

$config_handler = &xoops_gethandler('config');
$module_handler = &xoops_gethandler('module');
$xoopsModule = $module_handler->getByDirname($dirname);
$modulename = $xoopsModule->getVar('name');
$xoopsTpl->assign('modulename', $myts->htmlSpecialChars($modulename));

// GP変数の値のマジッククォートを無効化する
if (get_magic_quotes_gpc()) {
    $_POST = array_map('stripSlashesDeep', $_POST);
    $_GET = array_map('stripSlashesDeep', $_GET);
}

?>

<?php

// 変数を初期化
$dirname = basename(dirname(dirname(__FILE__)));
$affix = strtoupper(strlen($dirname) >= 3 ? substr($dirname, 0, 3) : $dirname);
$myts = &MyTextSanitizer::getInstance();
$module_url = XOOPS_URL . '/modules/' . $dirname;
$module_upload_url = XOOPS_UPLOAD_URL . '/' . $dirname;
$module_upload_dir = XOOPS_UPLOAD_PATH . '/' . $dirname;
$data_tbl = $xoopsDB->prefix($dirname . '_xgdb_data');
$his_tbl = $xoopsDB->prefix($dirname . '_xgdb_his');
$item_tbl = $xoopsDB->prefix($dirname . '_xgdb_item');
$users_tbl = $xoopsDB->prefix('users');

if (function_exists('mb_language')) mb_language(_LANGCODE);
if (function_exists('mb_regex_encoding')) mb_regex_encoding(_CHARSET);

// モジュールの一般設定値を初期化し、テンプレートに割り当てる
foreach ($xoopsModuleConfig as $cfg_key => $cfg_val) {
    $cfg_key = 'cfg_' . str_replace($dirname . '_', '', $cfg_key);
    $$cfg_key = $cfg_val;
    $xoopsTpl->assign($cfg_key, $cfg_val);
}

// 定数をテンプレートに割り当てる
if (!isset($main_consts)) include XOOPS_ROOT_PATH . '/modules/' . $dirname . '/language/' . $xoopsConfig['language'] . '/main.php';
foreach ($main_consts as $key => $value) {
    $xoopsTpl->assign($key, $value);
}

// ユーザ情報を初期化
if (is_object($xoopsUser)) {
    $uid = $xoopsUser->getVar('uid');
    $gids = $xoopsUser->getGroups();
} else {
    $uid = 0;
    $gids = array(3);
}

// 関数定義ファイルを読み込み
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
require_once XOOPS_ROOT_PATH . '/modules/' . $dirname . '/include/functions.php';

// テンプレートの自動更新
if ($cfg_auto_update) {
    $template_dir_path = XOOPS_ROOT_PATH . '/modules/' . $dirname . '/templates';
    if ($handler = @opendir($template_dir_path . '/')) {
        while (($file = readdir($handler)) !== false) {
            $file_path = $template_dir_path . '/' . $file;
            if (is_file($file_path) && substr($file, -5) == '.html' && $file != 'index.html') {
                $mtime = intval(@filemtime($file_path));
                $file = $dirname . '_' . $file;
                list($count) = $xoopsDB->fetchRow($xoopsDB->query("SELECT COUNT(*) FROM " . $xoopsDB->prefix("tplfile") . " WHERE tpl_tplset = '" . addslashes($xoopsConfig['template_set']) . "' AND tpl_file = '" . addslashes($file) . "' AND tpl_lastmodified >= $mtime"));
                if ($count == 0) {
                    updateTemplate($xoopsConfig['template_set'], $file, implode('', file($file_path)), $mtime);
                }
            }
        }
    }

    if ($handler = @opendir($template_dir_path . '/blocks/')) {
        while (($file = readdir($handler)) !== false) {
            $file_path = $template_dir_path . '/blocks/' . $file;
            if (is_file($file_path) && substr($file, -5) == '.html' && $file != 'index.html') {
                $mtime = intval(@filemtime($file_path));
                $file = $dirname . '_' . $file;
                list($count) = $xoopsDB->fetchRow($xoopsDB->query("SELECT COUNT(*) FROM " . $xoopsDB->prefix("tplfile") . " WHERE tpl_tplset = '" . addslashes($xoopsConfig['template_set']) . "' AND tpl_file = '" . addslashes($file) . "' AND tpl_lastmodified >= $mtime"));
                if ($count == 0) {
                    updateTemplate($xoopsConfig['template_set'], $file, implode('', file($file_path)), $mtime);
                }
            }
        }
    }
}

// 項目定義情報を初期化する
$item_defs = getItemDefs($dirname, $gids);

// GP変数の値のマジッククォートを無効化する
if (get_magic_quotes_gpc()) {
    $_POST = array_map('stripSlashesDeep', $_POST);
    $_GET = array_map('stripSlashesDeep', $_GET);
}

// xoops_module_headerタグにスタイルシートファイルとJavaScriptファイルを指定
$xoops_module_header = '<link rel="stylesheet" type="text/css" media="screen" href="' . XOOPS_URL . '/modules/' . $dirname . '/js/lightbox/css/jquery.lightbox-0.5.css" />' . "\n";
if (!$cfg_loaded_jq) $xoops_module_header .= '<script type="text/javascript" src="' . XOOPS_URL . '/modules/' . $dirname . '/js/jquery.js"></script>' . "\n";
$xoops_module_header .= '<script type="text/javascript" src="' . XOOPS_URL . '/modules/' . $dirname . '/js/lightbox/js/jquery.lightbox-0.5.js"></script>' . "\n";
$xoops_module_header .= '<script type="text/javascript" src="' . XOOPS_URL . '/modules/' . $dirname . '/js/xgdb.js"></script>' . "\n";

$xoopsTpl->assign('xoops_module_header', $xoops_module_header);

?>

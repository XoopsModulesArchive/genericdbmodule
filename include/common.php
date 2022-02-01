<?php

// Initialize variables
$dirname           = basename(dirname(__DIR__));
$affix             = mb_strtoupper(3 <= mb_strlen($dirname) ? mb_substr($dirname, 0, 3) : $dirname);
$myts              = MyTextSanitizer::getInstance();
$module_url        = XOOPS_URL . '/modules/' . $dirname;
$module_upload_url = XOOPS_UPLOAD_URL . '/' . $dirname;
$module_upload_dir = XOOPS_UPLOAD_PATH . '/' . $dirname;
$data_tbl          = $xoopsDB->prefix($dirname . '_xgdb_data');
$his_tbl           = $xoopsDB->prefix($dirname . '_xgdb_his');
$item_tbl          = $xoopsDB->prefix($dirname . '_xgdb_item');
$users_tbl         = $xoopsDB->prefix('users');

if (function_exists('mb_language')) {
    mb_language(_LANGCODE);
}
if (function_exists('mb_regex_encoding')) {
    mb_regex_encoding(_CHARSET);
}

// Initialize the general settings of the module and assign it to the template
foreach ($xoopsModuleConfig as $cfg_key => $cfg_val) {
    $cfg_key  = 'cfg_' . str_replace($dirname . '_', '', $cfg_key);
    $$cfg_key = $cfg_val;
    $xoopsTpl->assign($cfg_key, $cfg_val);
}

// Assign constants to templates
if (!isset($main_consts)) {
    require XOOPS_ROOT_PATH . '/modules/' . $dirname . '/language/' . $xoopsConfig['language'] . '/main.php';
}
foreach ($main_consts as $key => $value) {
    $xoopsTpl->assign($key, $value);
}

// Initialize user information
if (is_object($xoopsUser)) {
    $uid  = $xoopsUser->getVar('uid');
    $gids = $xoopsUser->getGroups();
} else {
    $uid  = 0;
    $gids = [3];
}

// Read the function definition file
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
require_once XOOPS_ROOT_PATH . '/modules/' . $dirname . '/include/functions.php';

// Automatic template update
if ($cfg_auto_update) {
    $template_dir_path = XOOPS_ROOT_PATH . '/modules/' . $dirname . '/templates';
    if ($handler = @opendir($template_dir_path . '/')) {
        while (false !== ($file = readdir($handler))) {
            $file_path = $template_dir_path . '/' . $file;
            if (is_file($file_path) && '.tpl' === mb_substr($file, -4)) {
                $mtime = (int)@filemtime($file_path);
                $file  = $dirname . '_' . $file;
                [$count] = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('tplfile') . " WHERE tpl_tplset = '" . addslashes($xoopsConfig['template_set']) . "' AND tpl_file = '" . addslashes($file) . "' AND tpl_lastmodified >= $mtime"));
                if (0 === $count) {
                    updateTemplate($xoopsConfig['template_set'], $file, file_get_contents($file_path), $mtime);
                }
            }
        }
    }

    if ($handler = @opendir($template_dir_path . '/blocks/')) {
        while (false !== ($file = readdir($handler))) {
            $file_path = $template_dir_path . '/blocks/' . $file;
            if (is_file($file_path) && '.tpl' === mb_substr($file, -4)) {
                $mtime = (int)@filemtime($file_path);
                $file  = $dirname . '_' . $file;
                [$count] = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('tplfile') . " WHERE tpl_tplset = '" . addslashes($xoopsConfig['template_set']) . "' AND tpl_file = '" . addslashes($file) . "' AND tpl_lastmodified >= $mtime"));
                if (0 === $count) {
                    updateTemplate($xoopsConfig['template_set'], $file, file_get_contents($file_path), $mtime);
                }
            }
        }
    }
}

// Initialize item definition information
$item_defs = getItemDefs($dirname, $gids);

// Disable magic quotes for GP variable values
//if (get_magic_quotes_gpc()) {
//    $_POST = array_map('stripSlashesDeep', $_POST);
//    $_GET = array_map('stripSlashesDeep', $_GET);
//}

// xoops_module_header Specify style sheet file and JavaScript file for tag
$xoops_module_header = '<link rel="stylesheet" type="text/css" media="screen" href="' . XOOPS_URL . '/modules/' . $dirname . '/js/lightbox/css/jquery.lightbox-0.5.css">' . "\n";
if (!$cfg_loaded_jq) {
    $xoops_module_header .= '<script type="text/javascript" src="' . XOOPS_URL . '/modules/' . $dirname . '/js/jquery.js"></script>' . "\n";
}
$xoops_module_header .= '<script type="text/javascript" src="' . XOOPS_URL . '/modules/' . $dirname . '/js/lightbox/js/jquery.lightbox-0.5.js"></script>' . "\n";
$xoops_module_header .= '<script type="text/javascript" src="' . XOOPS_URL . '/modules/' . $dirname . '/js/xgdb.js"></script>' . "\n";

$xoopsTpl->assign('xoops_module_header', $xoops_module_header);

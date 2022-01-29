<?php

// Initialize variables
$affix             = mb_strtoupper(3 <= mb_strlen($dirname) ? mb_substr($dirname, 0, 3) : $dirname);
$xoopsDB           = XoopsDatabaseFactory::getDatabaseConnection();
$myts              = MyTextSanitizer::getInstance();
$module_url        = XOOPS_URL . '/modules/' . $dirname . '/';
$module_upload_url = XOOPS_UPLOAD_URL . '/' . $dirname;
$data_tbl          = $xoopsDB->prefix($dirname . '_xgdb_data');
$item_tbl          = $xoopsDB->prefix($dirname . '_xgdb_item');
$users_tbl         = $xoopsDB->prefix('users');
$modules_tbl       = $xoopsDB->prefix('modules');
$config_tbl        = $xoopsDB->prefix('config');

if (function_exists('mb_language')) {
    mb_language(_LANGCODE);
}
if (function_exists('mb_regex_encoding')) {
    mb_regex_encoding(_CHARSET);
}

// Initialize the general settings of the module
$sql = "SELECT conf_name, conf_value FROM $config_tbl c, $modules_tbl m WHERE c.conf_modid=m.mid AND m.dirname='$dirname'";
$res = $xoopsDB->query($sql);
while ([$conf_name, $conf_value] = $xoopsDB->fetchRow($res)) {
    $conf_name  = 'cfg_' . str_replace($dirname . '_', '', $conf_name);
    $$conf_name = $conf_value;
}

// Assign constants to templates
if (!isset($block_consts)) {
    require XOOPS_ROOT_PATH . '/modules/' . $dirname . '/language/' . $xoopsConfig['language'] . '/blocks.php';
}
foreach ($block_consts as $key => $value) {
    $block['langs'][$key] = $value;
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
require_once XOOPS_ROOT_PATH . '/modules/' . $dirname . '/include/functions.php';

// Automatic template update
if ($cfg_auto_update) {
    $template_dir_path = XOOPS_ROOT_PATH . '/modules/' . $dirname . '/templates';
    if ($handler = @opendir($template_dir_path . '/blocks/')) {
        while (false !== ($file = readdir($handler))) {
            $file_path = $template_dir_path . '/blocks/' . $file;
            if (is_file($file_path) && '.html' === mb_substr($file, -5) && 'index.html' !== $file) {
                $mtime = (int)@filemtime($file_path);
                $file  = $dirname . '_' . $file;
                [$count] = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('tplfile') . " WHERE tpl_tplset = '" . addslashes($xoopsConfig['template_set']) . "' AND tpl_file = '" . addslashes($file) . "' AND tpl_lastmodified >= $mtime"));
                if (0 == $count) {
                    updateTemplate($xoopsConfig['template_set'], $file, file_get_contents($file_path), $mtime);
                }
            }
        }
    }
}

// Initialize item definition information
$item_defs          = getItemDefs($dirname, $gids);
$block['item_defs'] = $item_defs;

// Disable magic quotes for GP variable values
//if (get_magic_quotes_gpc()) {
//    $_POST = array_map('stripSlashesDeep', $_POST);
//    $_GET = array_map('stripSlashesDeep', $_GET);
//}

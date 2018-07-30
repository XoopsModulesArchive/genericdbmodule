<?php

// �ѿ�����
$affix = strtoupper(strlen($dirname) >= 3 ? substr($dirname, 0, 3) : $dirname);
$xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();
$myts = MyTextSanitizer::getInstance();
$module_url = XOOPS_URL . '/modules/' . $dirname . '/';
$module_upload_url = XOOPS_UPLOAD_URL . '/' . $dirname;
$data_tbl = $xoopsDB->prefix($dirname . '_xgdb_data');
$item_tbl = $xoopsDB->prefix($dirname . '_xgdb_item');
$users_tbl = $xoopsDB->prefix('users');
$modules_tbl = $xoopsDB->prefix('modules');
$config_tbl = $xoopsDB->prefix('config');

if (function_exists('mb_language')) {
    mb_language(_LANGCODE);
}
if (function_exists('mb_regex_encoding')) {
    mb_regex_encoding(_CHARSET);
}

// �⥸�塼��ΰ��������ͤ����
$sql = "SELECT conf_name, conf_value FROM $config_tbl c, $modules_tbl m WHERE c.conf_modid=m.mid AND m.dirname='$dirname'";
$res = $xoopsDB->query($sql);
while (list($conf_name, $conf_value) = $xoopsDB->fetchRow($res)) {
    $conf_name = 'cfg_' . str_replace($dirname . '_', '', $conf_name);
    $$conf_name = $conf_value;
}

// �����ƥ�ץ졼�Ȥ˳�����Ƥ�
if (!isset($block_consts)) {
    include XOOPS_ROOT_PATH . '/modules/' . $dirname . '/language/' . $xoopsConfig['language'] . '/blocks.php';
}
foreach ($block_consts as $key => $value) {
    $block['langs'][$key] = $value;
}

// �桼���������
if (is_object($xoopsUser)) {
    $uid = $xoopsUser->getVar('uid');
    $gids = $xoopsUser->getGroups();
} else {
    $uid = 0;
    $gids = array(3);
}

// �ؿ�����ե�������ɤ߹���
require_once XOOPS_ROOT_PATH . '/modules/' . $dirname . '/include/functions.php';

// �ƥ�ץ졼�Ȥμ�ư����
if ($cfg_auto_update) {
    $template_dir_path = XOOPS_ROOT_PATH . '/modules/' . $dirname . '/templates';
    if ($handler = @opendir($template_dir_path . '/blocks/')) {
        while (($file = readdir($handler)) !== false) {
            $file_path = $template_dir_path . '/blocks/' . $file;
            if (is_file($file_path) && substr($file, -5) == '.html' && $file != 'index.html') {
                $mtime = intval(@filemtime($file_path));
                $file = $dirname . '_' . $file;
                list($count) = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('tplfile') . " WHERE tpl_tplset = '" . addslashes($xoopsConfig['template_set']) . "' AND tpl_file = '" . addslashes($file) . "' AND tpl_lastmodified >= $mtime"));
                if ($count == 0) {
                    updateTemplate($xoopsConfig['template_set'], $file, implode('', file($file_path)), $mtime);
                }
            }
        }
    }
}

// ���������������
$item_defs = getItemDefs($dirname, $gids);
$block['item_defs'] = $item_defs;

// GP�ѿ����ͤΥޥ��å��������Ȥ�̵����
if (get_magic_quotes_gpc()) {
    $_POST = array_map('stripSlashesDeep', $_POST);
    $_GET = array_map('stripSlashesDeep', $_GET);
}

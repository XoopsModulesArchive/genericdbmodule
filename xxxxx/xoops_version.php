<?php

$dirname = basename(dirname(__FILE__));
$affix = strtoupper(strlen($dirname) >= 3 ? substr($dirname, 0, 3) : $dirname);

// Basic informations
$modversion['name'] = constant('_MI_' . $affix . '_MODULE_NAME');
$modversion['version'] = 0.5;
$modversion['description'] = constant('_MI_' . $affix . '_MODULE_DESC');
$modversion['credits'] = 't.kishimoto';
$modversion['author'] = 't.kishimoto';
$modversion['official'] = 0;
$modversion['image'] = 'images/logo.png';
$modversion['dirname'] = $dirname;
$modversion['help'] = '';
$modversion['license'] = 'GPL';

// Admin settings
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';
$modversion['system_menu'] = 1;

// Menus
$modversion['hasMain'] = 1;

global $xoopsUser;
if (!isset($module_handler)) $module_handler = &xoops_gethandler('module');
$xgdbModule = &$module_handler->getByDirname($dirname);
$menucount = 0;
if ($xgdbModule != false) {
    if (!isset($config_handler)) $config_handler = &xoops_gethandler('config');
    $xgdbModuleConfig = &$config_handler->getConfigsByCat(0, $xgdbModule->getVar('mid'));
    if (count($xgdbModuleConfig) > 0) {
        if (is_object($xoopsUser)) {
            $gids = $xoopsUser->getGroups();
            foreach ($gids as $gid) {
                if (in_array($gid, $xgdbModuleConfig[$dirname . '_add_gids'])) {
                    $modversion['sub'][++$menucount]['name'] = constant('_MI_' . $affix . '_MENU_ADD');
                    $modversion['sub'][$menucount]['url'] = 'add.php';
                    break;
                }
            }
            $gids = $xoopsUser->getGroups();
            foreach ($gids as $gid) {
                if (in_array($gid, $xgdbModuleConfig[$dirname . '_his_gids'])) {
                    $modversion['sub'][++$menucount]['name'] = constant('_MI_' . $affix . '_MENU_HIS');
                    $modversion['sub'][$menucount]['url'] = 'his_search.php';
                    break;
                }
            }
        } else {
            if ($xgdbModuleConfig[$dirname . '_add_guest'] || in_array(3, $xgdbModuleConfig[$dirname . '_add_gids'])) {
                $modversion['sub'][++$menucount]['name'] = constant('_MI_' . $affix . '_MENU_ADD');
                $modversion['sub'][$menucount]['url'] = 'add.php';
            }
            if ($xgdbModuleConfig[$dirname . '_his_guest'] || in_array(3, $xgdbModuleConfig[$dirname . '_his_gids'])) {
                $modversion['sub'][++$menucount]['name'] = constant('_MI_' . $affix . '_MENU_HIS');
                $modversion['sub'][$menucount]['url'] = 'his_search.php';
            }
        }
    }
}

// Tables

// Templates

// General settings
$modversion['config'][] = array('name' => $dirname . '_loaded_jq', 'title' => '_MI_' . $affix . '_LOADED_JQ', 'description' => '', 'formtype' => 'yesno', 'valuetype' => 'int', 'default' => '0');
$modversion['config'][] = array('name' => $dirname . '_id_caption', 'title' => '_MI_' . $affix . '_ID_CAPTION', 'description' => '', 'formtype' => 'textbox', 'valuetype' => 'text', 'default' => 'ID');
$modversion['config'][] = array('name' => $dirname . '_result_num', 'title' => '_MI_' . $affix . '_SEARCH_NUM', 'description' => '', 'formtype' => 'textbox', 'valuetype' => 'int', 'default' => '10');
$modversion['config'][] = array('name' => $dirname . '_date_format', 'title' => '_MI_' . $affix . '_DATE_FORMAT', 'description' => '_MI_' . $affix . '_DATE_FORMAT_DESC', 'formtype' => 'textbox', 'valuetype' => 'text', 'default' => 'Y-m-d');
$modversion['config'][] = array('name' => $dirname . '_time_format', 'title' => '_MI_' . $affix . '_TIME_FORMAT', 'description' => '_MI_' . $affix . '_TIME_FORMAT_DESC', 'formtype' => 'textbox', 'valuetype' => 'text', 'default' => 'H:i');
$modversion['config'][] = array('name' => $dirname . '_manage_gids', 'title' => '_MI_' . $affix . '_MANAGE_GROUPS', 'description' => '_MI_' . $affix . '_MANAGE_GROUPS_DESC', 'formtype' => 'group_multi', 'valuetype' => 'array', 'default' => array('1'));
$modversion['config'][] = array('name' => $dirname . '_add_gids', 'title' => '_MI_' . $affix . '_ADD_GROUPS', 'description' => '', 'formtype' => 'group_multi', 'valuetype' => 'array', 'default' => array('1'));
$modversion['config'][] = array('name' => $dirname . '_add_guest', 'title' => '_MI_' . $affix . '_ADD_GUEST', 'description' => '', 'formtype' => 'yesno', 'valuetype' => 'int', 'default' => '0');
$modversion['config'][] = array('name' => $dirname . '_his_gids', 'title' => '_MI_' . $affix . '_HIS_GROUPS', 'description' => '', 'formtype' => 'group_multi', 'valuetype' => 'array', 'default' => array('1'));
$modversion['config'][] = array('name' => $dirname . '_his_guest', 'title' => '_MI_' . $affix . '_HIS_GUEST', 'description' => '', 'formtype' => 'yesno', 'valuetype' => 'int', 'default' => '0');
$modversion['config'][] = array('name' => $dirname . '_auto_update', 'title' => '_MI_' . $affix . '_AUTO_UPDATE', 'description' => '_MI_' . $affix . '_AUTO_UPDATE_DESC', 'formtype' => 'yesno', 'valuetype' => 'int', 'default' => '0');
$modversion['config'][] = array('name' => $dirname . '_main_img_wd', 'title' => '_MI_' . $affix . '_DETAIL_IMAGE_WIDTH', 'description' => '', 'formtype' => 'textbox', 'valuetype' => 'int', 'default' => '300');
$modversion['config'][] = array('name' => $dirname . '_list_img_wd', 'title' => '_MI_' . $affix . '_LIST_IMAGE_WIDTH', 'description' => '', 'formtype' => 'textbox', 'valuetype' => 'int', 'default' => '50');

// Blocks
$blockcount = 0;
$modversion['blocks'][++$blockcount]['file'] = 'blocks.php';
$modversion['blocks'][$blockcount]['name'] = constant('_MI_' . $affix . '_NEW_BLOCK');
$modversion['blocks'][$blockcount]['description'] = '';
$modversion['blocks'][$blockcount]['show_func'] = $dirname . '_xgdb_new_show';
$modversion['blocks'][$blockcount]['edit_func'] = $dirname . '_xgdb_new_edit';
$modversion['blocks'][$blockcount]['options'] = '5';
$modversion['blocks'][$blockcount]['template'] = $dirname . '_xgdb_new_block.html';

// Comments
$modversion['hasComments'] = 1;
$modversion['comments']['pageName'] = 'detail.php';
$modversion['comments']['itemName'] = 'did';

// Search
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = 'include/search.php';
$modversion['search']['func'] = $dirname . '_xgdb_search';

// Notifications
$notcatcount = 0;
$modversion['hasNotification'] = 1;
$modversion['notification']['lookup_file'] = 'include/notification.php';
$modversion['notification']['lookup_func'] = $dirname . '_xgdb_notify';

$modversion['notification']['category'][++$notcatcount]['name'] = 'global';
$modversion['notification']['category'][$notcatcount]['title'] = constant('_MI_' . $affix . '_NTF_GLOBAL');
$modversion['notification']['category'][$notcatcount]['description'] = constant('_MI_' . $affix . '_NTF_GLOBAL_DESC');
$modversion['notification']['category'][$notcatcount]['subscribe_from'] = array('index.php');

$modversion['notification']['category'][++$notcatcount]['name'] = 'change';
$modversion['notification']['category'][$notcatcount]['title'] = constant('_MI_' . $affix . '_NTF_CHANGE');
$modversion['notification']['category'][$notcatcount]['description'] = constant('_MI_' . $affix . '_NTF_CHANGE_DESC');
$modversion['notification']['category'][$notcatcount]['subscribe_from'] = array('detail.php');
$modversion['notification']['category'][$notcatcount]['item_name'] = 'did';

$noteventcount = 0;
$modversion['notification']['event'][++$noteventcount]['name'] = 'add';
$modversion['notification']['event'][$noteventcount]['title'] = constant('_MI_' . $affix . '_NTF_ADD_TITLE');
$modversion['notification']['event'][$noteventcount]['category'] = 'global';
$modversion['notification']['event'][$noteventcount]['description'] = constant('_MI_' . $affix . '_NTF_ADD_DESC');
$modversion['notification']['event'][$noteventcount]['caption'] = constant('_MI_' . $affix . '_NTF_ADD_CAPTION');
$modversion['notification']['event'][$noteventcount]['mail_template'] = 'notify_add';
$modversion['notification']['event'][$noteventcount]['mail_subject'] = constant('_MI_' . $affix . '_NTF_ADD_SUBJECT');

$modversion['notification']['event'][++$noteventcount]['name'] = 'update';
$modversion['notification']['event'][$noteventcount]['title'] = constant('_MI_' . $affix . '_NTF_UPDATE_TITLE');
$modversion['notification']['event'][$noteventcount]['category'] = 'change';
$modversion['notification']['event'][$noteventcount]['description'] = constant('_MI_' . $affix . '_NTF_UPDATE_DESC');
$modversion['notification']['event'][$noteventcount]['caption'] = constant('_MI_' . $affix . '_NTF_UPDATE_CAPTION');
$modversion['notification']['event'][$noteventcount]['mail_template'] = 'notify_update';
$modversion['notification']['event'][$noteventcount]['mail_subject'] = constant('_MI_' . $affix . '_NTF_UPDATE_SUBJECT');

$modversion['notification']['event'][++$noteventcount]['name'] = 'delete';
$modversion['notification']['event'][$noteventcount]['title'] = constant('_MI_' . $affix . '_NTF_DELETE_TITLE');
$modversion['notification']['event'][$noteventcount]['category'] = 'change';
$modversion['notification']['event'][$noteventcount]['description'] = constant('_MI_' . $affix . '_NTF_DELETE_DESC');
$modversion['notification']['event'][$noteventcount]['caption'] = constant('_MI_' . $affix . '_NTF_DELETE_CAPTION');
$modversion['notification']['event'][$noteventcount]['mail_template'] = 'notify_delete';
$modversion['notification']['event'][$noteventcount]['mail_subject'] = constant('_MI_' . $affix . '_NTF_DELETE_SUBJECT');

// Events
$modversion['onInstall'] = 'include/oninstall.php';
$modversion['onUpdate'] = 'include/onupdate.php';
$modversion['onUninstall'] = 'include/onuninstall.php';

?>

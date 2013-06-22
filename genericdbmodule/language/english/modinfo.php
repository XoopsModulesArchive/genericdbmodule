<?php

$dirname = basename(dirname(dirname(dirname(__FILE__))));
$lang_dirname = basename(dirname(__FILE__));
$affix = strtoupper(strlen($dirname) >= 3 ? substr($dirname, 0, 3) : $dirname);

include_once XOOPS_ROOT_PATH . "/modules/$dirname/language/$lang_dirname/common.php";

$modinfo_consts = array(
        '_MODULE_NAME'          => 'XOOPS Generic Database',
        '_MODULE_DESC'          => 'This is generic database module which can define items.',
        '_LOADED_JQ'            => 'Already load jQuery library',
        '_ID_CAPTION'           => 'ID\'s caption',
        '_TEMP_INDEX'           => 'Search List Page',
        '_TEMP_ADD'             => 'Add Page',
        '_TEMP_UPDATE'          => 'Update Page',
        '_TEMP_DELETE'          => 'Delete Page',
        '_TEMP_DETAIL'          => 'Detail Page',
        '_MENU_ADD'             => 'Add',
        '_MENU_HIS'             => 'Update History',
        '_ITEM_MANAGE_MENU'     => 'Manage Items',
        '_MOD_UPDATE_MENU'      => 'Module Update',
        '_SEARCH_NUM'           => 'Numbers Of Search Results',
        '_DATE_FORMAT'          => 'Date Format',
        '_DATE_FORMAT_DESC'     => 'Date Format as date() function\'s 1st parameter format',
        '_TIME_FORMAT'          => 'Time Format',
        '_TIME_FORMAT_DESC'     => 'Time Format as date() function\'s 1st parameter format',
        '_MANAGE_GROUPS'        => 'Data Manager Group',
        '_MANAGE_GROUPS_DESC'   => 'The users which belongs to data manage groups can manage all data.',
        '_ADD_GROUPS'           => 'The group(s) which can add data',
        '_ADD_GUEST'            => 'Allow guest to add data',
        '_HIS_GROUPS'           => 'The group(s) which can show update histories',
        '_HIS_GUEST'            => 'Allow guest to show update histories',
        '_AUTO_UPDATE'          => 'Enable auto update template files',
        '_AUTO_UPDATE_DESC'     => 'If you enable this function, the response speed maybe slows down.<br />If you update template files frequency, you would enable this function.',
        '_DETAIL_IMAGE_WIDTH'   => 'Maximum image width(px) in detail page',
        '_LIST_IMAGE_WIDTH'     => 'Maximum image width(px) in list page',
        '_NEW_BLOCK'            => 'Latest Block',
        '_NTF_GLOBAL'           => 'Add New',
        '_NTF_GLOBAL_DESC'      => 'Event Notification about Adding',
        '_NTF_CHANGE'           => 'Event Notification about individual data',
        '_NTF_CHANGE_DESC'      => 'Event Notification about Updating and Deleting',
        '_NTF_ADD_TITLE'        => 'Add Event Notification',
        '_NTF_ADD_DESC'         => 'Event Notification which occurs when new data is added',
        '_NTF_ADD_CAPTION'      => 'It is notified when new data is added.',
        '_NTF_ADD_SUBJECT'      => 'New data is added',
        '_NTF_UPDATE_TITLE'     => 'Update vent Notification',
        '_NTF_UPDATE_DESC'      => 'Event Notification which occurs when new data is updated',
        '_NTF_UPDATE_CAPTION'   => 'It is notified when a data is updated.',
        '_NTF_UPDATE_SUBJECT'   => 'The data is updated',
        '_NTF_DELETE_TITLE'     => 'Delete Event Notification',
        '_NTF_DELETE_DESC'      => 'Event Notification which occurs when new data is deleted',
        '_NTF_DELETE_CAPTION'   => 'It is notified when a data is deleted.',
        '_NTF_DELETE_SUBJECT'   => 'The data is deleted',
        '_MBSTRING_DISABLE_ERR' => 'The mbstring module could not be used. Please install mbstring module.',
        '_GD_DISABLE_ERR'       => 'The gd module could not be used. Please install gd module.',
        '_GD_NOT_SUPPORTED_ERR' => 'The dg module does not support GIF/JPEG/PNG image files.');

foreach ($modinfo_consts as $key => $value) {
    if (!defined('_MI_' . $affix . $key)) {
        define('_MI_' . $affix . $key, $value);
    }
}

?>
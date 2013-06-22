<?php

$dirname = basename(dirname(dirname(dirname(__FILE__))));
$lang_dirname = basename(dirname(__FILE__));
$affix = strtoupper(strlen($dirname) >= 3 ? substr($dirname, 0, 3) : $dirname);

include_once XOOPS_ROOT_PATH . "/modules/$dirname/language/$lang_dirname/common.php";

$main_consts = array(
        '_PAGENAVI_INFO'      => 'All %s, From %s To %s',
        '_REQ_MARK'           => '<font color="red">(Req)</font>',
        '_SEARCH'             => 'Search',
        '_SEARCH_RESULT'      => 'Search Result',
        '_ADD_DATE'           => 'Add Date',
        '_UNAME'              => 'Add User Name',
        '_FILE'               => 'File',
        '_ADD'                => 'Add',
        '_ADD_MSG'            => 'Added.',
        '_UPDATE'             => 'Update',
        '_UPDATE_MSG'         => 'Updated.',
        '_DELETE'             => 'Delete',
        '_DELETE_CONFIRM_MSG' => 'Do you delete it really?',
        '_DELETE_MSG'         => 'Deleted.',
        '_DETAIL'             => 'Detail',
        '_TRANS'              => 'Transfer',
        '_CANCEL'             => 'Cancel',
        '_BACK'               => 'Back',
        '_CLOSE'              => 'Close',
        '_SELECT'             => 'Select',
        '_COND_LABEL'         => 'Search Conditions:',
        '_COMP_MATCH'         => 'Fully Match',
        '_PART_MATCH'         => 'Partially Match',
        '_AND_MATCH'          => 'AND(And Match)',
        '_OR_MATCH'           => 'OR(Or Match)',
        '_OR_OVER'            => '(Or Over)',
        '_OR_LESS'            => '(Or Less)',
        '_SINCE'              => '(Since)',
        '_UNTIL'              => '(Until)',
        '_HIS_TITLE'          => 'Update History',
        '_HIS_ID'             => 'Update ID',
        '_OPERATION'          => 'Operation',
        '_UPDATE_UNAME'       => 'User Name',
        '_UPDATE_DATE'        => 'Update Date',
        '_BEFORE_TITLE'       => 'Before',
        '_AFTER_TITLE'        => 'After',
        '_SHOW'               => 'Show',
        '_HIDE'               => 'Hide',
		'_NOT_FOUND_MSG'      => 'There are not the data which fell under search condition.',
        '_REQ_ERR_MSG'        => '%s is required.',
        '_RANGE_ERR_MSG'      => '%s is over range.',
        '_INT_ERR_MSG'        => '%s is not integer value.',
        '_FLOAT_ERR_MSG'      => '%s is not float value.',
        '_FILE_TYPE_ERR_MSG'  => 'MIME Type %s(File %s) can not be uploaded.',
        '_FILE_EXT_ERR_MSG'   => 'File extension %s(File %s) can not be uploaded.',
        '_FILE_SIZE_ERR_MSG'  => 'File %s \'s size is over.',
        '_DATE_ERR_MSG'       => '%s\'s date format is invalid.',
        '_FILE_SAME_ERR_MSG'  => 'You can not do same time uploading and deleting file %s.',
        '_DUPLICATE_ERR_MSG'  => 'You can not add the data because the same data has already added.',
        '_TOKEN_ERR_MSG'      => 'Token Error occurred.',
        '_SYSTEM_ERR_MSG'     => 'System Error occurred.',
        '_PARAM_ERR_MSG'      => 'Parameter is invalid.',
        '_PERM_ERR_MSG'       => 'There are not permission.',
        '_NO_ERR_MSG'         => 'There are not appointed data.');

foreach ($main_consts as $key => $value) {
    if (!defined('_MD_' . $affix . $key)) {
        define('_MD_' . $affix . $key, $value);
    }
}

?>
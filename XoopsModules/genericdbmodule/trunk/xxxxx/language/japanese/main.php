<?php

$dirname = basename(dirname(dirname(dirname(__FILE__))));
$lang_dirname = basename(dirname(__FILE__));
$affix = strtoupper(strlen($dirname) >= 3 ? substr($dirname, 0, 3) : $dirname);

include_once XOOPS_ROOT_PATH . "/modules/$dirname/language/$lang_dirname/common.php";

$main_consts = array(
        '_PAGENAVI_INFO'      => '��%s���桢 %s���ܤ���%s���ܤޤǤ�ɽ��',
        '_REQ_MARK'           => '<font color="red">(ɬ��)</font>',
        '_SEARCH'             => '����',
        '_SEARCH_RESULT'      => '�������',
        '_ADD_DATE'           => '��Ͽ����',
        '_UNAME'              => '��Ͽ�桼��̾',
        '_FILE'               => '�ե�����',
        '_ADD'                => '��Ͽ',
        '_ADD_MSG'            => '��Ͽ���ޤ�����',
        '_UPDATE'             => '����',
        '_UPDATE_MSG'         => '�������ޤ�����',
        '_DELETE'             => '���',
        '_DELETE_CONFIRM_MSG' => '�����ˤ��ξ���������ޤ�����',
        '_DELETE_MSG'         => '������ޤ�����',
        '_DETAIL'             => '�ܺ�',
        '_TRANS'              => '�ܹ�',
        '_CANCEL'             => '����󥻥�',
        '_BACK'               => '���',
        '_CLOSE'              => '�Ĥ���',
        '_SELECT'             => '����',
        '_COND_LABEL'         => '������',
        '_COMP_MATCH'         => '��������',
        '_PART_MATCH'         => '��ʬ����',
        '_AND_MATCH'          => 'AND(���٤ư���)',
        '_OR_MATCH'           => 'OR(�����줫�˰���)',
        '_OR_OVER'            => '�ʾ�',
        '_OR_LESS'            => '�ʲ�',
        '_SINCE'              => '�ʹ�',
        '_UNTIL'              => '����',
        '_HIS_TITLE'          => '��������',
        '_HIS_ID'             => '����ID',
        '_OPERATION'          => '��������',
        '_UPDATE_UNAME'       => '�¹ԥ桼����̾',
        '_UPDATE_DATE'        => '��������',
        '_BEFORE_TITLE'       => '������',
        '_AFTER_TITLE'        => '������',
        '_SHOW'               => 'ɽ��',
        '_HIDE'               => '��ɽ��',
        '_NOT_FOUND_MSG'      => '�������˳�������ǡ����Ϥ���ޤ���Ǥ�����',
        '_REQ_ERR_MSG'        => '��%s�פϡ�ɬ�����Ϥ⤷�������򤷤Ƥ���������',
        '_RANGE_ERR_MSG'      => '��%s�פ������ͤ������ϰ�(%s)��Ķ���Ƥ��ޤ���',
        '_INT_ERR_MSG'        => '��%s�פ������Ͱʳ������Ϥ���Ƥ��ޤ���',
        '_FLOAT_ERR_MSG'      => '��%s�פ˾����Ͱʳ������Ϥ���Ƥ��ޤ���',
        '_FILE_TYPE_ERR_MSG'  => 'MIME�����ס�%s�פΥե������%s�פϥ��åץ��ɤǤ��ޤ���',
        '_FILE_EXT_ERR_MSG'   => '��ĥ�ҡ�%s�פΥե������%s�פϥ��åץ��ɤǤ��ޤ���',
        '_FILE_SIZE_ERR_MSG'  => '�ե������%s�פ����̤������ͤ�Ķ���Ƥ��ޤ���',
        '_DATE_ERR_MSG'       => '��%s�פ����դν񼰤˸�꤬����ޤ���',
        '_FILE_SAME_ERR_MSG'  => '�ե������%s�פΥ��åץ��ɤȺ����Ʊ���˹Ԥ��ޤ���',
        '_DUPLICATE_ERR_MSG'  => '���Ǥ�Ʊ�����Ƥξ�����Ͽ����Ƥ��뤿�ᡢ��Ͽ�Ǥ��ޤ���',
        '_TOKEN_ERR_MSG'      => '�ȡ����󥨥顼��ȯ�����ޤ�����',
        '_SYSTEM_ERR_MSG'     => '�����ƥ२�顼��ȯ�����ޤ�����',
        '_PARAM_ERR_MSG'      => '�ѥ�᡼�����������Ǥ���',
        '_PERM_ERR_MSG'       => '��������Ԥ����¤�����ޤ���',
        '_NO_ERR_MSG'         => '���ꤵ�줿����Ϥ���ޤ���');

foreach ($main_consts as $key => $value) {
    if (!defined('_MD_' . $affix . $key)) {
        define('_MD_' . $affix . $key, $value);
    }
}

?>
<?php

$dirname = basename(dirname(dirname(dirname(__FILE__))));
$lang_dirname = basename(dirname(__FILE__));
$affix = strtoupper(strlen($dirname) >= 3 ? substr($dirname, 0, 3) : $dirname);

include_once XOOPS_ROOT_PATH . "/modules/$dirname/language/$lang_dirname/common.php";

$modinfo_consts = array(
        '_MODULE_NAME'          => 'XOOPS���ѥǡ����١���',
        '_MODULE_DESC'          => '�������̤�����ܤι������ѹ��Ǥ�������Ū�ʥǡ����١����⥸�塼��Ǥ���',
        '_LOADED_JQ'            => '�ơ��ޤ�jQuery���ɤ߹���Ǥ���',
        '_ID_CAPTION'           => 'ID��̾��',
        '_TEMP_INDEX'           => '�����������ڡ���',
        '_TEMP_ADD'             => '��Ͽ�ڡ���',
        '_TEMP_UPDATE'          => '�����ڡ���',
        '_TEMP_DELETE'          => '����ڡ���',
        '_TEMP_DETAIL'          => '�ܺ٥ڡ���',
        '_MENU_ADD'             => '��Ͽ',
        '_MENU_HIS'             => '�������򸡺�',
        '_ITEM_MANAGE_MENU'     => '���ܴ���',
        '_MOD_UPDATE_MENU'      => '�⥸�塼�롦���åץǡ���',
        '_SEARCH_NUM'           => '������̤�1�ڡ����������ɽ�����',
        '_DATE_FORMAT'          => '���դν�',
        '_DATE_FORMAT_DESC'     => '���դν񼰤�date�ؿ�����1�����η����ǵ��Ҥ��ޤ���',
        '_TIME_FORMAT'          => '����ν�',
        '_TIME_FORMAT_DESC'     => '����ν񼰤�date�ؿ�����1�����η����ǵ��Ҥ��ޤ���',
        '_MANAGE_GROUPS'        => '�ǡ����������롼��',
        '_MANAGE_GROUPS_DESC'   => '�ǡ����������롼�פ˽�°����桼�����ϡ����٤Ƥ���Ͽ�ǡ����򹹿�������Ǥ��ޤ���',
        '_ADD_GROUPS'           => '�ǡ�������Ͽ����Ĥ��륰�롼��(ʣ�������)',
        '_ADD_GUEST'            => '�����ȥ桼�����˥ǡ�������Ͽ����Ĥ���',
        '_HIS_GROUPS'           => '��������λ��Ȥ���Ĥ��륰�롼��(ʣ�������)',
        '_HIS_GUEST'            => '�����ȥ桼�����˹�������λ��Ȥ���Ĥ���',
        '_AUTO_UPDATE'          => '�ƥ�ץ졼�ȥե�����μ�ư������ͭ���ˤ���',
        '_AUTO_UPDATE_DESC'     => '���ε�ǽ��ͭ���ˤ���ȡ�ư��®�٤��㲼���ޤ���<br />�ƥ�ץ졼�ȥե���������ˤ˹�������֤Τ�ͭ���ˤ��ޤ���',
        '_DETAIL_IMAGE_WIDTH'   => '�ܺٲ��̤�ɽ�������������(px)',
        '_LIST_IMAGE_WIDTH'     => '�������̤�ɽ�������������(px)',
        '_NEW_BLOCK'            => '������Ͽ�֥�å�',
        '_NTF_GLOBAL'           => '������Ͽ',
        '_NTF_GLOBAL_DESC'      => '������Ͽ�˴ؤ��륤�٥������',
        '_NTF_CHANGE'           => '��Ͽ�������',
        '_NTF_CHANGE_DESC'      => '����������˴ؤ��륤�٥������',
        '_NTF_ADD_TITLE'        => '��Ͽ���٥������',
        '_NTF_ADD_DESC'         => '������������Ͽ���줿�ݤ�ȯ�����륤�٥������',
        '_NTF_ADD_CAPTION'      => '������������Ͽ���줿�ݤ����Τ��ޤ���',
        '_NTF_ADD_SUBJECT'      => '������������Ͽ����ޤ���',
        '_NTF_UPDATE_TITLE'     => '�������٥������',
        '_NTF_UPDATE_DESC'      => '��Ͽ���󤬹������줿�ݤ�ȯ�����륤�٥������',
        '_NTF_UPDATE_CAPTION'   => '��Ͽ���󤬹������줿�ݤ����Τ��ޤ���',
        '_NTF_UPDATE_SUBJECT'   => '��Ͽ���󤬹�������ޤ���',
        '_NTF_DELETE_TITLE'     => '������٥������',
        '_NTF_DELETE_DESC'      => '��Ͽ���󤬺�����줿�ݤ�ȯ�����륤�٥������',
        '_NTF_DELETE_CAPTION'   => '��Ͽ���󤬺�����줿�ݤ����Τ��ޤ���',
        '_NTF_DELETE_SUBJECT'   => '��Ͽ���󤬺������ޤ���',
        '_MBSTRING_DISABLE_ERR' => 'mbstring�⥸�塼������ѤǤ��ޤ��󡣤��Υ⥸�塼��������ư�����ˤϡ�mbstring�⥸�塼��򥤥󥹥ȡ��뤹��ɬ�פ�����ޤ���',
        '_GD_DISABLE_ERR'       => 'gd�⥸�塼������ѤǤ��ޤ��󡣤��Υ⥸�塼��ǲ������������ˤϡ�gd�⥸�塼��򥤥󥹥ȡ��뤹��ɬ�פ�����ޤ���',
        '_GD_NOT_SUPPORTED_ERR' => 'gd�⥸�塼�뤬GIF/JPEG/PNG�������ɤ߽񤭤򥵥ݡ��Ȥ��Ƥ��ޤ��󡣤��Υ⥸�塼��ǲ������������ˤϡ�gd�⥸�塼�뤬GIF/JPEG/PNG�������ɤ߽񤭤򥵥ݡ��Ȥ���ɬ�פ�����ޤ���');

foreach ($modinfo_consts as $key => $value) {
    if (!defined('_MI_' . $affix . $key)) {
        define('_MI_' . $affix . $key, $value);
    }
}

?>
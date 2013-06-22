<?php

require_once XOOPS_ROOT_PATH . '/modules/' . $dirname . '/include/functions.php';

/**
 * 管理画面で使用する項目情報の内、指定したtypeに一致する項目情報を返す.
 *
 * @param String $type 取得するtypeの種類
 *
 * @return Array 項目情報の配列
 */
function getAdminItemDefs($type) {
    $dirname = basename(dirname(dirname(dirname(__FILE__))));
    $affix = strtoupper(strlen($dirname) >= 3 ? substr($dirname, 0, 3) : $dirname);

    $item_def = array();
    $item_def['caption'] = getAMConst('_NAME');
    $item_def['type'] = 'text';
    $item_def['required'] = 1;
    $item_def['value_type'] = 'string';
    $item_def['size'] = 32;
    $item_def['max_length'] = 255;
    $item_defs['name'] = $item_def;

    $item_def = array();
    $item_def['caption'] = getAMConst('_CAPTION');
    $item_def['type'] = 'text';
    $item_def['required'] = 1;
    $item_def['value_type'] = 'string';
    $item_def['size'] = 32;
    $item_def['max_length'] = 255;
    $item_defs['caption'] = $item_def;

    $item_def = array();
    $item_def['caption'] = getAMConst('_TYPE');
    $item_def['type'] = 'select';
    $item_def['required'] = 1;
    $item_def['value_type'] = 'string';
    $item_def['options'] = constant('_' . $affix . '_NOT_SELECTED') . "|\n";
    $item_def['options'] .= getAMConst('_TYPE_TEXT') . "|text\n";
    $item_def['options'] .= getAMConst('_TYPE_NUM') . "|number\n";
    $item_def['options'] .= getAMConst('_TYPE_CBOX') . "|cbox\n";
    $item_def['options'] .= getAMConst('_TYPE_RADIO') . "|radio\n";
    $item_def['options'] .= getAMConst('_TYPE_SELECT') . "|select\n";
    $item_def['options'] .= getAMConst('_TYPE_MSELECT') . "|mselect\n";
    $item_def['options'] .= getAMConst('_TYPE_TAREA') . "|tarea\n";
    $item_def['options'] .= getAMConst('_TYPE_XTAREA') . "|xtarea\n";
    $item_def['options'] .= getAMConst('_TYPE_FILE') . "|file\n";
    $item_def['options'] .= getAMConst('_TYPE_IMAGE') . "|image\n";
    $item_def['options'] .= getAMConst('_TYPE_DATE') . "|date\n";
    $item_def['options'] = nl2array($item_def['options']);
    $item_defs['type'] = $item_def;

    $item_def = array();
    $item_def['caption'] = getAMConst('_REQUIRED');
    $item_def['type'] = 'radio';
    $item_def['required'] = 1;
    $item_def['value_type'] = 'int';
    $item_def['options'] = nl2array(getAMConst('_YES') . "|1\n" . getAMConst('_NO') . "|0");
    $item_def['option_br'] = 0;
    $item_defs['required'] = $item_def;

    $item_def = array();
    $item_def['caption'] = getAMConst('_SHOW_GIDS');
    $item_def['type'] = 'mselect';
    $item_def['required'] = 0;
    $item_def['value_type'] = 'int';
    $item_def['size'] = 5;
    $item_def['input_desc'] = getAMConst('_SHOW_GIDS_DESC');
    $item_def['options'] = nl2array(makeGroupSelectOptions());
    $item_defs['show_gids'] = $item_def;

    $item_def = array();
    $item_def['caption'] = getAMConst('_SEQUENCE');
    $item_def['type'] = 'text';
    $item_def['required'] = 1;
    $item_def['value_type'] = 'int';
    $item_def['value_range_min'] = 0;
    $item_def['value_range_max'] = 9999;
    $item_def['size'] = 4;
    $item_def['max_length'] = 4;
    $item_defs['sequence'] = $item_def;

    $item_def = array();
    $item_def['caption'] = getAMConst('_SEARCH') . getAMConst('_PAGE');
    $item_def['type'] = 'radio';
    $item_def['required'] = 1;
    $item_def['value_type'] = 'int';
    $item_def['options'] = nl2array(getAMConst('_DISP') . "|1\n" . getAMConst('_NOT_DISP') . "|0");
    $item_def['option_br'] = 0;
    $item_defs['search'] = $item_def;

    $item_def = array();
    $item_def['caption'] = getAMConst('_LIST') . getAMConst('_PAGE');
    $item_def['type'] = 'radio';
    $item_def['required'] = 1;
    $item_def['value_type'] = 'int';
    $item_def['options'] = nl2array(getAMConst('_DISP') . "|1\n" . getAMConst('_NOT_DISP') . "|0");
    $item_def['option_br'] = 0;
    $item_defs['list'] = $item_def;

    $item_def = array();
    $item_def['caption'] = getAMConst('_ADD') . getAMConst('_PAGE');
    $item_def['type'] = 'radio';
    $item_def['required'] = 1;
    $item_def['value_type'] = 'int';
    $item_def['options'] = nl2array(getAMConst('_DISP') . "|1\n" . getAMConst('_NOT_DISP') . "|0");
    $item_def['option_br'] = 0;
    $item_defs['add'] = $item_def;

    $item_def = array();
    $item_def['caption'] = getAMConst('_UPDATE') . getAMConst('_PAGE');
    $item_def['type'] = 'radio';
    $item_def['required'] = 1;
    $item_def['value_type'] = 'int';
    $item_def['options'] = nl2array(getAMConst('_DISP') . "|1\n" . getAMConst('_NOT_DISP') . "|0");
    $item_def['option_br'] = 0;
    $item_defs['update'] = $item_def;

    $item_def = array();
    $item_def['caption'] = getAMConst('_DETAIL') . '/' . getAMConst('_DELETE') . getAMConst('_PAGE');
    $item_def['type'] = 'radio';
    $item_def['required'] = 1;
    $item_def['value_type'] = 'int';
    $item_def['options'] = nl2array(getAMConst('_DISP') . "|1\n" . getAMConst('_NOT_DISP') . "|0");
    $item_def['option_br'] = 0;
    $item_defs['detail'] = $item_def;

    $item_def = array();
    $item_def['caption'] = getAMConst('_SITE_SEARCH');
    $item_def['type'] = 'radio';
    $item_def['required'] = 1;
    $item_def['value_type'] = 'int';
    $item_def['options'] = nl2array(getAMConst('_YES') . "|1\n" . getAMConst('_NO') . "|0");
    $item_def['option_br'] = 0;
    $item_defs['site_search'] = $item_def;

    $item_def = array();
    $item_def['caption'] = getAMConst('_DUPLICATE_CHECK');
    $item_def['type'] = 'radio';
    $item_def['required'] = 1;
    $item_def['value_type'] = 'int';
    $item_def['options'] = nl2array(getAMConst('_YES') . "|1\n" . getAMConst('_NO') . "|0");
    $item_def['option_br'] = 0;
    $item_defs['duplicate'] = $item_def;

    $item_def = array();
    $item_def['caption'] = getAMConst('_SEARCH_DESC');
    $item_def['type'] = 'xtarea';
    $item_def['required'] = 0;
    $item_def['rows'] = 5;
    $item_def['cols'] = 50;
    $item_def['html'] = 0;
    $item_def['smily'] = 1;
    $item_def['xcode'] = 1;
    $item_def['image'] = 1;
    $item_def['br'] = 1;
    $item_defs['search_desc'] = $item_def;

    $item_def = array();
    $item_def['caption'] = getAMConst('_SHOW_DESC');
    $item_def['type'] = 'xtarea';
    $item_def['required'] = 0;
    $item_def['rows'] = 5;
    $item_def['cols'] = 50;
    $item_def['html'] = 0;
    $item_def['smily'] = 1;
    $item_def['xcode'] = 1;
    $item_def['image'] = 1;
    $item_def['br'] = 1;
    $item_defs['show_desc'] = $item_def;

    $item_def = array();
    $item_def['caption'] = getAMConst('_INPUT_DESC');
    $item_def['type'] = 'xtarea';
    $item_def['required'] = 0;
    $item_def['rows'] = 5;
    $item_def['cols'] = 50;
    $item_def['html'] = 0;
    $item_def['smily'] = 1;
    $item_def['xcode'] = 1;
    $item_def['image'] = 1;
    $item_def['br'] = 1;
    $item_defs['input_desc'] = $item_def;

    if ($type == 'text' || $type == 'number') {
        if ($type == 'text') {
            $item_def = array();
            $item_def['caption'] = getAMConst('_DISP_COND');
            $item_def['type'] = 'radio';
            $item_def['required'] = 1;
            $item_def['value_type'] = 'int';
            $item_def['options'] = nl2array(getAMConst('_DISP') . "|1\n" . getAMConst('_NOT_DISP') . "|0");
            $item_def['option_br'] = 0;
            $item_defs['disp_cond'] = $item_def;

            $item_def = array();
            $item_def['caption'] = getAMConst('_SEARCH_COND');
            $item_def['type'] = 'radio';
            $item_def['required'] = 1;
            $item_def['value_type'] = 'int';
            $item_def['options'] = nl2array(getAMConst('_COMP_MATCH') . "|1\n" . getAMConst('_PART_MATCH') . "|0");
            $item_def['option_br'] = 0;
            $item_defs['search_cond'] = $item_def;
        }

        if ($type == 'number') {
            $item_def = array();
            $item_def['caption'] = getAMConst('_VALUE_TYPE');
            $item_def['type'] = 'select';
            $item_def['required'] = 1;
            $item_def['value_type'] = 'string';
            $item_def['options'] = constant('_' . $affix . '_NOT_SELECTED') . "|\n";
            $item_def['options'] .= getAMConst('_INTEGER') . "|int\n";
            $item_def['options'] .= getAMConst('_FLOAT') . "|float";
            $item_def['options'] = nl2array($item_def['options']);
            $item_defs['value_type'] = $item_def;

            $item_def['caption'] = getAMConst('_VALUE_RANGE_MIN');
            $item_def['type'] = 'text';
            $item_def['required'] = 0;
            $item_def['value_type'] = 'int';
            $item_def['size'] = 9;
            $item_def['max_length'] = 9;
            $item_defs['value_range_min'] = $item_def;

            $item_def['caption'] = getAMConst('_VALUE_RANGE_MAX');
            $item_def['type'] = 'text';
            $item_def['required'] = 0;
            $item_def['value_type'] = 'int';
            $item_def['size'] = 9;
            $item_def['max_length'] = 9;
            $item_defs['value_range_max'] = $item_def;
        }

        $item_def = array();
        $item_def['caption'] = getAMConst('_DEFAULT');
        $item_def['type'] = 'text';
        $item_def['required'] = 0;
        $item_def['value_type'] = 'string';
        $item_def['size'] = 32;
        $item_def['max_length'] = 255;
        $item_defs['default'] = $item_def;

        $item_def = array();
        $item_def['caption'] = getAMConst('_SIZE');
        $item_def['type'] = 'text';
        $item_def['required'] = 1;
        $item_def['value_type'] = 'int';
        $item_def['value_range_min'] = 1;
        $item_def['value_range_max'] = 9999;
        $item_def['size'] = 4;
        $item_def['max_length'] = 4;
        $item_defs['size'] = $item_def;

        $item_def = array();
        $item_def['caption'] = getAMConst('_MAX_LENGTH');
        $item_def['type'] = 'text';
        $item_def['required'] = 1;
        $item_def['value_type'] = 'int';
        $item_def['value_range_min'] = 1;
        $item_def['value_range_max'] = 9999;
        $item_def['size'] = 4;
        $item_def['max_length'] = 4;
        $item_defs['max_length'] = $item_def;
    } elseif ($type == 'cbox' || $type == 'radio') {
        if ($type == 'cbox') {
            $item_def = array();
            $item_def['caption'] = getAMConst('_DISP_COND');
            $item_def['type'] = 'radio';
            $item_def['required'] = 1;
            $item_def['value_type'] = 'int';
            $item_def['options'] = nl2array(getAMConst('_DISP') . "|1\n" . getAMConst('_NOT_DISP') . "|0");
            $item_def['option_br'] = 0;
            $item_defs['disp_cond'] = $item_def;

            $item_def = array();
            $item_def['caption'] = getAMConst('_SEARCH_COND');
            $item_def['type'] = 'radio';
            $item_def['required'] = 1;
            $item_def['value_type'] = 'int';
            $item_def['input_desc'] = '';
            $item_def['options'] = nl2array(getAMConst('_AND_MATCH') . "|1\n" . getAMConst('_OR_MATCH') . "|0");
            $item_def['option_br'] = 0;
            $item_defs['search_cond'] = $item_def;
        }

        $item_def = array();
        $item_def['caption'] = getAMConst('_VALUE_TYPE');
        $item_def['type'] = 'select';
        $item_def['required'] = 1;
        $item_def['value_type'] = 'string';
        $item_def['options'] = constant('_' . $affix . '_NOT_SELECTED') . "|\n";
        $item_def['options'] .= getAMConst('_STRING') . "|string\n";
        $item_def['options'] .= getAMConst('_INTEGER') . "|int\n";
        $item_def['options'] .= getAMConst('_FLOAT') . "|float";
        $item_def['options'] = nl2array($item_def['options']);
        $item_defs['value_type'] = $item_def;

        if ($type == 'cbox') {
            $item_def = array();
            $item_def['caption'] = getAMConst('_DEFAULT');
            $item_def['type'] = 'tarea';
            $item_def['required'] = 0;
            $item_def['input_desc'] = getAMConst('_NOTE_VALUE_SEP');
            $item_def['show_desc'] = getAMConst('_NOTE_VALUE_SEP');
            $item_def['rows'] = 5;
            $item_def['cols'] = 50;
            $item_def['html'] = 0;
            $item_def['smily'] = 0;
            $item_def['xcode'] = 0;
            $item_def['image'] = 0;
            $item_def['br'] = 1;
            $item_defs['default'] = $item_def;
        } elseif ($type == 'radio') {
            $item_def = array();
            $item_def['caption'] = getAMConst('_DEFAULT');
            $item_def['type'] = 'text';
            $item_def['required'] = 0;
            $item_def['value_type'] = 'string';
            $item_def['size'] = 32;
            $item_def['max_length'] = 255;
            $item_defs['default'] = $item_def;
        }

        $item_def = array();
        $item_def['caption'] = getAMConst('_OPTIONS');
        $item_def['type'] = 'tarea';
        $item_def['required'] = 1;
        $item_def['input_desc'] = getAMConst('_NOTE_VALUE_SEP') . getAMConst('_NOTE_SHOW_VALUE_SEP');
        $item_def['show_desc'] = getAMConst('_NOTE_VALUE_SEP') . getAMConst('_NOTE_SHOW_VALUE_SEP');
        $item_def['rows'] = 5;
        $item_def['cols'] = 50;
        $item_def['html'] = 0;
        $item_def['smily'] = 0;
        $item_def['xcode'] = 0;
        $item_def['image'] = 0;
        $item_def['br'] = 1;
        $item_defs['options'] = $item_def;

        $item_def = array();
        $item_def['caption'] = getAMConst('_OPTION_BR');
        $item_def['type'] = 'radio';
        $item_def['required'] = 1;
        $item_def['value_type'] = 'int';
        $item_def['options'] = nl2array(getAMConst('_ENABLE') . "|1\n" . getAMConst('_DISABLE') . "|0");
        $item_def['option_br'] = 0;
        $item_defs['option_br'] = $item_def;
    } elseif ($type == 'select' || $type == 'mselect') {
        if ($type == 'mselect') {
            $item_def = array();
            $item_def['caption'] = getAMConst('_DISP_COND');
            $item_def['type'] = 'radio';
            $item_def['required'] = 1;
            $item_def['value_type'] = 'int';
            $item_def['options'] = nl2array(getAMConst('_DISP') . "|1\n" . getAMConst('_NOT_DISP') . "|0");
            $item_def['option_br'] = 0;
            $item_defs['disp_cond'] = $item_def;

            $item_def = array();
            $item_def['caption'] = getAMConst('_SEARCH_COND');
            $item_def['type'] = 'radio';
            $item_def['required'] = 1;
            $item_def['value_type'] = 'int';
            $item_def['input_desc'] = '';
            $item_def['options'] = nl2array(getAMConst('_AND_MATCH') . "|1\n" . getAMConst('_OR_MATCH') . "|0");
            $item_def['option_br'] = 0;
            $item_defs['search_cond'] = $item_def;
        }

        $item_def = array();
        $item_def['caption'] = getAMConst('_VALUE_TYPE');
        $item_def['type'] = 'select';
        $item_def['required'] = 1;
        $item_def['value_type'] = 'string';
        $item_def['options'] = constant('_' . $affix . '_NOT_SELECTED') . "|\n";
        $item_def['options'] .= getAMConst('_STRING') . "|string\n";
        $item_def['options'] .= getAMConst('_INTEGER') . "|int\n";
        $item_def['options'] .= getAMConst('_FLOAT') . "|float";
        $item_def['options'] = nl2array($item_def['options']);
        $item_defs['value_type'] = $item_def;

        if ($type == 'select') {
            $item_def = array();
            $item_def['caption'] = getAMConst('_DEFAULT');
            $item_def['type'] = 'text';
            $item_def['required'] = 0;
            $item_def['value_type'] = 'string';
            $item_def['size'] = 32;
            $item_def['max_length'] = 255;
            $item_defs['default'] = $item_def;
        } elseif ($type == 'mselect') {
            $item_def = array();
            $item_def['caption'] = getAMConst('_DEFAULT');
            $item_def['type'] = 'tarea';
            $item_def['required'] = 0;
            $item_def['input_desc'] = getAMConst('_NOTE_VALUE_SEP');
            $item_def['show_desc'] = getAMConst('_NOTE_VALUE_SEP');
            $item_def['rows'] = 5;
            $item_def['cols'] = 50;
            $item_def['html'] = 0;
            $item_def['smily'] = 0;
            $item_def['xcode'] = 0;
            $item_def['image'] = 0;
            $item_def['br'] = 1;
            $item_defs['default'] = $item_def;

            $item_def = array();
            $item_def['caption'] = getAMConst('_SIZE');
            $item_def['type'] = 'text';
            $item_def['required'] = 1;
            $item_def['value_type'] = 'int';
            $item_def['value_range_min'] = 1;
            $item_def['value_range_max'] = 9999;
            $item_def['size'] = 4;
            $item_def['max_length'] = 4;
            $item_defs['size'] = $item_def;
        }

        $item_def = array();
        $item_def['caption'] = getAMConst('_OPTIONS');
        $item_def['type'] = 'tarea';
        $item_def['required'] = 1;
        $item_def['input_desc'] = getAMConst('_NOTE_VALUE_SEP') . getAMConst('_NOTE_SHOW_VALUE_SEP');
        $item_def['show_desc'] = getAMConst('_NOTE_VALUE_SEP') . getAMConst('_NOTE_SHOW_VALUE_SEP');
        $item_def['rows'] = 5;
        $item_def['cols'] = 50;
        $item_def['html'] = 0;
        $item_def['smily'] = 0;
        $item_def['xcode'] = 0;
        $item_def['image'] = 0;
        $item_def['br'] = 1;
        $item_defs['options'] = $item_def;
    } elseif ($type == 'tarea' || $type == 'xtarea') {
        $item_def = array();
        $item_def['caption'] = getAMConst('_DEFAULT');
        $item_def['type'] = $type;
        $item_def['required'] = 0;
        $item_def['rows'] = 5;
        $item_def['cols'] = 50;
        $item_def['html'] = 0;
        $item_def['smily'] = 0;
        $item_def['xcode'] = 0;
        $item_def['image'] = 0;
        $item_def['br'] = 1;
        $item_defs['default'] = $item_def;

        $item_def = array();
        $item_def['caption'] = getAMConst('_SIZE');
        $item_def['type'] = 'text';
        $item_def['required'] = 1;
        $item_def['value_type'] = 'int';
        $item_def['value_range_min'] = 1;
        $item_def['value_range_max'] = 9999;
        $item_def['size'] = 4;
        $item_def['max_length'] = 4;
        $item_defs['size'] = $item_def;

        $item_def = array();
        $item_def['caption'] = getAMConst('_MAX_LENGTH');
        $item_def['type'] = 'text';
        $item_def['required'] = 1;
        $item_def['value_type'] = 'int';
        $item_def['value_range_min'] = 1;
        $item_def['value_range_max'] = 9999;
        $item_def['size'] = 4;
        $item_def['max_length'] = 4;
        $item_defs['max_length'] = $item_def;

        $item_def = array();
        $item_def['caption'] = getAMConst('_ROWS');
        $item_def['type'] = 'text';
        $item_def['required'] = 1;
        $item_def['value_type'] = 'int';
        $item_def['value_range_min'] = 1;
        $item_def['value_range_max'] = 9999;
        $item_def['size'] = 4;
        $item_def['max_length'] = 4;
        $item_defs['rows'] = $item_def;

        $item_def = array();
        $item_def['caption'] = getAMConst('_COLS');
        $item_def['type'] = 'text';
        $item_def['required'] = 1;
        $item_def['value_type'] = 'int';
        $item_def['value_range_min'] = 1;
        $item_def['value_range_max'] = 9999;
        $item_def['size'] = 4;
        $item_def['max_length'] = 4;
        $item_defs['cols'] = $item_def;

        $item_def = array();
        $item_def['caption'] = getAMConst('_HTML');
        $item_def['type'] = 'radio';
        $item_def['required'] = 1;
        $item_def['input_desc'] = getAMConst('_HTML_WARN');
        $item_def['show_desc'] = getAMConst('_HTML_WARN');
        $item_def['value_type'] = 'int';
        $item_def['options'] = nl2array(getAMConst('_ENABLE') . "|1\n" . getAMConst('_DISABLE') . "|0");
        $item_def['option_br'] = 0;
        $item_defs['html'] = $item_def;

        $item_def = array();
        $item_def['caption'] = getAMConst('_SMILY');
        $item_def['type'] = 'radio';
        $item_def['required'] = 1;
        $item_def['value_type'] = 'int';
        $item_def['options'] = nl2array(getAMConst('_ENABLE') . "|1\n" . getAMConst('_DISABLE') . "|0");
        $item_def['option_br'] = 0;
        $item_defs['smily'] = $item_def;

        $item_def = array();
        $item_def['caption'] = getAMConst('_XCODE');
        $item_def['type'] = 'radio';
        $item_def['required'] = 1;
        $item_def['value_type'] = 'int';
        $item_def['options'] = nl2array(getAMConst('_ENABLE') . "|1\n" . getAMConst('_DISABLE') . "|0");
        $item_def['option_br'] = 0;
        $item_defs['xcode'] = $item_def;

        $item_def = array();
        $item_def['caption'] = getAMConst('_IMAGE');
        $item_def['type'] = 'radio';
        $item_def['required'] = 1;
        $item_def['value_type'] = 'int';
        $item_def['options'] = nl2array(getAMConst('_ENABLE') . "|1\n" . getAMConst('_DISABLE') . "|0");
        $item_def['option_br'] = 0;
        $item_defs['image'] = $item_def;

        $item_def = array();
        $item_def['caption'] = getAMConst('_BR');
        $item_def['type'] = 'radio';
        $item_def['required'] = 1;
        $item_def['value_type'] = 'int';
        $item_def['options'] = nl2array(getAMConst('_ENABLE') . "|1\n" . getAMConst('_DISABLE') . "|0");
        $item_def['option_br'] = 0;
        $item_defs['br'] = $item_def;
    } elseif ($type == 'file' || $type == 'image') {
        $item_def = array();
        $item_def['caption'] = getAMConst('_DISP_COND');
        $item_def['type'] = 'radio';
        $item_def['required'] = 1;
        $item_def['value_type'] = 'int';
        $item_def['options'] = nl2array(getAMConst('_DISP') . "|1\n" . getAMConst('_NOT_DISP') . "|0");
        $item_def['option_br'] = 0;
        $item_defs['disp_cond'] = $item_def;

        $item_def = array();
        $item_def['caption'] = getAMConst('_SEARCH_COND');
        $item_def['type'] = 'radio';
        $item_def['required'] = 1;
        $item_def['value_type'] = 'int';
        $item_def['input_desc'] = '';
        $item_def['options'] = nl2array(getAMConst('_COMP_MATCH') . "|1\n" . getAMConst('_PART_MATCH') . "|0");
        $item_def['option_br'] = 0;
        $item_defs['search_cond'] = $item_def;

        $item_def = array();
        $item_def['caption'] = getAMConst('_SIZE');
        $item_def['type'] = 'text';
        $item_def['required'] = 1;
        $item_def['value_type'] = 'int';
        $item_def['value_range_min'] = 1;
        $item_def['value_range_max'] = 9999;
        $item_def['size'] = 4;
        $item_def['max_length'] = 4;
        $item_defs['size'] = $item_def;

        $item_def = array();
        $item_def['caption'] = getAMConst('_MAX_LENGTH');
        $item_def['type'] = 'text';
        $item_def['required'] = 1;
        $item_def['value_type'] = 'int';
        $item_def['value_range_min'] = 1;
        $item_def['value_range_max'] = 9999;
        $item_def['size'] = 4;
        $item_def['max_length'] = 4;
        $item_defs['max_length'] = $item_def;

        $item_def = array();
        $item_def['caption'] = getAMConst('_MAX_FILE_SIZE');
        $item_def['type'] = 'text';
        $item_def['required'] = 1;
        $item_def['value_type'] = 'int';
        $item_def['value_range_min'] = 1;
        $item_def['value_range_max'] = 9999;
        $item_def['size'] = 4;
        $item_def['max_length'] = 4;
        $item_defs['max_file_size'] = $item_def;

        if ($type == 'image') {
            $item_def = array();
            $item_def['caption'] = getAMConst('_MAX_IMAGE_SIZE');
            $item_def['type'] = 'text';
            $item_def['required'] = 1;
            $item_def['value_type'] = 'int';
            $item_def['value_range_min'] = 1;
            $item_def['value_range_max'] = 9999;
            $item_def['size'] = 4;
            $item_def['max_length'] = 4;
            $item_defs['max_image_size'] = $item_def;
        }

        $item_def = array();
        $item_def['caption'] = getAMConst('_ALLOWED_EXTS');
        $item_def['type'] = 'tarea';
        $item_def['required'] = 1;
        if ($type == 'file') $item_def['input_desc'] = getAMConst('_NOTE_VALUE_SEP') . getAMConst('_NOTE_ALLOWED_FILE_EXTS');
        else $item_def['input_desc'] = getAMConst('_NOTE_VALUE_SEP') . getAMConst('_NOTE_ALLOWED_IMG_EXTS');
        $item_def['rows'] = 5;
        $item_def['cols'] = 50;
        $item_def['html'] = 0;
        $item_def['smily'] = 0;
        $item_def['xcode'] = 0;
        $item_def['image'] = 0;
        $item_def['br'] = 1;
        $item_defs['allowed_exts'] = $item_def;

        $item_def = array();
        $item_def['caption'] = getAMConst('_ALLOWED_MIMES');
        $item_def['type'] = 'tarea';
        $item_def['required'] = 1;
        if ($type == 'file') $item_def['input_desc'] = getAMConst('_NOTE_VALUE_SEP') . getAMConst('_NOTE_ALLOWED_FILE_MIMES');
        else $item_def['input_desc'] = getAMConst('_NOTE_VALUE_SEP') . getAMConst('_NOTE_ALLOWED_IMG_MIMES');
        $item_def['rows'] = 5;
        $item_def['cols'] = 50;
        $item_def['html'] = 0;
        $item_def['smily'] = 0;
        $item_def['xcode'] = 0;
        $item_def['image'] = 0;
        $item_def['br'] = 1;
        $item_defs['allowed_mimes'] = $item_def;
    }

    return $item_defs;
}

/**
 * 引数の値が半角英数字(小文字)とアンダーバーだけで構成されているかチェックする.
 *
 * @param String $value チェック対象の値
 *
 * @return Boolean 半角英数字(小文字)とアンダーバーだけの場合true、それ以外の場合false
 */
function checkColumnName($value) {
    if ($value == '') return true;
    if (preg_match("/^[a-z0-9_]+$/", $value)) return true;
    return false;
}

/**
 * モジュール管理画面用(_AM_)用の定数を返す.
 *
 * @param String  $const_name 定数名
 * @return String 定数値
 */
function getAMConst($const_name) {
    $dirname = basename(dirname(dirname(dirname(__FILE__))));
    $affix = strtoupper(strlen($dirname) >= 3 ? substr($dirname, 0, 3) : $dirname);

    return constant('_AM_' . $affix . $const_name);
}

?>
<?php

if (!defined('_XGDB_FUNCTIONS_INCLUDED')) {
    define('_XGDB_FUNCTIONS_INCLUDED', true);

    /**
     * テキストボックスのinputタグを生成する.
     *
     * @param String $name name属性の値f
     * @param Array $item_def 項目の定義情報
     * @param String $default 初期値
     * @return String テキストボックスのinputタグ
     */
    function makeTextForm($name, $item_def, $default) {
        $myts = &MyTextSanitizer::getInstance();

        $ret = '<input type="text" name="' . $myts->htmlSpecialChars($name) . '" size="' . intval($item_def['size']) . '" maxlength="' . intval($item_def['max_length']) . '" value="' . $myts->htmlSpecialChars($default) . '" />';

        return $ret;
    }

    /**
     * チェックボックスのinputタグを生成する.
     *
     * @param String $name name属性の値
     * @param Array $item_def 項目の定義情報
     * @param Array $defaults 初期値
     * @return String チェックボックスのinputタグ
     */
    function makeCboxForm($name, $item_def, $defaults) {
        $myts = &MyTextSanitizer::getInstance();

        if (!is_array($defaults)) $defaults = string2array($defaults);
        $ret = '';

        foreach ($item_def['options'] as $key => $value) {
            $ret .= '<label style="margin-right: 1em;"><input type="checkbox" name="' . $myts->htmlSpecialChars($name) . '[]" value="' . $myts->htmlSpecialChars($value) . '"';
            foreach ($defaults as $default) {
                if ($default == $value) $ret .= " checked";
            }
            $ret .= '/>' . $myts->htmlSpecialChars($key) . '</label>';

            if ($item_def['option_br']) $ret .= '<br />';
        }
        if ($ret !== '' && substr($ret, -6) == '<br />') $ret = substr($ret, 0, -6);

        return $ret;
    }

    /**
     * ラジオボタンのinputタグを生成する.
     *
     * @param String $name name属性の値
     * @param Array $item_def 項目の定義情報
     * @param String $default 初期値
     * @return String ラジオボタンのinputタグ
     */
    function makeRadioForm($name, $item_def, $default) {
        $myts = &MyTextSanitizer::getInstance();

        $ret = '';

        foreach ($item_def['options'] as $key => $value) {
            $ret .= '<label style="margin-right: 1em;"><input type="radio" name="' . $myts->htmlSpecialChars($name) . '" value="' . $myts->htmlSpecialChars($value) . '"';
            if ($default == $value) $ret .= " checked";
            $ret .= '/>' . $myts->htmlSpecialChars($key) . '</label>';

            if ($item_def['option_br']) $ret .= '<br />';
        }
        if ($ret !== '' && substr($ret, -6) == '<br />') $ret = substr($ret, 0, -6);

        return $ret;
    }

    /**
     * プルダウンメニューのselectタグを生成する.
     *
     * @param String $name name属性の値
     * @param Array $item_def 項目の定義情報
     * @param String $default 初期値
     * @return String プルダウンメニューのselectタグ
     */
    function makeSelectForm($name, $item_def, $default) {
        global $affix;
        $myts = &MyTextSanitizer::getInstance();

        $not_selected_ary = array(constant('_' . $affix . '_NOT_SELECTED') => '');
        $item_def['options'] = $not_selected_ary + $item_def['options'];
        $ret = '<select name="' . $myts->htmlSpecialChars($name) . '">';

        foreach ($item_def['options'] as $key => $value) {
            $ret .= '<option value="' . $myts->htmlSpecialChars($value) . '"';
            if ($default === (string) $value) $ret .= ' selected="selected"';
            $ret .= '>' . $myts->htmlSpecialChars($key) . '</option>';
        }
        $ret .= '</select>';

        return $ret;
    }

    /**
     * リストボックスのselectタグを生成する.
     *
     * @param String $name name属性の値
     * @param Array $item_def 項目の定義情報
     * @param Array $defaults 初期値
     * @return String リストボックスのselectタグ
     */
    function makeMSelectForm($name, $item_def, $defaults) {
        $myts = &MyTextSanitizer::getInstance();

        if (!is_array($defaults)) $defaults = string2array($defaults);
        $ret = '<select name="' . $myts->htmlSpecialChars($name) . '[]" size="' . intval($item_def['size']) . '" multiple="multiple">';

        foreach ($item_def['options'] as $key => $value) {
            $ret .= '<option value="' . $myts->htmlSpecialChars($value) . '"';
            if (in_array($value, $defaults)) $ret .= ' selected="selected"';
            $ret .= '>' . $myts->htmlSpecialChars($key) . '</option>';
        }
        $ret .= '</select>';

        return $ret;
    }

    /**
     * テキストエリアのtextareaタグを生成する.
     *
     * @param String $name name属性の値
     * @param Array $item_def 項目の定義情報
     * @param String $default 初期値
     * @return String テキストエリアのtextareaタグ
     */
    function makeTAreaForm($name, $item_def, $default) {
        $myts = &MyTextSanitizer::getInstance();

        $ret = '<textarea name="' . $myts->htmlSpecialChars($name) . '" rows="' . intval($item_def['rows']) . '" cols="' . intval($item_def['cols']) . '">' . sanitize($default, $item_def) . '</textarea>';

        return $ret;
    }

    /**
     * BBコード対応テキストエリアのtextareaタグを生成する.
     *
     * @param String $name name属性の値
     * @param Array $item_def 項目の定義情報
     * @param String $default 初期値
     * @return String BBコード対応テキストエリアのtextareaタグ
     */
    function makeXTAreaForm($name, $item_def, $default) {
        $myts = &MyTextSanitizer::getInstance();

        $form = new XoopsFormDhtmlTextArea($myts->htmlSpecialChars($name), $myts->htmlSpecialChars($name), sanitize($default, $item_def), intval($item_def['rows']), intval($item_def['cols']));
        $ret = $form->render();

        return $ret;
    }

    /**
     * 日付のinputタグを生成する.
     *
     * @param String $name name属性の値
     * @param Array $item_def 項目の定義情報
     * @param String $default 初期値
     * @return String 日付のinputタグ
     */
    function makeDateForm($name, $item_def, $default) {
        $myts = &MyTextSanitizer::getInstance();

        $form = new XoopsFormTextDateSelect($myts->htmlSpecialChars($name), $myts->htmlSpecialChars($name), 15, $myts->htmlSpecialChars($default));
        $ret = $form->render();

        return $ret;
    }

    /**
     * ファイルアップロードのinputタグを生成する.
     *
     * @param String $name name属性の値
     * @param Array $item_def 項目の定義情報
     * @return String ファイルアップロードのinput
     */
    function makeFileForm($name, $item_def) {
        $myts = &MyTextSanitizer::getInstance();
        $ret = '<input type="file" name="' . $myts->htmlSpecialChars($name) . '" size="' . intval($item_def['size']) . ' maxlength="' . intval($item_def['max_length']) . '"  />';

        return $ret;
    }

    /**
     * 検索条件のラジオボタンのinputタグを生成する.
     *
     * @param String $name name属性の値
     * @param Array $options 選択肢の配列
     * @param String $default 初期値
     * @return String ラジオボタンのinputタグ
     */
    function makeCondForm($name, $options, $default) {
        global $affix;
        $myts = &MyTextSanitizer::getInstance();

        $ret = '<br />' . getMDConst('_COND_LABEL');

        foreach ($options as $key => $value) {
            $ret .= '<label style="margin-right: 1em;"><input type="radio" name="' . $myts->htmlSpecialChars($name) . '" value="' . $myts->htmlSpecialChars($value) . '"';
            if ($default == $value) $ret .= " checked";
            $ret .= '/>' . $myts->htmlSpecialChars($key) . '</label>';
        }

        return $ret;
    }

    /**
     * グループのセレクトボックスに使用するグループ名とグループIDの文字列を生成する.
     *
     * @return String グループのセレクトボックスに使用するグループ名とグループIDの文字列
     */
    function makeGroupSelectOptions() {
        $xoopsDB = &Database::getInstance();
        $ret = '';

        $sql = "SELECT groupid, name FROM " . $xoopsDB->prefix('groups') . " ORDER BY groupid ASC";
        $res = $xoopsDB->query($sql);
        $groups_ary = array();
        while (list($gid, $gname) = $xoopsDB->fetchRow($res)) {
            $groups_ary[$gid] = $gname;
        }

        foreach ($groups_ary as $gid => $gname) {
            $ret .= "$gname|$gid\n";
        }

        if ($ret !== '') $ret = substr($ret, 0, -1);
        return $ret;
    }

    /**
     * グループIDの文字列のリストを改行済みのグループ名の文字列に変換する.
     *
     * @param String $gidstring グループIDの文字列のリスト
     * @return String 改行済みのグループ名の文字列
     */
    function gidstring2brgroup($gidstring) {
        if (!isset($gidstring) || $gidstring === '') return '';

        $xoopsDB = &Database::getInstance();
        $myts = &MyTextSanitizer::getInstance();
        $ret = '';

        $sql = "SELECT groupid, name FROM " . $xoopsDB->prefix('groups') . " ORDER BY groupid ASC";
        $res = $xoopsDB->query($sql);
        $groups_ary = array();
        while (list($gid, $gname) = $xoopsDB->fetchRow($res)) {
            $groups_ary[$gid] = $gname;
        }

        $gid_ary = string2array($gidstring);
        foreach ($gid_ary as $gid) {
            if ($gid === '') continue;
            $ret .= $myts->htmlSpecialChars($groups_ary[$gid]) . '<br />';
        }

        return $ret;
    }

    /**
     * 指定したディレクトリ内でユニークになるランダムなファイル名を返す.
     *
     * @param String $ext ファイルの拡張子
     * @param String $target_dirpath ディレクトリのフルパス
     * @return String ファイル名(ディレクトリのパスは含まない)
     */
    function getUniqueFileName($ext, $target_dirpath) {
        $file_name = md5(XOOPS_SALT . uniqid(rand(), true)) . '.' . $ext;
        if (file_exists($target_dirpath . $file_name)) {
            $file_name = getUniqueFileName($ext, $target_dirpath);
        }

        return $file_name;
    }

    /**
     * ファイルの縦と横の大きさをリサイズする.
     *
     * @param String $file_name ファイル名(ファイルのパスを含む)
     * @param String $max_image_size 最大ファイルサイズ(px)
     * @return String リサイズしたファイルの拡張子。リサイズしなかった場合は空文字
     */
    function resizeImage($file_name, $max_image_size) {
        if (!extension_loaded('gd')) {
            return '';
        } else {
            $gd_infos = gd_info();
        }

        list($bef_x, $bef_y, $type) = getImageSize($file_name);
        if ($bef_x > $max_image_size || $bef_y > $max_image_size) {
            switch ($type) {
            case 1:
                if (!$gd_infos['GIF Read Support'] || !$gd_infos['GIF Create Support']) return '';
                $bef_img = ImageCreateFromGIF($file_name);
                break;
            case 2:
                if (isset($gd_infos['JPG Support']) && !$gd_infos['JPG Support']) return '';
                if (isset($gd_infos['JPEG Support']) && !$gd_infos['JPEG Support']) return '';
                $bef_img = ImageCreateFromJPEG($file_name);
                break;
            case 3:
                if (!$gd_infos['PNG Support']) return '';
                $bef_img = ImageCreateFromPNG($file_name);
                break;
            }

            if ($bef_x > $bef_y) {
                $aft_x = $max_image_size;
                $aft_y = $bef_y * ($max_image_size / $bef_x);
            } else {
                $aft_x = $bef_x * ($max_image_size / $bef_y);
                $aft_y = $max_image_size;
            }

            $aft_img = ImageCreateTrueColor($aft_x, $aft_y);
            ImageCopyResampled($aft_img, $bef_img, 0, 0, 0, 0, $aft_x, $aft_y, $bef_x, $bef_y);
            ImageDestroy($bef_img);

            switch ($type) {
            case 1:
                imageGIF($aft_img, $file_name);
                ImageDestroy($aft_img);
                return 'gif';
            case 2:
                imageJPEG($aft_img, $file_name);
                ImageDestroy($aft_img);
                return 'jpg';
            case 3:
                imagePNG($aft_img, $file_name);
                ImageDestroy($aft_img);
                return 'png';
            }
        }

        return '';
    }

    /**
     * 権限をチェックする.
     *
     * @param Array $user_groups ユーザーが所属するグループのグループIDの配列
     * @param Array $perm_groups 権限を持つグループのグループIDの配列
     * @return Boolean 権限がある場合はtrue、ない場合はfalse
     */
    function checkPerm($user_groups, $perm_groups) {
        foreach ($user_groups as $gid) {
            if (in_array($gid, $perm_groups)) {
                return true;
            }
        }
        return false;
    }

    /**
     * 指定したグループIDのグループに属するユーザーのXoopsUserObjectの配列を取得する.
     *
     * @param Array グループIDの配列
     * @return Array ユーザーのXoopsUserObjectの配列
     */
    function getUsers($gids) {
        $ret = array();

        foreach ($gids as $gid) {
            $member_handler = &xoops_gethandler('member');
            $users = &$member_handler->getUsersByGroup($gid, true);
            foreach ($users as $user) {
                $ret[$user->getVar('uid')] = $user;
            }
        }

        return $ret;
    }

    /**
     * stripslashes関数で再帰的に処理して返す.
     *
     * @param $value stripslashes関数で処理する値
     * @return String/Array 引数をstripslashes関数で処理した値
     */
    function stripSlashesDeep($value) {
        if (is_array($value)) {
            $value = array_map('stripSlashesDeep', $value);
        } else {
            $value = stripslashes($value);
        }

        return $value;
    }

    /**
     * 配列を区切り文字列に変換する.
     *
     * @param Array $array 配列
     * @param String $sep 区切り文字(初期値：|)
     *
     * @return String 文字列
     */
    function array2string($array, $sep = '|') {
        if (!is_array($array) && $array == '') return '';
        $ret = '';

        foreach ($array as $value) {
            $ret .= $value . $sep;
        }
        if ($ret != '') $ret = substr($ret, 0, -1 * strlen($sep));

        return $ret;
    }

    /**
     * 配列を改行区切り文字列に変換する.
     *
     * @param String $array 配列
     *
     * @return String 改行区切り文字列
     */
    function array2brstring($array) {
        $ret = '';

        foreach ($array as $key => $value) {
            $key .= '';
            $value .= '';
            if ($key !== $value) $ret .= $key . '|';
            $ret .= $value;
            $ret .= '<br />';
        }

        if ($ret !== '') $ret = substr($ret, 0, -6);

        return $ret;
    }

    /**
     * 区切り文字列を配列に変換する.
     *
     * @param String $string 文字列
     * @param String $sep 区切り文字(初期値：|)
     *
     * @return Array 配列
     */
    function string2array($string, $sep = '|') {
        if ($string != '') return explode($sep, $string);
        else return array();
    }

    /**
     * 改行区切り文字列を配列に変換する.
     *
     * @param String $string 文字列
     * @param String $sep 区切り文字(初期値：|)
     *
     * @return Array 配列
     */
    function nl2array($string, $sep = '|') {
        if ($string === '') return array();

        $myts = &MyTextSanitizer::getInstance();

        if (function_exists('mb_ereg_replace')) {
            $string = mb_ereg_replace("\r\n", "\n", $string);
            $string = mb_ereg_replace("\r", "\n", $string);
        } else {
            $string = ereg_replace("\r\n", "\n", $string);
            $string = ereg_replace("\r", "\n", $string);
        }
        $strings = explode("\n", $string);

        $ret = array();
        foreach ($strings as $value) {
            if (strpos($value, $sep)) {
                list($key, $value) = explode($sep, $value);
                $ret[$myts->htmlSpecialChars($key)] = $myts->htmlSpecialChars($value);
            } else {
                $value = $myts->htmlSpecialChars($value);
                $ret[$value] = $value;
            }
        }
        return $ret;
    }

    /**
     * グループIDのWHERE句を生成する.
     *
     * @param Array $gids グループIDの配列
     * @param String $as xgdb_itemテーブルの別名
     * @return String グループIDのWHERE句
     */
    function makeWhereGID($gids, $as = '') {
        $ret = '(';

        foreach ($gids as $gid) {
            if ($as) $ret .= "($as.show_gids LIKE '%|$gid|%') OR ";
            else $ret .= "(show_gids LIKE '%|$gid|%') OR ";
        }
        $ret = substr($ret, 0, -4);

        $ret .= ')';
        return $ret;
    }

    /**
     * すべての項目の情報を返す.
     *
     * @return Array 項目情報の配列
     */
    function getItemDefs($dirname, $gids) {
        //global $gids;
        $xoopsDB = &Database::getInstance();
        $myts = &MyTextSanitizer::getInstance();

        $ret = array();
        $sql = "SELECT * FROM " . $xoopsDB->prefix($dirname . '_xgdb_item') . " WHERE " . makeWhereGID($gids) . " ORDER BY `sequence` ASC, `iid` ASC";
        $res = $xoopsDB->query($sql);
        while ($row = $xoopsDB->fetchArray($res)) {
            $item = array();
            $item['caption'] = $myts->htmlSpecialChars($row['caption']);
            $item['type'] = $myts->htmlSpecialChars($row['type']);
            $item['required'] = $row['required'];
            $item['sequence'] = $row['sequence'];
            $item['search'] = $row['search'];
            $item['list'] = $row['list'];
            $item['add'] = $row['add'];
            $item['update'] = $row['update'];
            $item['detail'] = $row['detail'];
            $item['site_search'] = $row['site_search'];
            $item['duplicate'] = $row['duplicate'];
            $item['search_desc'] = $myts->displayTarea($row['search_desc']);
            $item['show_desc'] = $myts->displayTarea($row['show_desc']);
            $item['input_desc'] = $myts->displayTarea($row['input_desc']);
            $item['disp_cond'] = intval($row['disp_cond']);
            $item['search_cond'] = intval($row['search_cond']);
            if ($row['type'] == 'text') {
                $item['value_type'] = $myts->htmlSpecialChars($row['value_type']);
                $item['default'] = $myts->htmlSpecialChars($row['default']);
                $item['size'] = $row['size'];
                $item['max_length'] = $row['max_length'];
            } elseif ($row['type'] == 'number') {
                $item['value_type'] = $myts->htmlSpecialChars($row['value_type']);
                if ($row['value_range_min'] !== '') $item['value_range_min'] = $row['value_range_min'];
                if ($row['value_range_max'] !== '') $item['value_range_max'] = $row['value_range_max'];
                $item['default'] = $myts->htmlSpecialChars($row['default']);
                $item['size'] = $row['size'];
                $item['max_length'] = $row['max_length'];
            } elseif ($row['type'] == 'cbox') {
                $item['value_type'] = $myts->htmlSpecialChars($row['value_type']);
                $item['default'] = nl2array($row['default']);
                $item['options'] = nl2array($row['options']);
                $item['option_br'] = $row['option_br'];
            } elseif ($row['type'] == 'radio') {
                $item['value_type'] = $myts->htmlSpecialChars($row['value_type']);
                $item['default'] = $myts->htmlSpecialChars($row['default']);
                $item['options'] = nl2array($row['options']);
                $item['option_br'] = $row['option_br'];
            } elseif ($row['type'] == 'select') {
                $item['value_type'] = $myts->htmlSpecialChars($row['value_type']);
                $item['default'] = $myts->htmlSpecialChars($row['default']);
                $item['size'] = 5;
                $item['options'] = nl2array($row['options']);
            } elseif ($row['type'] == 'mselect') {
                $item['value_type'] = $myts->htmlSpecialChars($row['value_type']);
                $item['default'] = nl2array($row['default']);
                $item['size'] = $row['size'];
                $item['options'] = nl2array($row['options']);
            } elseif ($row['type'] == 'tarea' || $row['type'] == 'xtarea') {
                $item['default'] = $row['html'] ? $row['default'] : $myts->htmlSpecialChars($row['default']);
                $item['size'] = $row['size'];
                $item['max_length'] = $row['max_length'];
                $item['rows'] = $row['rows'];
                $item['cols'] = $row['cols'];
                $item['html'] = $row['html'];
                $item['smily'] = $row['smily'];
                $item['xcode'] = $row['xcode'];
                $item['image'] = $row['image'];
                $item['br'] = $row['br'];
            } elseif ($row['type'] == 'file' || $row['type'] == 'image') {
                $item['default'] = '';
                $item['size'] = $row['size'];
                $item['max_length'] = $row['max_length'];
                $item['max_file_size'] = $row['max_file_size'];
                if ($row['type'] == 'image') $item['max_image_size'] = $row['max_image_size'];
                $item['allowed_exts'] = nl2array($row['allowed_exts']);
                $item['allowed_mimes'] = nl2array($row['allowed_mimes']);
            } elseif ($row['type'] == 'date') {
                $item['default'] = '';
            }

            $ret[$myts->htmlSpecialChars($row['name'])] = $item;
        }

        return $ret;
    }

    /**
     * 項目情報の内、指定したtypeに一致する項目情報を返す.
     *
     * @param Array $defs 項目情報の配列
     * @param String $type 取得するtypeの種類
     *
     * @return Array 項目情報の配列
     */
    function getDefs($defs, $type) {
        global $cfg_id_caption;
        $ret = array();

        //        if ($type == 'search') {
        //            $did_item_def['caption'] = $cfg_id_caption;
        //            $did_item_def['type'] = 'text';
        //            $did_item_def['required'] = 1;
        //            $did_item_def['sequence'] = 1;
        //            $did_item_def['search'] = 1;
        //            $did_item_def['list'] = 1;
        //            $did_item_def['add'] = 0;
        //            $did_item_def['update'] = 0;
        //            $did_item_def['detail'] = 1;
        //            $did_item_def['site_search'] = 0;
        //            $did_item_def['duplicate'] = 0;
        //            $did_item_def['search_desc'] = '';
        //            $did_item_def['show_desc'] = '';
        //            $did_item_def['input_desc'] = '';
        //            $did_item_def['disp_cond'] = 0;
        //            $did_item_def['search_cond'] = 1;
        //            $did_item_def['value_range_min'] = 1;
        //            $did_item_def['value_range_max'] = 99999;
        //            $did_item_def['value_type'] = 'int';
        //            $did_item_def['default'] = '';
        //            $did_item_def['size'] = 10;
        //            $did_item_def['max_length'] = 10;
        //            $ret['did'] = $did_item_def;
        //        }

        foreach ($defs as $index => $def) {
            if (isset($def[$type]) && $def[$type]) {
                $ret[$index] = $def;
                if ($type == 'search' && ($def['type'] == 'number' || $def['type'] == 'date')) {
                    $ret[$index . '_or_over'] = $def;
                    $ret[$index . '_or_over']['is_range_item'] = true;
                    if ($def['type'] == 'number') {
                        $ret[$index . '_or_over']['caption'] = $def['caption'] . '(' . getMDConst('_OR_OVER') . ')';
                    } elseif ($def['type'] == 'date') {
                        $ret[$index . '_or_over']['caption'] = $def['caption'] . '(' . getMDConst('_SINCE') . ')';
                    }

                    $ret[$index . '_or_less'] = $def;
                    $ret[$index . '_or_less']['is_range_item'] = true;
                    if ($def['type'] == 'number') {
                        $ret[$index . '_or_less']['caption'] = $def['caption'] . '(' . getMDConst('_OR_LESS') . ')';
                    } elseif ($def['type'] == 'date') {
                        $ret[$index . '_or_less']['caption'] = $def['caption'] . '(' . getMDConst('_UNTIL') . ')';
                    }
                }
            }
        }

        return $ret;
    }

    /**
     * 項目情報の内容に従って、引数の値をサニタイズして返す.
     *
     * @param String $value サニタイズ対象の値
     * @param Array  $item_def 項目情報
     * @param Boolean $number_format 数値書式フォーマット
     *
     * @return String サニタイズした引数$value
     */
    function sanitize($value, $item_def, $number_format = true) {
        if ($value === '' || $value === NULL) return '';

        $myts = &MyTextSanitizer::getInstance();
        global $cfg_date_format;

        if ($item_def['type'] == 'number') {
            if ($item_def['value_type'] == 'int') {
                $value = intval($value);
                if ($number_format) $value = number_format($value);
            } elseif ($item_def['value_type'] == 'float') {
                $value = floatval($value);
                if (strpos($value, '.') === false) $value .= '.0';
                if ($number_format) {
                    $value = number_format($value, strlen($value) - intval(strpos($value, '.')) - 1);
                } else {
                    $value = number_format($value, strlen($value) - intval(strpos($value, '.')) - 1, '.', '');
                }
            }
        } elseif ($item_def['type'] == 'tarea' || $item_def['type'] == 'xtarea') {
            if (!$item_def['html']) {
                $value = $myts->htmlSpecialChars($value);
            }
        } elseif ($item_def['type'] == 'file' || $item_def['type'] == 'image') {
            $value = $myts->htmlSpecialChars($value);
        } elseif ($item_def['type'] == 'date') {
            $value = date($cfg_date_format, strtotime($value));
        } else {
            // text,cbox,radio,select,mselect
            $value = $myts->makeClickable($myts->htmlSpecialChars($value));
        }

        return $value;
    }

    /**
     * モジュールのテンプレートファイルを更新する.
     *
     * @param String  $tpl_set      テンプレートセット名
     * @param String  $tpl_file     テンプレートファイル名
     * @param String  $tpl_source   テンプレートファイルのソースコードの内容
     * @param Integer $lastmodified 最終更新日時のタイムスタンプ
     */
    function updateTemplate($tpl_set, $tpl_file, $tpl_source, $lastmodified = 0) {
        include_once XOOPS_ROOT_PATH . '/class/template.php';
        $xoopsDB = &Database::getInstance();
        $tplfile_tbl = $xoopsDB->prefix("tplfile");
        $tplsource_tbl = $xoopsDB->prefix("tplsource");

        $sql = "SELECT * FROM $tplfile_tbl WHERE tpl_tplset = 'default' AND tpl_file = '" . addslashes($tpl_file) . "'";
        $res = $xoopsDB->query($sql);
        if ($xoopsDB->getRowsNum($res) == 0) return;

        $tpl_id_sql = "SELECT tpl_id FROM $tplfile_tbl WHERE tpl_tplset = '" . addslashes($tpl_set) . "' AND tpl_file = '" . addslashes($tpl_file) . "'";
        $tpl_id_res = $xoopsDB->query($tpl_id_sql);

        if ($tpl_set != 'default' && $xoopsDB->getRowsNum($tpl_id_res) == 0) {
            while ($row = $xoopsDB->fetchArray($res)) {
                $xoopsDB->queryF("INSERT INTO $tplfile_tbl SET tpl_refid = '" . addslashes($row['tpl_refid']) . "',tpl_module = '" . addslashes($row['tpl_module']) . "',tpl_tplset = '" . addslashes($tpl_set) . "',tpl_file = '" . addslashes($tpl_file) . "',tpl_desc = '" . addslashes($row['tpl_desc']) . "',tpl_type = '" . addslashes($row['tpl_type']) . "'");
                $tpl_id = $xoopsDB->getInsertId();
                $xoopsDB->queryF("INSERT INTO $tplsource_tbl SET tpl_id = '$tpl_id', tpl_source = ''");
            }
        }

        while (list($tpl_id) = $xoopsDB->fetchRow($tpl_id_res)) {
            $xoopsDB->queryF("UPDATE $tplfile_tbl SET tpl_lastmodified = '" . addslashes($lastmodified) . "',tpl_lastimported=UNIX_TIMESTAMP() WHERE tpl_id = '$tpl_id'");
            $xoopsDB->queryF("UPDATE $tplsource_tbl SET tpl_source = '" . addslashes($tpl_source) . "' WHERE tpl_id = '$tpl_id'");
            $error_reporting = error_reporting(0);
            xoops_template_touch($tpl_id);
            error_reporting($error_reporting);
        }
    }

    /**
     * 最小値と最大値の範囲をあらわす文字列表現を返す.
     *
     * @param String  $value_range_min 最小値
     * @param String  $value_range_max 最大値
     *
     * @return String 最小値と最大値の範囲
     */
    function getRangeText($value_range_min, $value_range_max) {
        global $affix;
        $ret = '';
        if (isset($value_range_min)) {
            $ret .= $value_range_min . constant('_' . $affix . '_MORE_THAN');
        }
        if (isset($value_range_max)) {
            if ($ret !== '') {
                $ret .= constant('_' . $affix . '_COMMA');
            }
            $ret .= $value_range_max . constant('_' . $affix . '_LESS_THAN');
        }

        return $ret;
    }

    /**
     * 値が整数値かどうか判定する.
     *
     * @param String  $value 値
     *
     * @return Boolean 整数値の場合true、それ以外の場合false
     */
    function is_intval($value) {
        if (!isset($value) || $value === '') {
            return false;
        } elseif (!is_numeric($value)) {
            return false;
        } elseif (strpos($value, '.') !== false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 値が小数値かどうか判定する.
     *
     * @param String  $value 値
     *
     * @return Boolean 小数値の場合true、それ以外の場合false
     */
    function is_floatval($value) {
        if (!isset($value) || $value === '') {
            return false;
        } elseif (!is_numeric($value)) {
            return false;
        } elseif (strpos($value, '.') === false) {
            return false;
        } elseif (!is_numeric(substr($value, -1))) {
            return false;
        } elseif (strpos($value, '.') === 0) {
            return false;
        } elseif (!is_numeric(substr($value, strpos($value, '.') - 1, 1))) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 保存されているファイル名を返す.
     *
     * @param String  $did データID
     * @param String  $col_name 列名
     * @param String  $file_name テーブル上のファイル名
     * @return String 保存されているファイル名
     */
    function getRealFileName($did, $col_name, $file_name) {
        return urlencode("$did-$col_name-$file_name");
    }

    /**
     * 画像ファイルの幅を返す.
     *
     * @param String  $filename 画像ファイルの絶対パス
     * @param String  $cfg_width 画像ファイルの幅の設定値
     * @return int 画像ファイルの幅
     */
    function getImageWidth($filename, $cfg_width) {
        list($x, $y, $type) = getImageSize($filename);

        if ($x > $cfg_width) $ret = $cfg_width;
        else return $ret = $x;

        return $ret;
    }

    /**
     * 範囲を持つ項目かを返す.
     *
     * @param String  $item_name 項目名
     * @return boolean 範囲を持つ項目の場合true、範囲を持たない項目の場合false
     */
    function isRangeItemName($item_name) {
        if (strlen($item_name) > 8) {
            if (substr($item_name, -8) == '_or_over' || substr($item_name, -8) == '_or_less') {
                return true;
            }
        }

        return false;
    }

    /**
     * 数値が範囲内に収まっているかを返す.
     *
     * @param Array  $item_def 項目の定義情報
     * @param Array  $item_def 項目の定義情報
     * @return int -1：未満、0：範囲内、1：超過
     */
    function checkNumberRange($item_def, $value) {
        if ($item_def['value_type'] == 'int') {
            if (isset($item_def['value_range_min']) && $item_def['value_range_min'] !== '') {
                if (intval($value) < intval($item_def['value_range_min'])) {
                    return -1;
                }
            } elseif (isset($item_def['value_range_max']) && $item_def['value_range_max'] !== '') {
                if (intval($value) > intval($item_def['value_range_max'])) {
                    return 1;
                }
            }
        } else {
            if (isset($item_def['value_range_min']) && $item_def['value_range_min'] !== '') {
                if (floatval($value) < floatval($item_def['value_range_min'])) {
                    return -1;
                }
            } elseif (isset($item_def['value_range_max']) && $item_def['value_range_max'] !== '') {
                if (floatval($value) > floatval($item_def['value_range_max'])) {
                    return 1;
                }
            }
        }
        return 0;
    }

    /**
     * モジュールフロント画面用(_MD_)用の定数を返す.
     *
     * @param String  $const_name 定数名
     * @return String 定数値
     */
    function getMDConst($const_name) {
        global $affix;
        return constant('_MD_' . $affix . $const_name);
    }

    /**
     * 入力用のフォームを生成する.
     *
     * @param Array  &$item_defs 項目定義情報の配列
     */
    function makeInputForms(&$item_defs) {
        foreach ($item_defs as $item_name => $item_def) {
            if ($item_def['type'] == 'text' || $item_def['type'] == 'number') {
                $item_defs[$item_name]['value'] = makeTextForm($item_name, $item_def, $item_defs[$item_name]['raw']);
            } elseif ($item_def['type'] == 'cbox') {
                $item_defs[$item_name]['value'] = makeCboxForm($item_name, $item_def, $item_defs[$item_name]['raw']);
            } elseif ($item_def['type'] == 'radio') {
                $item_defs[$item_name]['value'] = makeRadioForm($item_name, $item_def, $item_defs[$item_name]['raw']);
            } elseif ($item_def['type'] == 'select') {
                $item_defs[$item_name]['value'] = makeSelectForm($item_name, $item_def, $item_defs[$item_name]['raw']);
            } elseif ($item_def['type'] == 'mselect') {
                $item_defs[$item_name]['value'] = makeMSelectForm($item_name, $item_def, $item_defs[$item_name]['raw']);
            } elseif ($item_def['type'] == 'tarea') {
                $item_defs[$item_name]['value'] = makeTAreaForm($item_name, $item_def, $item_defs[$item_name]['raw']);
            } elseif ($item_def['type'] == 'xtarea') {
                $item_defs[$item_name]['value'] = makeXTAreaForm($item_name, $item_def, $item_defs[$item_name]['raw']);
            } elseif ($item_def['type'] == 'file' || $item_def['type'] == 'image') {
                $item_defs[$item_name]['value'] = makeFileForm($item_name, $item_def);
            } elseif ($item_def['type'] == 'date') {
                $item_defs[$item_name]['value'] = makeDateForm($item_name, $item_def, $item_defs[$item_name]['raw']);
            }
        }
    }

    /**
     * 入力値を初期化する.
     *
     * @param Array  $item_def 項目定義情報
     * @param String  $item_name 項目名
     * @param Array  &$item_defs 項目定義情報の配列
     * @param Array  &$uploaded_file_defs アップロードファイルの項目名の配列
     * @param Array  &$errors エラーメッセージの配列
     * @param String  $type 処理のタイプ
     */
    function initInput($item_def, $item_name, &$item_defs, &$uploaded_file_defs, &$errors, $type) {
        $myts = &MyTextSanitizer::getInstance();

        $ret = '';
        if ($item_def[$type]) {
            // ファイル、画像の場合
            if ($item_def['type'] == 'file' || $item_def['type'] == 'image') {
                if (isset($_FILES[$item_name]['tmp_name']) && $_FILES[$item_name]['tmp_name'] !== '') {
                    if (!in_array($_FILES[$item_name]['type'], $item_def['allowed_mimes'])) {
                        $errors[] = sprintf(getMDConst('_FILE_TYPE_ERR_MSG'), $myts->htmlSpecialChars($_FILES[$item_name]['type']), $item_def['caption']);
                        $item_defs[$item_name]['error'] = '<br />' . sprintf(getMDConst('_FILE_TYPE_ERR_MSG'), $myts->htmlSpecialChars($_FILES[$item_name]['type']), $item_def['caption']);
                    } elseif (!in_array(pathinfo($_FILES[$item_name]['name'], PATHINFO_EXTENSION), $item_def['allowed_exts'])) {
                        $errors[] = sprintf(getMDConst('_FILE_EXT_ERR_MSG'), $myts->htmlSpecialChars(pathinfo($_FILES[$item_name]['name'], PATHINFO_EXTENSION)), $item_def['caption']);
                        $item_defs[$item_name]['error'] = '<br />' . sprintf(getMDConst('_FILE_EXT_ERR_MSG'), $myts->htmlSpecialChars(pathinfo($_FILES[$item_name]['name'], PATHINFO_EXTENSION)), $item_def['caption']);
                    } elseif ($_FILES[$item_name]['size'] > ($item_def['max_file_size'] * 1024 * 1024)) {
                        $errors[] = sprintf(getMDConst('_FILE_SIZE_ERR_MSG'), $item_def['caption']);
                        $item_defs[$item_name]['error'] = '<br />' . sprintf(getMDConst('_FILE_SIZE_ERR_MSG'), $item_def['caption']);
                    } else {
                        $ret = $_FILES[$item_name]['name'];
                        $uploaded_file_defs[$item_name] = $item_def;
                    }
                } else {
                    if ($type === 'add' && $item_def['required']) {
                        $errors[] = sprintf(getMDConst('_REQ_ERR_MSG'), $item_def['caption']);
                        $item_defs[$item_name]['error'] = '<br />' . sprintf(getMDConst('_REQ_ERR_MSG'), $item_def['caption']);
                    }
                }
            } elseif ($item_def['type'] == 'number') {
                // 数値の場合
                if (isset($_POST[$item_name]) && $_POST[$item_name] !== '') {
                    $ret = $_POST[$item_name];
                    // 整数の書式かどうか
                    if ($item_def['value_type'] == 'int' && !is_intval($ret)) {
                        $errors[] = sprintf(getMDConst('_INT_ERR_MSG'), $item_def['caption']);
                        $item_defs[$item_name]['error'] = '<br />' . sprintf(getMDConst('_INT_ERR_MSG'), $item_def['caption']);
                    } elseif ($item_def['value_type'] == 'float' && !is_floatval($ret)) {
                        // 小数の書式かどうか
                        if (!is_floatval($ret . '.0')) {
                            $errors[] = sprintf(getMDConst('_FLOAT_ERR_MSG'), $item_def['caption']);
                            $item_defs[$item_name]['error'] = '<br />' . sprintf(getMDConst('_FLOAT_ERR_MSG'), $item_def['caption']);
                        }
                    }

                    // 範囲チェック
                    if (checkNumberRange($item_def, $ret) !== 0) {
                        $errors[] = sprintf(getMDConst('_RANGE_ERR_MSG'), $item_def['caption'], getRangeText($item_def['value_range_min'], $item_def['value_range_max']));
                        $item_defs[$item_name]['error'] = '<br />' . sprintf(getMDConst('_RANGE_ERR_MSG'), $item_def['caption'], getRangeText($item_def['value_range_min'], $item_def['value_range_max']));
                    }
                } else {
                    if ($item_def['required']) {
                        $errors[] = sprintf(getMDConst('_REQ_ERR_MSG'), $item_def['caption']);
                        $item_defs[$item_name]['error'] = '<br />' . sprintf(getMDConst('_REQ_ERR_MSG'), $item_def['caption']);
                    }
                }
            } elseif ($item_def['type'] == 'date') {
                // 日付の場合
                if (isset($_POST[$item_name]) && $_POST[$item_name] !== '') {
                    $ret = $_POST[$item_name];
                    if (!isValidDate($ret)) {
                        $errors[] = sprintf(getMDConst('_DATE_ERR_MSG'), $item_def['caption']);
                        $item_defs[$item_name]['error'] = '<br />' . sprintf(getMDConst('_DATE_ERR_MSG'), $item_def['caption']);
                    }
                } else {
                    if ($item_def['required']) {
                        $errors[] = sprintf(getMDConst('_REQ_ERR_MSG'), $item_def['caption']);
                        $item_defs[$item_name]['error'] = '<br />' . sprintf(getMDConst('_REQ_ERR_MSG'), $item_def['caption']);
                    }
                }
            } else {
                // その他の場合
                if (isset($_POST[$item_name]) && $_POST[$item_name] !== '') {
                    $ret = $_POST[$item_name];
                } else {
                    if ($item_def['required']) {
                        $errors[] = sprintf(getMDConst('_REQ_ERR_MSG'), $item_def['caption']);
                        $item_defs[$item_name]['error'] = '<br />' . sprintf(getMDConst('_REQ_ERR_MSG'), $item_def['caption']);
                    }
                }
            }
        } else {
            // 項目が非表示の場合
            $ret = $item_def['default'];
        }

        $item_defs[$item_name]['raw'] = $ret;

        return $ret;
    }

    /**
     * 日付の書式をチェックする.
     *
     * @param String  $date 日付
     * @return boolean 正しい場合true、正しくない場合false
     */
    function isValidDate($date) {
        $vals = explode('-', $date);
        if (count($vals) == 3) {
            $year = intval($vals[0]);
            $month = intval($vals[1]);
            $day = intval($vals[2]);
            return checkdate($month, $day, $year);
        } else {
            return false;
        }
    }

    /**
     * 重複レコードが存在するかチェックする.
     *
     * @param String  $value チェックする値
     * @param String  $item_name 項目名
     * @param Array  &$item_defs 項目定義情報の配列
     * @param Array  &$errors エラーメッセージの配列
     * @param int  $did 除外するデータID
     */
    function checkDuplicate($value, $item_name, &$item_defs, &$errors, $did = 0) {
        global $data_tbl;
        $xoopsDB = &Database::getInstance();

        $sql = "SELECT * FROM $data_tbl WHERE ";
        $where_value = is_array($value) ? array2string($value) : $value;
        if ($where_value === '') {
            $sql .= $item_name . " IS NULL";
        } else {
            $sql .= $item_name . " = '" . addslashes($where_value) . "'";
        }
        if ($did > 0) $sql .= " AND did != $did";
        $res = $xoopsDB->query($sql);
        if ($xoopsDB->getRowsNum($res) > 0) {
            $item_defs[$item_name]['error'] = '<br />' . getMDConst('_DUPLICATE_ERR_MSG');
            if (!in_array(getMDConst('_DUPLICATE_ERR_MSG'), $errors)) $errors[] = getMDConst('_DUPLICATE_ERR_MSG');
        }
    }

    /**
     * 変数に詳細情報を割り当てる.
     *
     * @param Array  $row レコード
     * @param Array  &$item_defs 項目定義情報の配列
     * @param String $target_dirname モジュールディレクトリ名
     */
    function assignDetail($row, &$item_defs, $target_dirname) {
        global $cfg_date_format, $cfg_time_format, $cfg_main_img_wd, $dirname;
        $myts = &MyTextSanitizer::getInstance();
        $upload_dir = XOOPS_UPLOAD_PATH . '/' . $target_dirname;

        foreach ($row as $key => $value) {
            if ($key == 'did' || $key == 'add_uid' || $key == 'update_uid' || $key == 'uname') {
                $item_defs[$key]['value'] = $myts->htmlSpecialChars($value);
            } elseif ($key == 'add_date' || $key == 'update_date') {
                $item_defs[$key]['value'] = date($cfg_date_format . ' ' . $cfg_time_format, strtotime($value));
            } elseif (!isset($item_defs[$key])) {
                continue;
            } elseif ($item_defs[$key]['type'] == 'text' || $item_defs[$key]['type'] == 'number' || $item_defs[$key]['type'] == 'radio' || $item_defs[$key]['type'] == 'select' || $item_defs[$key]['type'] == 'date') {
                $item_defs[$key]['value'] = sanitize($value, $item_defs[$key]);
            } elseif ($item_defs[$key]['type'] == 'cbox' || $item_defs[$key]['type'] == 'mselect') {
                $values = string2array($value);
                $item_defs[$key]['value'] = '';
                foreach ($values as $value) {
                    $item_defs[$key]['value'] .= sanitize($value, $item_defs[$key]) . '<br />';
                }
            } elseif ($item_defs[$key]['type'] == 'tarea' || $item_defs[$key]['type'] == 'xtarea') {
                $item_defs[$key]['value'] = $myts->displayTarea($value, $item_defs[$key]['html'], $item_defs[$key]['smily'], $item_defs[$key]['xcode'], $item_defs[$key]['image'], $item_defs[$key]['br']);
            } elseif ($item_defs[$key]['type'] == 'image') {
                $filename = $upload_dir . '/' . getRealFileName($row['did'], $key, $value);
                if ($value != '' && file_exists($filename)) $item_defs[$key]['width'] = getImageWidth($filename, $cfg_main_img_wd);
                $item_defs[$key]['value'] = $myts->htmlSpecialChars($value);
            } elseif ($item_defs[$key]['type'] == 'file') {
                $item_defs[$key]['value'] = $myts->htmlSpecialChars($value);
            }
        }
    }

    /**
     * 検索条件の入力値を初期化する.
     *
     * @param String  $op 操作タイプ
     * @param String  $item_name 項目名
     * @param Array  &$search_defs 項目定義情報の配列
     * @param Array  &$errors エラーメッセージの配列
     */
    function initSearchInput($op, $item_name, &$search_defs, &$errors) {
        $ret = '';
        if ($op == 'search') {
            if (isset($_POST[$item_name]) && $_POST[$item_name] !== '') {
                $ret = $_POST[$item_name];
                $_SESSION['search_conds'][$item_name] = $ret;
            } elseif (isset($_GET[$item_name]) && $_GET[$item_name] !== '') {
                $ret = $_GET[$item_name];
                $_SESSION['search_conds'][$item_name] = $ret;
            }
        } elseif ($op == 'back_search') {
            if (isset($_SESSION['search_conds'][$item_name]) && $_SESSION['search_conds'][$item_name] !== '') {
                $ret = $_SESSION['search_conds'][$item_name];
            }
        }

        if ($search_defs[$item_name]['type'] == 'number') {
            // 数値の場合
            if (isset($ret) && $ret !== '') {
                // 整数の書式かどうか
                if ($search_defs[$item_name]['value_type'] == 'int' && !is_intval($ret)) {
                    $errors[] = sprintf(getMDConst('_INT_ERR_MSG'), $search_defs[$item_name]['caption']);
                    $search_defs[$item_name]['error'] = '<br />' . sprintf(getMDConst('_INT_ERR_MSG'), $search_defs[$item_name]['caption']);
                } elseif ($search_defs[$item_name]['value_type'] == 'float' && !is_floatval($ret)) {
                    // 小数の書式かどうか
                    if (!is_floatval($ret . '.0')) {
                        $errors[] = sprintf(getMDConst('_FLOAT_ERR_MSG'), $search_defs[$item_name]['caption']);
                        $search_defs[$item_name]['error'] = '<br />' . sprintf(getMDConst('_FLOAT_ERR_MSG'), $search_defs[$item_name]['caption']);
                    }
                }
            }
        } elseif ($search_defs[$item_name]['type'] == 'date') {
            // 日付の場合
            if (isset($ret) && $ret !== '') {
                if (!isValidDate($ret)) {
                    $errors[] = sprintf(getMDConst('_DATE_ERR_MSG'), $search_defs[$item_name]['caption']);
                    $search_defs[$item_name]['error'] = '<br />' . sprintf(getMDConst('_DATE_ERR_MSG'), $search_defs[$item_name]['caption']);
                }
            }
        }

        return $ret;
    }

    function getHistories($did) {
        global $his_tbl, $cfg_date_format, $cfg_time_format;
        $xoopsDB = &Database::getInstance();

        $histories = array();

        $sql = "SELECT hid, operation, update_uid, update_date FROM $his_tbl WHERE did = $did ORDER BY hid ASC";
        $res = $xoopsDB->query($sql);

        while (list($hid, $operation, $update_uid, $update_date) = $xoopsDB->fetchRow($res)) {
            $history['hid'] = $hid;
            $history['operation_raw'] = $operation;
            $history['operation'] = getOperation($operation);
            $history['update_uname'] = XoopsUser::getUnameFromId($update_uid);
            $history['update_uid'] = $update_uid;
            $history['update_date'] = date($cfg_date_format . ' ' . $cfg_time_format, strtotime($update_date));

            $histories[] = $history;
        }

        return $histories;
    }

    function getOperation($key) {
        if ($key == 'trans') return getMDConst('_TRANS');
        elseif ($key == 'add') return getMDConst('_ADD');
        elseif ($key == 'update') return getMDConst('_UPDATE');
        elseif ($key == 'delete') return getMDConst('_DELETE');
        else return '';
    }

    function getHisSearchDefs() {
        $ret = array();

        // 更新履歴ID
        $item = array();
        //        $item['caption'] = getMDConst('_HIS_ID');
        //        $item['type'] = 'text';
        //        $item['required'] = '0';
        //        $item['sequence'] = '0';
        //        $item['search'] = '1';
        //        $item['list'] = '1';
        //        $item['add'] = '0';
        //        $item['update'] = '0';
        //        $item['detail'] = '0';
        //        $item['site_search'] = '0';
        //        $item['duplicate'] = '0';
        //        $item['search_desc'] = '';
        //        $item['show_desc'] = '';
        //        $item['input_desc'] = '';
        //        $item['disp_cond'] = 0;
        //        $item['search_cond'] = 1;
        //        $item['value_range_min'] = 1;
        //        $item['value_range_max'] = 99999;
        //        $item['value_type'] = 'int';
        //        $item['default'] = '';
        //        $item['size'] = 10;
        //        $item['max_length'] = 10;
        //        $ret['hid'] = $item;

        // 処理内容
        $item = array();
        $item['caption'] = getMDConst('_OPERATION');
        $item['type'] = 'mselect';
        $item['required'] = '0';
        $item['sequence'] = '0';
        $item['search'] = '1';
        $item['list'] = '1';
        $item['add'] = '0';
        $item['update'] = '0';
        $item['detail'] = '0';
        $item['site_search'] = '0';
        $item['duplicate'] = '0';
        $item['search_desc'] = '';
        $item['show_desc'] = '';
        $item['input_desc'] = '';
        $item['disp_cond'] = 0;
        $item['search_cond'] = 0;

        $item['value_type'] = 'string';
        $item['default'] = array();
        $item['size'] = 4;
        $item['options'] = array(getMDConst('_TRANS') => 'trans', getMDConst('_ADD') => 'add', getMDConst('_UPDATE') => 'update', getMDConst('_DELETE') => 'delete');

        $ret['operation'] = $item;

        // 処理日時
        $item = array();
        $item['caption'] = getMDConst('_UPDATE_DATE');
        $item['type'] = 'date';
        $item['required'] = '0';
        $item['sequence'] = '0';
        $item['search'] = '1';
        $item['list'] = '1';
        $item['add'] = '0';
        $item['update'] = '0';
        $item['detail'] = '0';
        $item['site_search'] = '0';
        $item['duplicate'] = '0';
        $item['search_desc'] = '';
        $item['show_desc'] = '';
        $item['input_desc'] = '';
        $item['disp_cond'] = 0;
        $item['search_cond'] = 0;

        $item['default'] = '';

        $ret['update_date'] = $item;

        // 処理日時(以降)
        $ret['update_date_or_over'] = $item;
        $ret['update_date_or_over']['is_range_item'] = true;
        $ret['update_date_or_over']['caption'] = getMDConst('_UPDATE_DATE') . '(' . getMDConst('_SINCE') . ')';

        // 処理日時(以前)
        $ret['update_date_or_less'] = $item;
        $ret['update_date_or_less']['is_range_item'] = true;
        $ret['update_date_or_less']['caption'] = getMDConst('_UPDATE_DATE') . '(' . getMDConst('_UNTIL') . ')';

        return $ret;
    }

    /**
     * GD(gif、jpeg、png)をサポートしているかどうかを返す.
     *
     * @return Boolean GD(gif、jpeg、png)をサポートしている場合はtrue
     */
    function checkGDSupport() {
        $gd_infos = gd_info();
        if (!$gd_infos['GIF Read Support'] || !$gd_infos['GIF Create Support']) {
            return false;
        }

        if (isset($gd_infos['JPG Support']) && !$gd_infos['JPG Support']) {
            return false;
        }

        if (isset($gd_infos['JPEG Support']) && !$gd_infos['JPEG Support']) {
            return false;
        }

        if (!$gd_infos['PNG Support']) {
            return false;
        }

        return true;
    }
}
?>
<?php

if (!defined('_XGDB_FUNCTIONS_INCLUDED')) {
    define('_XGDB_FUNCTIONS_INCLUDED', true);

    /**
     * �ƥ����ȥܥå�����input��������������.
     *
     * @param string $name name°������f
     * @param array $item_def ���ܤ��������?
     * @param string $default �����?
     * @return string �ƥ����ȥܥå�����input����
     */
    function makeTextForm($name, $item_def, $default)
    {
        $myts = MyTextSanitizer::getInstance();

        $ret = '<input type="text" name="' . $myts->htmlSpecialChars($name) . '" size="' . intval($item_def['size']) . '" maxlength="' . intval($item_def['max_length']) . '" value="' . $myts->htmlSpecialChars($default) . '" />';

        return $ret;
    }

    /**
     * �����å��ܥå�����input��������������.
     *
     * @param string $name name°������
     * @param array $item_def ���ܤ��������?
     * @param array $defaults �����?
     * @return string �����å��ܥå�����input����
     */
    function makeCboxForm($name, $item_def, $defaults)
    {
        $myts = MyTextSanitizer::getInstance();

        if (!is_array($defaults)) {
            $defaults = string2array($defaults);
        }
        $ret = '';

        foreach ($item_def['options'] as $key => $value) {
            $ret .= '<label style="margin-right: 1em;"><input type="checkbox" name="' . $myts->htmlSpecialChars($name) . '[]" value="' . $myts->htmlSpecialChars($value) . '"';
            foreach ($defaults as $default) {
                if ($default == $value) {
                    $ret .= ' checked';
                }
            }
            $ret .= '/>' . $myts->htmlSpecialChars($key) . '</label>';

            if ($item_def['option_br']) {
                $ret .= '<br />';
            }
        }
        if ('' !== $ret && '<br />' == mb_substr($ret, -6)) {
            $ret = mb_substr($ret, 0, -6);
        }

        return $ret;
    }

    /**
     * �饸���ܥ����?nput��������������.
     *
     * @param string $name name°������
     * @param array $item_def ���ܤ��������?
     * @param string $default �����?
     * @return string �饸���ܥ����?nput����
     */
    function makeRadioForm($name, $item_def, $default)
    {
        $myts = MyTextSanitizer::getInstance();

        $ret = '';

        foreach ($item_def['options'] as $key => $value) {
            $ret .= '<label style="margin-right: 1em;"><input type="radio" name="' . $myts->htmlSpecialChars($name) . '" value="' . $myts->htmlSpecialChars($value) . '"';
            if ($default == $value) {
                $ret .= ' checked';
            }
            $ret .= '/>' . $myts->htmlSpecialChars($key) . '</label>';

            if ($item_def['option_br']) {
                $ret .= '<br />';
            }
        }
        if ('' !== $ret && '<br />' == mb_substr($ret, -6)) {
            $ret = mb_substr($ret, 0, -6);
        }

        return $ret;
    }

    /**
     * �ץ�������˥塼��?elect��������������.
     *
     * @param string $name name°������
     * @param array $item_def ���ܤ��������?
     * @param string $default �����?
     * @return string �ץ�������˥塼��?elect����
     */
    function makeSelectForm($name, $item_def, $default)
    {
        global $affix;
        $myts = MyTextSanitizer::getInstance();

        $not_selected_ary = [constant('_' . $affix . '_NOT_SELECTED') => ''];
        $item_def['options'] = $not_selected_ary + $item_def['options'];
        $ret = '<select name="' . $myts->htmlSpecialChars($name) . '">';

        foreach ($item_def['options'] as $key => $value) {
            $ret .= '<option value="' . $myts->htmlSpecialChars($value) . '"';
            if ($default === (string) $value) {
                $ret .= ' selected="selected"';
            }
            $ret .= '>' . $myts->htmlSpecialChars($key) . '</option>';
        }
        $ret .= '</select>';

        return $ret;
    }

    /**
     * �ꥹ�ȥܥå�����select��������������.
     *
     * @param string $name name°������
     * @param array $item_def ���ܤ��������?
     * @param array $defaults �����?
     * @return string �ꥹ�ȥܥå�����select����
     */
    function makeMSelectForm($name, $item_def, $defaults)
    {
        $myts = MyTextSanitizer::getInstance();

        if (!is_array($defaults)) {
            $defaults = string2array($defaults);
        }
        $ret = '<select name="' . $myts->htmlSpecialChars($name) . '[]" size="' . intval($item_def['size']) . '" multiple="multiple">';

        foreach ($item_def['options'] as $key => $value) {
            $ret .= '<option value="' . $myts->htmlSpecialChars($value) . '"';
            if (in_array($value, $defaults, true)) {
                $ret .= ' selected="selected"';
            }
            $ret .= '>' . $myts->htmlSpecialChars($key) . '</option>';
        }
        $ret .= '</select>';

        return $ret;
    }

    /**
     * �ƥ����ȥ��ꥢ��textarea��������������.
     *
     * @param string $name name°������
     * @param array $item_def ���ܤ��������?
     * @param string $default �����?
     * @return string �ƥ����ȥ��ꥢ��textarea����
     */
    function makeTAreaForm($name, $item_def, $default)
    {
        $myts = MyTextSanitizer::getInstance();

        $ret = '<textarea name="' . $myts->htmlSpecialChars($name) . '" rows="' . intval($item_def['rows']) . '" cols="' . intval($item_def['cols']) . '">' . sanitize($default, $item_def) . '</textarea>';

        return $ret;
    }

    /**
     * BB�������б��ƥ����ȥ��ꥢ��textarea��������������.
     *
     * @param string $name name°������
     * @param array $item_def ���ܤ��������?
     * @param string $default �����?
     * @return string BB�������б��ƥ����ȥ��ꥢ��textarea����
     */
    function makeXTAreaForm($name, $item_def, $default)
    {
        $myts = MyTextSanitizer::getInstance();

        $form = new XoopsFormDhtmlTextArea($myts->htmlSpecialChars($name), $myts->htmlSpecialChars($name), sanitize($default, $item_def), intval($item_def['rows']), intval($item_def['cols']));
        $ret = $form->render();

        return $ret;
    }

    /**
     * ���դ�input��������������.
     *
     * @param string $name name°������
     * @param array $item_def ���ܤ��������?
     * @param string $default �����?
     * @return string ���դ�input����
     */
    function makeDateForm($name, $item_def, $default)
    {
        $myts = MyTextSanitizer::getInstance();

        $form = new XoopsFormTextDateSelect($myts->htmlSpecialChars($name), $myts->htmlSpecialChars($name), 15, $myts->htmlSpecialChars($default));
        $ret = $form->render();

        return $ret;
    }

    /**
     * �ե����륢�å�?�ɤ�input��������������.
     *
     * @param string $name name°������
     * @param array $item_def ���ܤ��������?
     * @return string �ե����륢�å�?�ɤ�input
     */
    function makeFileForm($name, $item_def)
    {
        $myts = MyTextSanitizer::getInstance();
        $ret = '<input type="file" name="' . $myts->htmlSpecialChars($name) . '" size="' . intval($item_def['size']) . ' maxlength="' . intval($item_def['max_length']) . '"  />';

        return $ret;
    }

    /**
     * �������Υ饸���ܥ����?nput��������������.
     *
     * @param string $name name°������
     * @param array $options ����������
     * @param string $default �����?
     * @return string �饸���ܥ����?nput����
     */
    function makeCondForm($name, $options, $default)
    {
        global $affix;
        $myts = MyTextSanitizer::getInstance();

        $ret = '<br />' . getMDConst('_COND_LABEL');

        foreach ($options as $key => $value) {
            $ret .= '<label style="margin-right: 1em;"><input type="radio" name="' . $myts->htmlSpecialChars($name) . '" value="' . $myts->htmlSpecialChars($value) . '"';
            if ($default == $value) {
                $ret .= ' checked';
            }
            $ret .= '/>' . $myts->htmlSpecialChars($key) . '</label>';
        }

        return $ret;
    }

    /**
     * ���롼�פΥ��쥯�ȥܥå����˻��Ѥ��륰�롼��̾�ȥ��롼��ID��ʸ�������������?
     *
     * @return string ���롼�פΥ��쥯�ȥܥå����˻��Ѥ��륰�롼��̾�ȥ��롼��ID��ʸ����
     */
    function makeGroupSelectOptions()
    {
        $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();
        $ret = '';

        $sql = 'SELECT groupid, name FROM ' . $xoopsDB->prefix('groups') . ' ORDER BY groupid ASC';
        $res = $xoopsDB->query($sql);
        $groups_ary = [];
        while ([$gid, $gname] = $xoopsDB->fetchRow($res)) {
            $groups_ary[$gid] = $gname;
        }

        foreach ($groups_ary as $gid => $gname) {
            $ret .= "$gname|$gid\n";
        }

        if ('' !== $ret) {
            $ret = mb_substr($ret, 0, -1);
        }

        return $ret;
    }

    /**
     * ���롼��ID��ʸ����Υꥹ�Ȥ���ԺѤߤΥ��롼��̾��ʸ������Ѵ�����?
     *
     * @param string $gidstring ���롼��ID��ʸ����Υꥹ��?
     * @return string ���ԺѤߤΥ��롼��̾��ʸ����
     */
    function gidstring2brgroup($gidstring)
    {
        if (!isset($gidstring) || '' === $gidstring) {
            return '';
        }

        $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();
        $myts = MyTextSanitizer::getInstance();
        $ret = '';

        $sql = 'SELECT groupid, name FROM ' . $xoopsDB->prefix('groups') . ' ORDER BY groupid ASC';
        $res = $xoopsDB->query($sql);
        $groups_ary = [];
        while ([$gid, $gname] = $xoopsDB->fetchRow($res)) {
            $groups_ary[$gid] = $gname;
        }

        $gid_ary = string2array($gidstring);
        foreach ($gid_ary as $gid) {
            if ('' === $gid) {
                continue;
            }
            $ret .= $myts->htmlSpecialChars($groups_ary[$gid]) . '<br />';
        }

        return $ret;
    }

    /**
     * ���ꤷ���ǥ��쥯�ȥ���ǥ�ˡ����ˤʤ������ʥե�����̾���֤�.
     *
     * @param string $ext �ե�����γ�ĥ��?
     * @param string $target_dirpath �ǥ��쥯�ȥ�Υե�ѥ�
     * @return string �ե�����̾(�ǥ��쥯�ȥ�Υѥ��ϴޤޤʤ�?
     */
    function getUniqueFileName($ext, $target_dirpath)
    {
        $file_name = md5(XOOPS_SALT . uniqid(mt_rand(), true)) . '.' . $ext;
        if (file_exists($target_dirpath . $file_name)) {
            $file_name = getUniqueFileName($ext, $target_dirpath);
        }

        return $file_name;
    }

    /**
     * �ե�����νĤȲ�����?�����ꥵ��������.
     *
     * @param string $file_name �ե�����̾(�ե�����Υѥ���ޤ�)
     * @param string $max_image_size ����ե����륵����?px)
     * @return string �ꥵ���������ե�����γ�ĥ�ҡ��ꥵ�������ʤ��ä����϶�ʸ��?
     */
    function resizeImage($file_name, $max_image_size)
    {
        if (!extension_loaded('gd')) {
            return '';
        }
        $gd_infos = gd_info();

        [$bef_x, $bef_y, $type] = getimagesize($file_name);
        if ($bef_x > $max_image_size || $bef_y > $max_image_size) {
            switch ($type) {
case 1:

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                if (!$gd_infos['GIF Read Support'] || !$gd_infos['GIF Create Support']) {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    return '';
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                }
                $bef_img = imagecreatefromgif($file_name);

                break;
            case 2:

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                if (isset($gd_infos['JPG Support']) && !$gd_infos['JPG Support']) {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    return '';
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                }
                if (isset($gd_infos['JPEG Support']) && !$gd_infos['JPEG Support']) {
                    return '';
                }
                $bef_img = imagecreatefromjpeg($file_name);

                break;
            case 3:

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                if (!$gd_infos['PNG Support']) {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    return '';
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                }
                $bef_img = imagecreatefrompng($file_name);

                break;
            }

            if ($bef_x > $bef_y) {
                $aft_x = $max_image_size;
                $aft_y = $bef_y * ($max_image_size / $bef_x);
            } else {
                $aft_x = $bef_x * ($max_image_size / $bef_y);
                $aft_y = $max_image_size;
            }

            $aft_img = imagecreatetruecolor($aft_x, $aft_y);
            imagecopyresampled($aft_img, $bef_img, 0, 0, 0, 0, $aft_x, $aft_y, $bef_x, $bef_y);
            imagedestroy($bef_img);

            switch ($type) {
case 1:

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 imagegif($aft_img, $file_name);
                imagedestroy($aft_img);

                return 'gif';
            case 2:

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 imagejpeg($aft_img, $file_name);
                imagedestroy($aft_img);

                return 'jpg';
            case 3:

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 imagepng($aft_img, $file_name);
                imagedestroy($aft_img);

                return 'png';
            }
        }

        return '';
    }

    /**
     * ���¤�����å�����?
     *
     * @param array $user_groups �桼��������°���륰�롼�פΥ��롼��ID������
     * @param array $perm_groups ���¤��ĥ��롼�פΥ��롼��ID������
     * @return bool ���¤��������?rue���ʤ�����false
     */
    function checkPerm($user_groups, $perm_groups)
    {
        foreach ($user_groups as $gid) {
            if (in_array($gid, $perm_groups, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * ���ꤷ�����롼��ID�Υ��롼�פ�°����桼������?oopsUserObject��������������.
     *
     * @param array ���롼��ID������
     * @param mixed $gids
     * @return array �桼������XoopsUserObject������
     */
    function getUsers($gids)
    {
        $ret = [];

        foreach ($gids as $gid) {
            $member_handler = xoops_gethandler('member');
            $users = $member_handler->getUsersByGroup($gid, true);
            foreach ($users as $user) {
                $ret[$user->getVar('uid')] = $user;
            }
        }

        return $ret;
    }

    /**
     * stripslashes�ؿ��ǺƵ�Ū�˽�����֤�?
     *
     * @param $value stripslashes�ؿ��ǽ������?
     * @return String/Array �����?tripslashes�ؿ��ǽ�����
     */
    function stripSlashesDeep($value)
    {
        if (is_array($value)) {
            $value = array_map('stripSlashesDeep', $value);
        } else {
            $value = stripslashes($value);
        }

        return $value;
    }

    /**
     * �������ڤ�ʸ������Ѵ�����?
     *
     * @param array $array ����
     * @param string $sep ���ڤ�ʸ��(����͡�?)
     *
     * @return string ʸ����
     */
    function array2string($array, $sep = '|')
    {
        if (!is_array($array) && '' == $array) {
            return '';
        }
        $ret = '';

        foreach ($array as $value) {
            $ret .= $value . $sep;
        }
        if ('' != $ret) {
            $ret = mb_substr($ret, 0, -1 * mb_strlen($sep));
        }

        return $ret;
    }

    /**
     * �������Զ��ڤ�ʸ������Ѵ�����?
     *
     * @param string $array ����
     *
     * @return string ���Զ��ڤ�ʸ����
     */
    function array2brstring($array)
    {
        $ret = '';

        foreach ($array as $key => $value) {
            $key .= '';
            $value .= '';
            if ($key !== $value) {
                $ret .= $key . '|';
            }
            $ret .= $value;
            $ret .= '<br />';
        }

        if ('' !== $ret) {
            $ret = mb_substr($ret, 0, -6);
        }

        return $ret;
    }

    /**
     * ���ڤ�ʸ�����������Ѵ�����.
     *
     * @param string $string ʸ����
     * @param string $sep ���ڤ�ʸ��(����͡�?)
     *
     * @return array ����
     */
    function string2array($string, $sep = '|')
    {
        if ('' != $string) {
            return explode($sep, $string);
        }

        return [];
    }

    /**
     * ���Զ��ڤ�ʸ�����������Ѵ�����.
     *
     * @param string $string ʸ����
     * @param string $sep ���ڤ�ʸ��(����͡�?)
     *
     * @return array ����
     */
    function nl2array($string, $sep = '|')
    {
        if ('' === $string) {
            return [];
        }

        $myts = MyTextSanitizer::getInstance();

        if (function_exists('mb_ereg_replace')) {
            $string = mb_ereg_replace("\r\n", "\n", $string);
            $string = mb_ereg_replace("\r", "\n", $string);
        } else {
            $string = ereg_replace("\r\n", "\n", $string);
            $string = ereg_replace("\r", "\n", $string);
        }
        $strings = explode("\n", $string);

        $ret = [];
        foreach ($strings as $value) {
            if (mb_strpos($value, $sep)) {
                [$key, $value] = explode($sep, $value);
                $ret[$myts->htmlSpecialChars($key)] = $myts->htmlSpecialChars($value);
            } else {
                $value = $myts->htmlSpecialChars($value);
                $ret[$value] = $value;
            }
        }

        return $ret;
    }

    /**
     * ���롼��ID��WHERE�����������?
     *
     * @param array $gids ���롼��ID������
     * @param string $as xgdb_item�ơ��֥�����?
     * @return string ���롼��ID��WHERE��
     */
    function makeWhereGID($gids, $as = '')
    {
        $ret = '(';

        foreach ($gids as $gid) {
            if ($as) {
                $ret .= "($as.show_gids LIKE '%|$gid|%') OR ";
            } else {
                $ret .= "(show_gids LIKE '%|$gid|%') OR ";
            }
        }
        $ret = mb_substr($ret, 0, -4);

        $ret .= ')';

        return $ret;
    }

    /**
     * ���٤Ƥι��ܤξ�����֤�?
     *
     * @param mixed $dirname
     * @param mixed $gids
     * @return array ���ܾ��������?
     */
    function getItemDefs($dirname, $gids)
    {
        //global $gids;
        $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();
        $myts = MyTextSanitizer::getInstance();

        $ret = [];
        $sql = 'SELECT * FROM ' . $xoopsDB->prefix($dirname . '_xgdb_item') . ' WHERE ' . makeWhereGID($gids) . ' ORDER BY `sequence` ASC, `iid` ASC';
        $res = $xoopsDB->query($sql);
        while ($row = $xoopsDB->fetchArray($res)) {
            $item = [];
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
            if ('text' == $row['type']) {
                $item['value_type'] = $myts->htmlSpecialChars($row['value_type']);
                $item['default'] = $myts->htmlSpecialChars($row['default']);
                $item['size'] = $row['size'];
                $item['max_length'] = $row['max_length'];
            } elseif ('number' == $row['type']) {
                $item['value_type'] = $myts->htmlSpecialChars($row['value_type']);
                if ('' !== $row['value_range_min']) {
                    $item['value_range_min'] = $row['value_range_min'];
                }
                if ('' !== $row['value_range_max']) {
                    $item['value_range_max'] = $row['value_range_max'];
                }
                $item['default'] = $myts->htmlSpecialChars($row['default']);
                $item['size'] = $row['size'];
                $item['max_length'] = $row['max_length'];
            } elseif ('cbox' == $row['type']) {
                $item['value_type'] = $myts->htmlSpecialChars($row['value_type']);
                $item['default'] = nl2array($row['default']);
                $item['options'] = nl2array($row['options']);
                $item['option_br'] = $row['option_br'];
            } elseif ('radio' == $row['type']) {
                $item['value_type'] = $myts->htmlSpecialChars($row['value_type']);
                $item['default'] = $myts->htmlSpecialChars($row['default']);
                $item['options'] = nl2array($row['options']);
                $item['option_br'] = $row['option_br'];
            } elseif ('select' == $row['type']) {
                $item['value_type'] = $myts->htmlSpecialChars($row['value_type']);
                $item['default'] = $myts->htmlSpecialChars($row['default']);
                $item['size'] = 5;
                $item['options'] = nl2array($row['options']);
            } elseif ('mselect' == $row['type']) {
                $item['value_type'] = $myts->htmlSpecialChars($row['value_type']);
                $item['default'] = nl2array($row['default']);
                $item['size'] = $row['size'];
                $item['options'] = nl2array($row['options']);
            } elseif ('tarea' == $row['type'] || 'xtarea' == $row['type']) {
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
            } elseif ('file' == $row['type'] || 'image' == $row['type']) {
                $item['default'] = '';
                $item['size'] = $row['size'];
                $item['max_length'] = $row['max_length'];
                $item['max_file_size'] = $row['max_file_size'];
                if ('image' == $row['type']) {
                    $item['max_image_size'] = $row['max_image_size'];
                }
                $item['allowed_exts'] = nl2array($row['allowed_exts']);
                $item['allowed_mimes'] = nl2array($row['allowed_mimes']);
            } elseif ('date' == $row['type']) {
                $item['default'] = '';
            }

            $ret[$myts->htmlSpecialChars($row['name'])] = $item;
        }

        return $ret;
    }

    /**
     * ���ܾ������?����ꤷ��?ype�˰��פ�����ܾ�����֤�.
     *
     * @param array $defs ���ܾ��������?
     * @param string $type ��������type�μ���
     *
     * @return array ���ܾ��������?
     */
    function getDefs($defs, $type)
    {
        global $cfg_id_caption;
        $ret = [];

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
                if ('search' == $type && ('number' == $def['type'] || 'date' == $def['type'])) {
                    $ret[$index . '_or_over'] = $def;
                    $ret[$index . '_or_over']['is_range_item'] = true;
                    if ('number' == $def['type']) {
                        $ret[$index . '_or_over']['caption'] = $def['caption'] . '(' . getMDConst('_OR_OVER') . ')';
                    } elseif ('date' == $def['type']) {
                        $ret[$index . '_or_over']['caption'] = $def['caption'] . '(' . getMDConst('_SINCE') . ')';
                    }

                    $ret[$index . '_or_less'] = $def;
                    $ret[$index . '_or_less']['is_range_item'] = true;
                    if ('number' == $def['type']) {
                        $ret[$index . '_or_less']['caption'] = $def['caption'] . '(' . getMDConst('_OR_LESS') . ')';
                    } elseif ('date' == $def['type']) {
                        $ret[$index . '_or_less']['caption'] = $def['caption'] . '(' . getMDConst('_UNTIL') . ')';
                    }
                }
            }
        }

        return $ret;
    }

    /**
     * ���ܾ�������Ƥ˽��äơ�������ͤ򥵥˥����������֤�.
     *
     * @param string $value ���˥������оݤ���
     * @param array  $item_def ���ܾ���
     * @param bool $number_format ���ͽ񼰥ե����ޥå�
     *
     * @return string ���˥������������?value
     */
    function sanitize($value, $item_def, $number_format = true)
    {
        if ('' === $value || null === $value) {
            return '';
        }

        $myts = MyTextSanitizer::getInstance();
        global $cfg_date_format;

        if ('number' == $item_def['type']) {
            if ('int' == $item_def['value_type']) {
                $value = intval($value);
                if ($number_format) {
                    $value = number_format($value);
                }
            } elseif ('float' == $item_def['value_type']) {
                $value = floatval($value);
                if (false === mb_strpos($value, '.')) {
                    $value .= '.0';
                }
                if ($number_format) {
                    $value = number_format($value, mb_strlen($value) - intval(mb_strpos($value, '.')) - 1);
                } else {
                    $value = number_format($value, mb_strlen($value) - intval(mb_strpos($value, '.')) - 1, '.', '');
                }
            }
        } elseif ('tarea' == $item_def['type'] || 'xtarea' == $item_def['type']) {
            if (!$item_def['html']) {
                $value = $myts->htmlSpecialChars($value);
            }
        } elseif ('file' == $item_def['type'] || 'image' == $item_def['type']) {
            $value = $myts->htmlSpecialChars($value);
        } elseif ('date' == $item_def['type']) {
            $value = date($cfg_date_format, strtotime($value));
        } else {
            // text,cbox,radio,select,mselect
            $value = $myts->makeClickable($myts->htmlSpecialChars($value));
        }

        return $value;
    }

    /**
     * �⥸�塼��Υƥ�ץ졼�ȥե�����򹹿�����?
     *
     * @param string  $tpl_set      �ƥ�ץ졼�ȥ��å��?
     * @param string  $tpl_file     �ƥ�ץ졼�ȥե������?
     * @param string  $tpl_source   �ƥ�ץ졼�ȥե�����Υ����������ɤ�����
     * @param int $lastmodified �ǽ���������Υ����ॹ�����
     */
    function updateTemplate($tpl_set, $tpl_file, $tpl_source, $lastmodified = 0)
    {
        include_once XOOPS_ROOT_PATH . '/class/template.php';
        $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();
        $tplfile_tbl = $xoopsDB->prefix('tplfile');
        $tplsource_tbl = $xoopsDB->prefix('tplsource');

        $sql = "SELECT * FROM $tplfile_tbl WHERE tpl_tplset = 'default' AND tpl_file = '" . addslashes($tpl_file) . "'";
        $res = $xoopsDB->query($sql);
        if (0 == $xoopsDB->getRowsNum($res)) {
            return;
        }

        $tpl_id_sql = "SELECT tpl_id FROM $tplfile_tbl WHERE tpl_tplset = '" . addslashes($tpl_set) . "' AND tpl_file = '" . addslashes($tpl_file) . "'";
        $tpl_id_res = $xoopsDB->query($tpl_id_sql);

        if ('default' != $tpl_set && 0 == $xoopsDB->getRowsNum($tpl_id_res)) {
            while ($row = $xoopsDB->fetchArray($res)) {
                $xoopsDB->queryF("INSERT INTO $tplfile_tbl SET tpl_refid = '" . addslashes($row['tpl_refid']) . "',tpl_module = '" . addslashes($row['tpl_module']) . "',tpl_tplset = '" . addslashes($tpl_set) . "',tpl_file = '" . addslashes($tpl_file) . "',tpl_desc = '" . addslashes($row['tpl_desc']) . "',tpl_type = '" . addslashes($row['tpl_type']) . "'");
                $tpl_id = $xoopsDB->getInsertId();
                $xoopsDB->queryF("INSERT INTO $tplsource_tbl SET tpl_id = '$tpl_id', tpl_source = ''");
            }
        }

        while ([$tpl_id] = $xoopsDB->fetchRow($tpl_id_res)) {
            $xoopsDB->queryF("UPDATE $tplfile_tbl SET tpl_lastmodified = '" . addslashes($lastmodified) . "',tpl_lastimported=UNIX_TIMESTAMP() WHERE tpl_id = '$tpl_id'");
            $xoopsDB->queryF("UPDATE $tplsource_tbl SET tpl_source = '" . addslashes($tpl_source) . "' WHERE tpl_id = '$tpl_id'");
            $error_reporting = error_reporting(0);
            xoops_template_touch($tpl_id);
            error_reporting($error_reporting);
        }
    }

    /**
     * �Ǿ��ͤȺ����ͤ��ϰϤ򤢤�魯ʸ����ɽ�����֤�?
     *
     * @param string  $value_range_min �Ǿ���
     * @param string  $value_range_max ������
     *
     * @return string �Ǿ��ͤȺ����ͤ��ϰ�
     */
    function getRangeText($value_range_min, $value_range_max)
    {
        global $affix;
        $ret = '';
        if (isset($value_range_min)) {
            $ret .= $value_range_min . constant('_' . $affix . '_MORE_THAN');
        }
        if (isset($value_range_max)) {
            if ('' !== $ret) {
                $ret .= constant('_' . $affix . '_COMMA');
            }
            $ret .= $value_range_max . constant('_' . $affix . '_LESS_THAN');
        }

        return $ret;
    }

    /**
     * �ͤ������ͤ��ɤ���Ƚ�ꤹ��.
     *
     * @param string  $value ��
     *
     * @return bool �����ͤξ��?rue������ʳ��ξ��false
     */
    function is_intval($value)
    {
        if (!isset($value) || '' === $value) {
            return false;
        } elseif (!is_numeric($value)) {
            return false;
        } elseif (false !== mb_strpos($value, '.')) {
            return false;
        }

        return true;
    }

    /**
     * �ͤ������ͤ��ɤ���Ƚ�ꤹ��.
     *
     * @param string  $value ��
     *
     * @return bool �����ͤξ��?rue������ʳ��ξ��false
     */
    function is_floatval($value)
    {
        if (!isset($value) || '' === $value) {
            return false;
        } elseif (!is_numeric($value)) {
            return false;
        } elseif (false === mb_strpos($value, '.')) {
            return false;
        } elseif (!is_numeric(mb_substr($value, -1))) {
            return false;
        } elseif (0 === mb_strpos($value, '.')) {
            return false;
        } elseif (!is_numeric(mb_substr($value, mb_strpos($value, '.') - 1, 1))) {
            return false;
        }

        return true;
    }

    /**
     * ��¸����Ƥ���ե�����̾���֤�.
     *
     * @param string  $did �ǡ���ID
     * @param string  $col_name ��̾
     * @param string  $file_name �ơ��֥��Υե�����̾
     * @return string ��¸����Ƥ���ե�����̾
     */
    function getRealFileName($did, $col_name, $file_name)
    {
        return urlencode("$did-$col_name-$file_name");
    }

    /**
     * ����ե����������֤�?
     *
     * @param string  $filename ����ե���������Хѥ�
     * @param string  $cfg_width ����ե���������������?
     * @return int ����ե��������
     */
    function getImageWidth($filename, $cfg_width)
    {
        [$x, $y, $type] = getimagesize($filename);

        if ($x > $cfg_width) {
            $ret = $cfg_width;
        } else {
            return $ret = $x;
        }

        return $ret;
    }

    /**
     * �ϰϤ��Ĺ��ܤ����֤�.
     *
     * @param string  $item_name ����̾
     * @return bool �ϰϤ��Ĺ��ܤξ��?rue���ϰϤ��ʤ����ܤξ��?alse
     */
    function isRangeItemName($item_name)
    {
        if (8 < mb_strlen($item_name)) {
            if ('_or_over' == mb_substr($item_name, -8) || '_or_less' == mb_substr($item_name, -8)) {
                return true;
            }
        }

        return false;
    }

    /**
     * ���ͤ��ϰ���˼�ޤäƤ��뤫���֤�.
     *
     * @param array  $item_def ���ܤ��������?
     * @param array  $item_def ���ܤ��������?
     * @param mixed $value
     * @return int -1��̤����0���ϰ��⡢1��Ķ��
     */
    function checkNumberRange($item_def, $value)
    {
        if ('int' == $item_def['value_type']) {
            if (isset($item_def['value_range_min']) && '' !== $item_def['value_range_min']) {
                if (intval($value) < intval($item_def['value_range_min'])) {
                    return -1;
                }
            } elseif (isset($item_def['value_range_max']) && '' !== $item_def['value_range_max']) {
                if (intval($value) > intval($item_def['value_range_max'])) {
                    return 1;
                }
            }
        } else {
            if (isset($item_def['value_range_min']) && '' !== $item_def['value_range_min']) {
                if (floatval($value) < floatval($item_def['value_range_min'])) {
                    return -1;
                }
            } elseif (isset($item_def['value_range_max']) && '' !== $item_def['value_range_max']) {
                if (floatval($value) > floatval($item_def['value_range_max'])) {
                    return 1;
                }
            }
        }

        return 0;
    }

    /**
     * �⥸�塼��ե��Ȳ�����?_MD_)�Ѥ�������֤�?
     *
     * @param string  $const_name ����?
     * @return string �����?
     */
    function getMDConst($const_name)
    {
        global $affix;

        return constant('_MD_' . $affix . $const_name);
    }

    /**
     * �����ѤΥե��������������?
     *
     * @param array  &$item_defs ����������������
     */
    function makeInputForms(&$item_defs)
    {
        foreach ($item_defs as $item_name => $item_def) {
            if ('text' == $item_def['type'] || 'number' == $item_def['type']) {
                $item_defs[$item_name]['value'] = makeTextForm($item_name, $item_def, $item_defs[$item_name]['raw']);
            } elseif ('cbox' == $item_def['type']) {
                $item_defs[$item_name]['value'] = makeCboxForm($item_name, $item_def, $item_defs[$item_name]['raw']);
            } elseif ('radio' == $item_def['type']) {
                $item_defs[$item_name]['value'] = makeRadioForm($item_name, $item_def, $item_defs[$item_name]['raw']);
            } elseif ('select' == $item_def['type']) {
                $item_defs[$item_name]['value'] = makeSelectForm($item_name, $item_def, $item_defs[$item_name]['raw']);
            } elseif ('mselect' == $item_def['type']) {
                $item_defs[$item_name]['value'] = makeMSelectForm($item_name, $item_def, $item_defs[$item_name]['raw']);
            } elseif ('tarea' == $item_def['type']) {
                $item_defs[$item_name]['value'] = makeTAreaForm($item_name, $item_def, $item_defs[$item_name]['raw']);
            } elseif ('xtarea' == $item_def['type']) {
                $item_defs[$item_name]['value'] = makeXTAreaForm($item_name, $item_def, $item_defs[$item_name]['raw']);
            } elseif ('file' == $item_def['type'] || 'image' == $item_def['type']) {
                $item_defs[$item_name]['value'] = makeFileForm($item_name, $item_def);
            } elseif ('date' == $item_def['type']) {
                $item_defs[$item_name]['value'] = makeDateForm($item_name, $item_def, $item_defs[$item_name]['raw']);
            }
        }
    }

    /**
     * �����ͤ����?
     *
     * @param array  $item_def �����������?
     * @param string  $item_name ����̾
     * @param array  &$item_defs ����������������
     * @param array  &$uploaded_file_defs ���å�?�ɥե�����ι���̾������?
     * @param array  &$errors ���顼��å�����������?
     * @param string  $type ����Υ�����?
     */
    function initInput($item_def, $item_name, &$item_defs, &$uploaded_file_defs, &$errors, $type)
    {
        $myts = MyTextSanitizer::getInstance();

        $ret = '';
        if ($item_def[$type]) {
            // �ե����롢����ξ��
            if ('file' == $item_def['type'] || 'image' == $item_def['type']) {
                if (isset($_FILES[$item_name]['tmp_name']) && '' !== $_FILES[$item_name]['tmp_name']) {
                    if (!in_array($_FILES[$item_name]['type'], $item_def['allowed_mimes'], true)) {
                        $errors[] = sprintf(getMDConst('_FILE_TYPE_ERR_MSG'), $myts->htmlSpecialChars($_FILES[$item_name]['type']), $item_def['caption']);
                        $item_defs[$item_name]['error'] = '<br />' . sprintf(getMDConst('_FILE_TYPE_ERR_MSG'), $myts->htmlSpecialChars($_FILES[$item_name]['type']), $item_def['caption']);
                    } elseif (!in_array(pathinfo($_FILES[$item_name]['name'], PATHINFO_EXTENSION), $item_def['allowed_exts'], true)) {
                        $errors[] = sprintf(getMDConst('_FILE_EXT_ERR_MSG'), $myts->htmlSpecialChars(pathinfo($_FILES[$item_name]['name'], PATHINFO_EXTENSION)), $item_def['caption']);
                        $item_defs[$item_name]['error'] = '<br />' . sprintf(getMDConst('_FILE_EXT_ERR_MSG'), $myts->htmlSpecialChars(pathinfo($_FILES[$item_name]['name'], PATHINFO_EXTENSION)), $item_def['caption']);
                    } elseif (($item_def['max_file_size'] * 1024 * 1024) < $_FILES[$item_name]['size']) {
                        $errors[] = sprintf(getMDConst('_FILE_SIZE_ERR_MSG'), $item_def['caption']);
                        $item_defs[$item_name]['error'] = '<br />' . sprintf(getMDConst('_FILE_SIZE_ERR_MSG'), $item_def['caption']);
                    } else {
                        $ret = $_FILES[$item_name]['name'];
                        $uploaded_file_defs[$item_name] = $item_def;
                    }
                } else {
                    if ('add' === $type && $item_def['required']) {
                        $errors[] = sprintf(getMDConst('_REQ_ERR_MSG'), $item_def['caption']);
                        $item_defs[$item_name]['error'] = '<br />' . sprintf(getMDConst('_REQ_ERR_MSG'), $item_def['caption']);
                    }
                }
            } elseif ('number' == $item_def['type']) {
                // ���ͤξ��?
                if (isset($_POST[$item_name]) && '' !== $_POST[$item_name]) {
                    $ret = $_POST[$item_name];
                    // �����ν񼰤��ɤ���
                    if ('int' == $item_def['value_type'] && !is_intval($ret)) {
                        $errors[] = sprintf(getMDConst('_INT_ERR_MSG'), $item_def['caption']);
                        $item_defs[$item_name]['error'] = '<br />' . sprintf(getMDConst('_INT_ERR_MSG'), $item_def['caption']);
                    } elseif ('float' == $item_def['value_type'] && !is_floatval($ret)) {
                        // �����ν񼰤��ɤ���
                        if (!is_floatval($ret . '.0')) {
                            $errors[] = sprintf(getMDConst('_FLOAT_ERR_MSG'), $item_def['caption']);
                            $item_defs[$item_name]['error'] = '<br />' . sprintf(getMDConst('_FLOAT_ERR_MSG'), $item_def['caption']);
                        }
                    }

                    // �ϰϥ����å�
                    if (0 !== checkNumberRange($item_def, $ret)) {
                        $errors[] = sprintf(getMDConst('_RANGE_ERR_MSG'), $item_def['caption'], getRangeText($item_def['value_range_min'], $item_def['value_range_max']));
                        $item_defs[$item_name]['error'] = '<br />' . sprintf(getMDConst('_RANGE_ERR_MSG'), $item_def['caption'], getRangeText($item_def['value_range_min'], $item_def['value_range_max']));
                    }
                } else {
                    if ($item_def['required']) {
                        $errors[] = sprintf(getMDConst('_REQ_ERR_MSG'), $item_def['caption']);
                        $item_defs[$item_name]['error'] = '<br />' . sprintf(getMDConst('_REQ_ERR_MSG'), $item_def['caption']);
                    }
                }
            } elseif ('date' == $item_def['type']) {
                // ���դξ��?
                if (isset($_POST[$item_name]) && '' !== $_POST[$item_name]) {
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
                // ����¾�ξ��?
                if (isset($_POST[$item_name]) && '' !== $_POST[$item_name]) {
                    $ret = $_POST[$item_name];
                } else {
                    if ($item_def['required']) {
                        $errors[] = sprintf(getMDConst('_REQ_ERR_MSG'), $item_def['caption']);
                        $item_defs[$item_name]['error'] = '<br />' . sprintf(getMDConst('_REQ_ERR_MSG'), $item_def['caption']);
                    }
                }
            }
        } else {
            // ���ܤ���ɽ���ξ��?
            $ret = $item_def['default'];
        }

        $item_defs[$item_name]['raw'] = $ret;

        return $ret;
    }

    /**
     * ���դν񼰤�����å�����?
     *
     * @param string  $date ����
     * @return bool ���������?rue���������ʤ����?alse
     */
    function isValidDate($date)
    {
        $vals = explode('-', $date);
        if (3 == count($vals)) {
            $year = intval($vals[0]);
            $month = intval($vals[1]);
            $day = intval($vals[2]);

            return checkdate($month, $day, $year);
        }

        return false;
    }

    /**
     * ��ʣ�쥳���ɤ�¸�ߤ��뤫�����å�����.
     *
     * @param string  $value �����å�������
     * @param string  $item_name ����̾
     * @param array  &$item_defs ����������������
     * @param array  &$errors ���顼��å�����������?
     * @param int  $did ���ǡ���ID
     */
    function checkDuplicate($value, $item_name, &$item_defs, &$errors, $did = 0)
    {
        global $data_tbl;
        $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();

        $sql = "SELECT * FROM $data_tbl WHERE ";
        $where_value = is_array($value) ? array2string($value) : $value;
        if ('' === $where_value) {
            $sql .= $item_name . ' IS NULL';
        } else {
            $sql .= $item_name . " = '" . addslashes($where_value) . "'";
        }
        if (0 < $did) {
            $sql .= " AND did != $did";
        }
        $res = $xoopsDB->query($sql);
        if (0 < $xoopsDB->getRowsNum($res)) {
            $item_defs[$item_name]['error'] = '<br />' . getMDConst('_DUPLICATE_ERR_MSG');
            if (!in_array(getMDConst('_DUPLICATE_ERR_MSG'), $errors, true)) {
                $errors[] = getMDConst('_DUPLICATE_ERR_MSG');
            }
        }
    }

    /**
     * �ѿ��˾ܺپ���������Ƥ�?
     *
     * @param array  $row �쥳����
     * @param array  &$item_defs ����������������
     * @param string $target_dirname �⥸�塼��ǥ���?��ȥ��?
     */
    function assignDetail($row, &$item_defs, $target_dirname)
    {
        global $cfg_date_format, $cfg_time_format, $cfg_main_img_wd, $dirname;
        $myts = MyTextSanitizer::getInstance();
        $upload_dir = XOOPS_UPLOAD_PATH . '/' . $target_dirname;

        foreach ($row as $key => $value) {
            if ('did' == $key || 'add_uid' == $key || 'update_uid' == $key || 'uname' == $key) {
                $item_defs[$key]['value'] = $myts->htmlSpecialChars($value);
            } elseif ('add_date' == $key || 'update_date' == $key) {
                $item_defs[$key]['value'] = date($cfg_date_format . ' ' . $cfg_time_format, strtotime($value));
            } elseif (!isset($item_defs[$key])) {
                continue;
            } elseif ('text' == $item_defs[$key]['type'] || 'number' == $item_defs[$key]['type'] || 'radio' == $item_defs[$key]['type'] || 'select' == $item_defs[$key]['type'] || 'date' == $item_defs[$key]['type']) {
                $item_defs[$key]['value'] = sanitize($value, $item_defs[$key]);
            } elseif ('cbox' == $item_defs[$key]['type'] || 'mselect' == $item_defs[$key]['type']) {
                $values = string2array($value);
                $item_defs[$key]['value'] = '';
                foreach ($values as $value) {
                    $item_defs[$key]['value'] .= sanitize($value, $item_defs[$key]) . '<br />';
                }
            } elseif ('tarea' == $item_defs[$key]['type'] || 'xtarea' == $item_defs[$key]['type']) {
                $item_defs[$key]['value'] = $myts->displayTarea($value, $item_defs[$key]['html'], $item_defs[$key]['smily'], $item_defs[$key]['xcode'], $item_defs[$key]['image'], $item_defs[$key]['br']);
            } elseif ('image' == $item_defs[$key]['type']) {
                $filename = $upload_dir . '/' . getRealFileName($row['did'], $key, $value);
                if ('' != $value && file_exists($filename)) {
                    $item_defs[$key]['width'] = getImageWidth($filename, $cfg_main_img_wd);
                }
                $item_defs[$key]['value'] = $myts->htmlSpecialChars($value);
            } elseif ('file' == $item_defs[$key]['type']) {
                $item_defs[$key]['value'] = $myts->htmlSpecialChars($value);
            }
        }
    }

    /**
     * �������������ͤ����?
     *
     * @param string  $op ������?
     * @param string  $item_name ����̾
     * @param array  &$search_defs ����������������
     * @param array  &$errors ���顼��å�����������?
     */
    function initSearchInput($op, $item_name, &$search_defs, &$errors)
    {
        $ret = '';
        if ('search' == $op) {
            if (isset($_POST[$item_name]) && '' !== $_POST[$item_name]) {
                $ret = $_POST[$item_name];
                $_SESSION['search_conds'][$item_name] = $ret;
            } elseif (isset($_GET[$item_name]) && '' !== $_GET[$item_name]) {
                $ret = $_GET[$item_name];
                $_SESSION['search_conds'][$item_name] = $ret;
            }
        } elseif ('back_search' == $op) {
            if (isset($_SESSION['search_conds'][$item_name]) && '' !== $_SESSION['search_conds'][$item_name]) {
                $ret = $_SESSION['search_conds'][$item_name];
            }
        }

        if ('number' == $search_defs[$item_name]['type']) {
            // ���ͤξ��?
            if (isset($ret) && '' !== $ret) {
                // �����ν񼰤��ɤ���
                if ('int' == $search_defs[$item_name]['value_type'] && !is_intval($ret)) {
                    $errors[] = sprintf(getMDConst('_INT_ERR_MSG'), $search_defs[$item_name]['caption']);
                    $search_defs[$item_name]['error'] = '<br />' . sprintf(getMDConst('_INT_ERR_MSG'), $search_defs[$item_name]['caption']);
                } elseif ('float' == $search_defs[$item_name]['value_type'] && !is_floatval($ret)) {
                    // �����ν񼰤��ɤ���
                    if (!is_floatval($ret . '.0')) {
                        $errors[] = sprintf(getMDConst('_FLOAT_ERR_MSG'), $search_defs[$item_name]['caption']);
                        $search_defs[$item_name]['error'] = '<br />' . sprintf(getMDConst('_FLOAT_ERR_MSG'), $search_defs[$item_name]['caption']);
                    }
                }
            }
        } elseif ('date' == $search_defs[$item_name]['type']) {
            // ���դξ��?
            if (isset($ret) && '' !== $ret) {
                if (!isValidDate($ret)) {
                    $errors[] = sprintf(getMDConst('_DATE_ERR_MSG'), $search_defs[$item_name]['caption']);
                    $search_defs[$item_name]['error'] = '<br />' . sprintf(getMDConst('_DATE_ERR_MSG'), $search_defs[$item_name]['caption']);
                }
            }
        }

        return $ret;
    }

    function getHistories($did)
    {
        global $his_tbl, $cfg_date_format, $cfg_time_format;
        $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();

        $histories = [];

        $sql = "SELECT hid, operation, update_uid, update_date FROM $his_tbl WHERE did = $did ORDER BY hid ASC";
        $res = $xoopsDB->query($sql);

        while ([$hid, $operation, $update_uid, $update_date] = $xoopsDB->fetchRow($res)) {
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

    function getOperation($key)
    {
        if ('trans' == $key) {
            return getMDConst('_TRANS');
        } elseif ('add' == $key) {
            return getMDConst('_ADD');
        } elseif ('update' == $key) {
            return getMDConst('_UPDATE');
        } elseif ('delete' == $key) {
            return getMDConst('_DELETE');
        }

        return '';
    }

    function getHisSearchDefs()
    {
        $ret = [];

        // ��������ID
        $item = [];
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

        // ��������
        $item = [];
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
        $item['default'] = [];
        $item['size'] = 4;
        $item['options'] = [getMDConst('_TRANS') => 'trans', getMDConst('_ADD') => 'add', getMDConst('_UPDATE') => 'update', getMDConst('_DELETE') => 'delete'];

        $ret['operation'] = $item;

        // �������?
        $item = [];
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

        // �������?�ʹ�)
        $ret['update_date_or_over'] = $item;
        $ret['update_date_or_over']['is_range_item'] = true;
        $ret['update_date_or_over']['caption'] = getMDConst('_UPDATE_DATE') . '(' . getMDConst('_SINCE') . ')';

        // �������?����)
        $ret['update_date_or_less'] = $item;
        $ret['update_date_or_less']['is_range_item'] = true;
        $ret['update_date_or_less']['caption'] = getMDConst('_UPDATE_DATE') . '(' . getMDConst('_UNTIL') . ')';

        return $ret;
    }

    /**
     * GD(gif��jpeg��png)�򥵥ݡ��Ȥ��Ƥ��뤫�ɤ������֤�.
     *
     * @return bool GD(gif��jpeg��png)�򥵥ݡ��Ȥ��Ƥ������?rue
     */
    function checkGDSupport()
    {
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

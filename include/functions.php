<?php

if (!defined('_XGDB_FUNCTIONS_INCLUDED')) {
    define('_XGDB_FUNCTIONS_INCLUDED', true);

    /**
     * Generate an input tag for a textbox.
     *
     * @param String $name     The value of the name attribute f
     * @param Array  $item_def Item definition information
     * @param String $default  initial value
     * @return String Text box input tag
     */
    function makeTextForm($name, $item_def, $default)
    {
        $myts = MyTextSanitizer::getInstance();

        $ret = '<input type="text" name="' . htmlspecialchars($name, ENT_QUOTES | ENT_HTML5) . '" size="' . (int)$item_def['size'] . '" maxlength="' . (int)$item_def['max_length'] . '" value="' . htmlspecialchars($default, ENT_QUOTES | ENT_HTML5) . '">';

        return $ret;
    }

    /**
     * Generate an input tag for the checkbox.
     *
     * @param String $name     The value of the name attribute
     * @param Array  $item_def Item definition information
     * @param Array  $defaults initial value
     * @return String Checkbox input tag
     */
    function makeCboxForm($name, $item_def, $defaults)
    {
        $myts = MyTextSanitizer::getInstance();

        if (!is_array($defaults)) {
            $defaults = string2array($defaults);
        }
        $ret = '';

        foreach ($item_def['options'] as $key => $value) {
            $ret .= '<label style="margin-right: 1em;"><input type="checkbox" name="' . htmlspecialchars($name, ENT_QUOTES | ENT_HTML5) . '[]" value="' . htmlspecialchars($value, ENT_QUOTES | ENT_HTML5) . '"';
            foreach ($defaults as $default) {
                if ($default == $value) {
                    $ret .= ' checked';
                }
            }
            $ret .= '>' . htmlspecialchars($key, ENT_QUOTES | ENT_HTML5) . '</label>';

            if ($item_def['option_br']) {
                $ret .= '<br>';
            }
        }
        if ('' !== $ret && '<br>' === mb_substr($ret, -6)) {
            $ret = mb_substr($ret, 0, -6);
        }

        return $ret;
    }

    /**
     * Generate an input tag for a radio button.
     *
     * @param String $name     The value of the name attribute
     * @param Array  $item_def Item definition information
     * @param String $default  initial value
     * @return String Radio button input tag
     */
    function makeRadioForm($name, $item_def, $default)
    {
        $myts = MyTextSanitizer::getInstance();

        $ret = '';

        foreach ($item_def['options'] as $key => $value) {
            $ret .= '<label style="margin-right: 1em;"><input type="radio" name="' . htmlspecialchars($name, ENT_QUOTES | ENT_HTML5) . '" value="' . htmlspecialchars($value, ENT_QUOTES | ENT_HTML5) . '"';
            if ($default == $value) {
                $ret .= ' checked';
            }
            $ret .= '>' . htmlspecialchars($key, ENT_QUOTES | ENT_HTML5) . '</label>';

            if ($item_def['option_br']) {
                $ret .= '<br>';
            }
        }
        if ('' !== $ret && '<br>' === mb_substr($ret, -6)) {
            $ret = mb_substr($ret, 0, -6);
        }

        return $ret;
    }

    /**
     * Generate select tag for pull-down menu.
     *
     * @param String $name     The value of the name attribute
     * @param Array  $item_def Item definition information
     * @param String $default  initial value
     * @return String Select tag of pull-down menu
     */
    function makeSelectForm($name, $item_def, $default)
    {
        global $affix;
        $myts = MyTextSanitizer::getInstance();

        $not_selected_ary    = [constant('_' . $affix . '_NOT_SELECTED') => ''];
        $item_def['options'] = $not_selected_ary + $item_def['options'];
        $ret                 = '<select name="' . htmlspecialchars($name, ENT_QUOTES | ENT_HTML5) . '">';

        foreach ($item_def['options'] as $key => $value) {
            $ret .= '<option value="' . htmlspecialchars($value, ENT_QUOTES | ENT_HTML5) . '"';
            if ($default === (string)$value) {
                $ret .= ' selected="selected"';
            }
            $ret .= '>' . htmlspecialchars($key, ENT_QUOTES | ENT_HTML5) . '</option>';
        }
        $ret .= '</select>';

        return $ret;
    }

    /**
     * Generate a select tag for the list box.
     *
     * @param String $name     The value of the name attribute
     * @param Array  $item_def Item definition information
     * @param Array  $defaults initial value
     * @return String Listbox select tag
     */
    function makeMSelectForm($name, $item_def, $defaults)
    {
        $myts = MyTextSanitizer::getInstance();

        if (!is_array($defaults)) {
            $defaults = string2array($defaults);
        }
        $ret = '<select name="' . htmlspecialchars($name, ENT_QUOTES | ENT_HTML5) . '[]" size="' . (int)$item_def['size'] . '" multiple="multiple">';

        foreach ($item_def['options'] as $key => $value) {
            $ret .= '<option value="' . htmlspecialchars($value, ENT_QUOTES | ENT_HTML5) . '"';
            if (in_array($value, $defaults, true)) {
                $ret .= ' selected="selected"';
            }
            $ret .= '>' . htmlspecialchars($key, ENT_QUOTES | ENT_HTML5) . '</option>';
        }
        $ret .= '</select>';

        return $ret;
    }

    /**
     * Generate a textarea tag for the text area.
     *
     * @param String $name     The value of the name attribute
     * @param Array  $item_def Item definition information
     * @param String $default  initial value
     * @return String Textarea tag for text area
     */
    function makeTAreaForm($name, $item_def, $default)
    {
        $myts = MyTextSanitizer::getInstance();

        $ret = '<textarea name="' . htmlspecialchars($name, ENT_QUOTES | ENT_HTML5) . '" rows="' . (int)$item_def['rows'] . '" cols="' . (int)$item_def['cols'] . '">' . sanitize($default, $item_def) . '</textarea>';

        return $ret;
    }

    /**
     * Generate a textarea tag for a text area that supports BBcode.
     *
     * @param String $name     The value of the name attribute
     * @param Array  $item_def Item definition information
     * @param String $default  initial value
     * @return String BB code compatible text area textarea tag
     */
    function makeXTAreaForm($name, $item_def, $default)
    {
        $myts = MyTextSanitizer::getInstance();

        $form = new XoopsFormDhtmlTextArea(htmlspecialchars($name, ENT_QUOTES | ENT_HTML5), htmlspecialchars($name, ENT_QUOTES | ENT_HTML5), sanitize($default, $item_def), (int)$item_def['rows'], (int)$item_def['cols']);
        $ret  = $form->render();

        return $ret;
    }

    /**
     * Generate date input tag.
     *
     * @param String $name     The value of the name attribute
     * @param Array  $item_def Item definition information
     * @param String $default  initial value
     * @return String Date input tag
     */
    function makeDateForm($name, $item_def, $default)
    {
        $myts = MyTextSanitizer::getInstance();

        $form = new XoopsFormTextDateSelect(htmlspecialchars($name, ENT_QUOTES | ENT_HTML5), htmlspecialchars($name, ENT_QUOTES | ENT_HTML5), 15, htmlspecialchars($default, ENT_QUOTES | ENT_HTML5));
        $ret  = $form->render();

        return $ret;
    }

    /**
     * Generate an input tag for file upload.
     *
     * @param String $name     The value of the name attribute
     * @param Array  $item_def Item definition information
     * @return String File upload input
     */
    function makeFileForm($name, $item_def)
    {
        $myts = MyTextSanitizer::getInstance();
        $ret  = '<input type="file" name="' . htmlspecialchars($name, ENT_QUOTES | ENT_HTML5) . '" size="' . (int)$item_def['size'] . ' maxlength="' . (int)$item_def['max_length'] . '" >';

        return $ret;
    }

    /**
     * Generate an input tag for the radio button of the search condition.
     *
     * @param String $name    The value of the name attribute
     * @param Array  $options Array of choices
     * @param String $default initial value
     * @return String Radio button input tag
     */
    function makeCondForm($name, $options, $default)
    {
        global $affix;
        $myts = MyTextSanitizer::getInstance();

        $ret = '<br>' . getMDConst('_COND_LABEL');

        foreach ($options as $key => $value) {
            $ret .= '<label style="margin-right: 1em;"><input type="radio" name="' . htmlspecialchars($name, ENT_QUOTES | ENT_HTML5) . '" value="' . htmlspecialchars($value, ENT_QUOTES | ENT_HTML5) . '"';
            if ($default == $value) {
                $ret .= ' checked';
            }
            $ret .= '>' . htmlspecialchars($key, ENT_QUOTES | ENT_HTML5) . '</label>';
        }

        return $ret;
    }

    /**
     * Generates a group name and group ID string to use in the group select box.
     *
     * @return String Group name and group ID string used for the group select box
     */
    function makeGroupSelectOptions()
    {
        $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();
        $ret     = '';

        $sql        = 'SELECT groupid, name FROM ' . $xoopsDB->prefix('groups') . ' ORDER BY groupid ASC';
        $res        = $xoopsDB->query($sql);
        $groups_ary = [];
        while ([
            $gid,
            $gname,
        ] = $xoopsDB->fetchRow($res)) {
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
     * Converts a list of group ID strings to a newlined group name string.
     *
     * @param String $gidstring List of group ID strings
     * @return String Group name string with line breaks
     */
    function gidstring2brgroup($gidstring)
    {
        if (!isset($gidstring) || '' === $gidstring) {
            return '';
        }

        $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();
        $myts    = MyTextSanitizer::getInstance();
        $ret     = '';

        $sql        = 'SELECT groupid, name FROM ' . $xoopsDB->prefix('groups') . ' ORDER BY groupid ASC';
        $res        = $xoopsDB->query($sql);
        $groups_ary = [];
        while ([
            $gid,
            $gname,
        ] = $xoopsDB->fetchRow($res)) {
            $groups_ary[$gid] = $gname;
        }

        $gid_ary = string2array($gidstring);
        foreach ($gid_ary as $gid) {
            if ('' === $gid) {
                continue;
            }
            $ret .= htmlspecialchars($groups_ary[$gid], ENT_QUOTES | ENT_HTML5) . '<br>';
        }

        return $ret;
    }

    /**
     * Returns a random filename that will be unique within the specified directory.
     *
     * @param String $ext            File extension
     * @param String $target_dirpath Full directory path
     * @return String File name (not including directory path)
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
     * Resize the vertical and horizontal size of the file.
     *
     * @param String $file_name      File name (including file path)
     * @param String $max_image_size Maximum file size (px)
     * @return String The extension of the resized file. Blank text if not resized字
     */
    function resizeImage($file_name, $max_image_size)
    {
        if (!extension_loaded('gd')) {
            return '';
        }
        $gd_infos = gd_info();

        [
            $bef_x,
            $bef_y,
            $type,
        ] = getimagesize($file_name);
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
     * Check permissions.
     *
     * @param Array $user_groups An array of group IDs for the group to which the user belongs
     * @param Array $perm_groups An array of group IDs for privileged groups
     * @return Boolean True if authorized, false otherwise
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
     * Gets an array of Xoops User Objects for users belonging to the group with the specified group ID.
     *
     * @param Array Array of group IDs
     * @return Array Array of users' XoopsUser Objects
     */
    function getUsers($gids)
    {
        $ret = [];

        foreach ($gids as $gid) {
            $memberHandler = xoops_getHandler('member');
            $users         = $memberHandler->getUsersByGroup($gid, true);
            foreach ($users as $user) {
                $ret[$user->getVar('uid')] = $user;
            }
        }

        return $ret;
    }

    /**
     * stripslashesRecursively process and return with a function.
     *
     * @param $value Value to be processed by stripslashes function
     * @return String/Array The value of the argument processed by the stripslashes function
     */
    //    function stripSlashesDeep($value)
    //    {
    //        if (is_array($value)) {
    //            $value = array_map('stripSlashesDeep', $value);
    //        } else {
    //            $value = stripslashes($value);
    //        }
    //
    //        return $value;
    //    }

    /**
     * Convert an array to a delimiter string.
     *
     * @param Array  $array Arrangement
     * @param String $sep   Delimiter (default: |)
     *
     * @return String
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
     * Convert the array to a newline delimited string.
     *
     * @param String $array Array
     *
     * @return String Line feed delimiter string
     */
    function array2brstring($array)
    {
        $ret = '';

        foreach ($array as $key => $value) {
            $key   .= '';
            $value .= '';
            if ($key !== $value) {
                $ret .= $key . '|';
            }
            $ret .= $value;
            $ret .= '<br>';
        }

        if ('' !== $ret) {
            $ret = mb_substr($ret, 0, -6);
        }

        return $ret;
    }

    /**
     * Convert the delimiter string to an array.
     *
     * @param String $string String
     * @param String $sep    Delimiter (default: |)
     *
     * @return Array
     */
    function string2array($string, $sep = '|')
    {
        if ('' != $string) {
            return explode($sep, $string);
        }

        return [];
    }

    /**
     * Convert a line break delimited string to an array.
     *
     * @param String $string String
     * @param String $sep    Delimiter (default: |)
     *
     * @return Array
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
            $string = preg_match("/\r\n/", "\n", $string);
            $string = preg_match("/\r/", "\n", $string);
        }
        $strings = explode("\n", $string);

        $ret = [];
        foreach ($strings as $value) {
            if (mb_strpos($value, $sep)) {
                [
                    $key,
                    $value,
                ] = explode($sep, $value);
                $ret[htmlspecialchars($key, ENT_QUOTES | ENT_HTML5)] = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5);
            } else {
                $value       = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5);
                $ret[$value] = $value;
            }
        }

        return $ret;
    }

    /**
     * Generate a WHERE clause for the group ID.
     *
     * @param Array  $gids Array of group IDs
     * @param String $as   xgdb item Table alias
     * @return String WHERE clause of group ID
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
     * Returns information for all items.
     *
     * @param mixed $dirname
     * @param mixed $gids
     * @return array Array of item information
     */
    function getItemDefs($dirname, $gids)
    {
        //global $gids;
        $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();
        $myts    = MyTextSanitizer::getInstance();

        $ret = [];
        $sql = 'SELECT * FROM ' . $xoopsDB->prefix($dirname . '_xgdb_item') . ' WHERE ' . makeWhereGID($gids) . ' ORDER BY `sequence` ASC, `iid` ASC';
        $res = $xoopsDB->query($sql);
        while (false !== ($row = $xoopsDB->fetchArray($res))) {
            $item                = [];
            $item['caption']     = htmlspecialchars($row['caption'], ENT_QUOTES | ENT_HTML5);
            $item['type']        = htmlspecialchars($row['type'], ENT_QUOTES | ENT_HTML5);
            $item['required']    = $row['required'];
            $item['sequence']    = $row['sequence'];
            $item['search']      = $row['search'];
            $item['list']        = $row['list'];
            $item['add']         = $row['add'];
            $item['update']      = $row['update'];
            $item['detail']      = $row['detail'];
            $item['site_search'] = $row['site_search'];
            $item['duplicate']   = $row['duplicate'];
            $item['search_desc'] = $myts->displayTarea($row['search_desc']);
            $item['show_desc']   = $myts->displayTarea($row['show_desc']);
            $item['input_desc']  = $myts->displayTarea($row['input_desc']);
            $item['disp_cond']   = (int)$row['disp_cond'];
            $item['search_cond'] = (int)$row['search_cond'];
            if ('text' === $row['type']) {
                $item['value_type'] = htmlspecialchars($row['value_type'], ENT_QUOTES | ENT_HTML5);
                $item['default']    = htmlspecialchars($row['default'], ENT_QUOTES | ENT_HTML5);
                $item['size']       = $row['size'];
                $item['max_length'] = $row['max_length'];
            } elseif ('number' === $row['type']) {
                $item['value_type'] = htmlspecialchars($row['value_type'], ENT_QUOTES | ENT_HTML5);
                if ('' !== $row['value_range_min']) {
                    $item['value_range_min'] = $row['value_range_min'];
                }
                if ('' !== $row['value_range_max']) {
                    $item['value_range_max'] = $row['value_range_max'];
                }
                $item['default']    = htmlspecialchars($row['default'], ENT_QUOTES | ENT_HTML5);
                $item['size']       = $row['size'];
                $item['max_length'] = $row['max_length'];
            } elseif ('cbox' === $row['type']) {
                $item['value_type'] = htmlspecialchars($row['value_type'], ENT_QUOTES | ENT_HTML5);
                $item['default']    = nl2array($row['default']);
                $item['options']    = nl2array($row['options']);
                $item['option_br']  = $row['option_br'];
            } elseif ('radio' === $row['type']) {
                $item['value_type'] = htmlspecialchars($row['value_type'], ENT_QUOTES | ENT_HTML5);
                $item['default']    = htmlspecialchars($row['default'], ENT_QUOTES | ENT_HTML5);
                $item['options']    = nl2array($row['options']);
                $item['option_br']  = $row['option_br'];
            } elseif ('select' === $row['type']) {
                $item['value_type'] = htmlspecialchars($row['value_type'], ENT_QUOTES | ENT_HTML5);
                $item['default']    = htmlspecialchars($row['default'], ENT_QUOTES | ENT_HTML5);
                $item['size']       = 5;
                $item['options']    = nl2array($row['options']);
            } elseif ('mselect' === $row['type']) {
                $item['value_type'] = htmlspecialchars($row['value_type'], ENT_QUOTES | ENT_HTML5);
                $item['default']    = nl2array($row['default']);
                $item['size']       = $row['size'];
                $item['options']    = nl2array($row['options']);
            } elseif ('tarea' === $row['type'] || 'xtarea' === $row['type']) {
                $item['default']    = $row['html'] ? $row['default'] : htmlspecialchars($row['default'], ENT_QUOTES | ENT_HTML5);
                $item['size']       = $row['size'];
                $item['max_length'] = $row['max_length'];
                $item['rows']       = $row['rows'];
                $item['cols']       = $row['cols'];
                $item['html']       = $row['html'];
                $item['smily']      = $row['smily'];
                $item['xcode']      = $row['xcode'];
                $item['image']      = $row['image'];
                $item['br']         = $row['br'];
            } elseif ('file' === $row['type'] || 'image' === $row['type']) {
                $item['default']       = '';
                $item['size']          = $row['size'];
                $item['max_length']    = $row['max_length'];
                $item['max_file_size'] = $row['max_file_size'];
                if ('image' === $row['type']) {
                    $item['max_image_size'] = $row['max_image_size'];
                }
                $item['allowed_exts']  = nl2array($row['allowed_exts']);
                $item['allowed_mimes'] = nl2array($row['allowed_mimes']);
            } elseif ('date' === $row['type']) {
                $item['default'] = '';
            }

            $ret[htmlspecialchars($row['name'], ENT_QUOTES | ENT_HTML5)] = $item;
        }

        return $ret;
    }

    /**
     * Of the item information, the item information that matches the specified type is returned.
     *
     * @param Array  $defs Array of item information
     * @param String $type Type of type to get
     *
     * @return Array Array of item information
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
                if ('search' === $type && ('number' === $def['type'] || 'date' === $def['type'])) {
                    $ret[$index . '_or_over']                  = $def;
                    $ret[$index . '_or_over']['is_range_item'] = true;
                    if ('number' === $def['type']) {
                        $ret[$index . '_or_over']['caption'] = $def['caption'] . '(' . getMDConst('_OR_OVER') . ')';
                    } elseif ('date' === $def['type']) {
                        $ret[$index . '_or_over']['caption'] = $def['caption'] . '(' . getMDConst('_SINCE') . ')';
                    }

                    $ret[$index . '_or_less']                  = $def;
                    $ret[$index . '_or_less']['is_range_item'] = true;
                    if ('number' === $def['type']) {
                        $ret[$index . '_or_less']['caption'] = $def['caption'] . '(' . getMDConst('_OR_LESS') . ')';
                    } elseif ('date' === $def['type']) {
                        $ret[$index . '_or_less']['caption'] = $def['caption'] . '(' . getMDConst('_UNTIL') . ')';
                    }
                }
            }
        }

        return $ret;
    }

    /**
     * Sanitize and return the argument value according to the content of the item information.
     *
     * @param String  $value         Value to be sanitized
     * @param Array   $item_def      Project information
     * @param Boolean $number_format Numeric format format
     *
     * @return String Sanitized argument value
     */
    function sanitize($value, $item_def, $number_format = true)
    {
        if ('' === $value || null === $value) {
            return '';
        }

        $myts = MyTextSanitizer::getInstance();
        global $cfg_date_format;

        if ('number' === $item_def['type']) {
            if ('int' === $item_def['value_type']) {
                $value = (int)$value;
                if ($number_format) {
                    $value = number_format($value);
                }
            } elseif ('float' === $item_def['value_type']) {
                $value = (float)$value;
                if (false === mb_strpos($value, '.')) {
                    $value .= '.0';
                }
                if ($number_format) {
                    $value = number_format($value, mb_strlen($value) - (int)mb_strpos($value, '.') - 1);
                } else {
                    $value = number_format($value, mb_strlen($value) - (int)mb_strpos($value, '.') - 1, '.', '');
                }
            }
        } elseif ('tarea' === $item_def['type'] || 'xtarea' === $item_def['type']) {
            if (!$item_def['html']) {
                $value = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5);
            }
        } elseif ('file' === $item_def['type'] || 'image' === $item_def['type']) {
            $value = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5);
        } elseif ('date' === $item_def['type']) {
            $value = date($cfg_date_format, strtotime($value));
        } else {
            // text,cbox,radio,select,mselect
            $value = $myts->makeClickable(htmlspecialchars($value, ENT_QUOTES | ENT_HTML5));
        }

        return $value;
    }

    /**
     * Update the module template file.
     *
     * @param String  $tpl_set      Template set name
     * @param String  $tpl_file     Template file name
     * @param String  $tpl_source   Contents of the source code of the template file
     * @param Integer $lastmodified Timestamp of last update date
     */
    function updateTemplate($tpl_set, $tpl_file, $tpl_source, $lastmodified = 0)
    {
        require_once XOOPS_ROOT_PATH . '/class/template.php';
        $xoopsDB       = XoopsDatabaseFactory::getDatabaseConnection();
        $tplfile_tbl   = $xoopsDB->prefix('tplfile');
        $tplsource_tbl = $xoopsDB->prefix('tplsource');

        $sql = "SELECT * FROM $tplfile_tbl WHERE tpl_tplset = 'default' AND tpl_file = '" . addslashes($tpl_file) . "'";
        $res = $xoopsDB->query($sql);
        if (0 == $xoopsDB->getRowsNum($res)) {
            return;
        }

        $tpl_id_sql = "SELECT tpl_id FROM $tplfile_tbl WHERE tpl_tplset = '" . addslashes($tpl_set) . "' AND tpl_file = '" . addslashes($tpl_file) . "'";
        $tpl_id_res = $xoopsDB->query($tpl_id_sql);

        if ('default' !== $tpl_set && 0 == $xoopsDB->getRowsNum($tpl_id_res)) {
            while (false !== ($row = $xoopsDB->fetchArray($res))) {
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
     * Returns a string representation that represents the range of minimum and maximum values.
     *
     * @param String $value_range_min minimum value
     * @param String $value_range_max Maximum value
     *
     * @return String Range of minimum and maximum values
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
     * Determine if the value is an integer value.
     *
     * @param String $value value
     *
     * @return Boolean True for integer values, false otherwise
     */
    function isInteger($value)
    {
        if (!isset($value) || '' === $value) {
            return false;
        }

        if (!is_numeric($value)) {
            return false;
        } elseif (false !== mb_strpos($value, '.')) {
            return false;
        }

        return true;
    }

    /**
     * Determine if the value is a decimal value.
     *
     * @param String $value value
     *
     * @return Boolean True for fractional numbers, false otherwise
     */
    function isFloat($value)
    {
        if (!isset($value) || '' === $value) {
            return false;
        }

        if (!is_numeric($value)) {
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
     * Returns the saved file name.
     *
     * @param String $did       Data ID
     * @param String $col_name  column_name
     * @param String $file_name File name on the table
     * @return String Saved file name
     */
    function getRealFileName($did, $col_name, $file_name)
    {
        return urlencode("$did-$col_name-$file_name");
    }

    /**
     * Returns the width of the image file.
     *
     * @param String $filename  Absolute path of image file
     * @param String $cfg_width Image file width setting
     * @return int Image file width
     */
    function getImageWidth($filename, $cfg_width)
    {
        [
            $x,
            $y,
            $type,
        ] = getimagesize($filename);

        if ($x > $cfg_width) {
            $ret = $cfg_width;
        } else {
            return $ret = $x;
        }

        return $ret;
    }

    /**
     * Returns whether the item has a range.
     *
     * @param String $item_name Item name
     * @return bool True for items with a range, false for items without a range
     */
    function isRangeItemName($item_name)
    {
        if (8 < mb_strlen($item_name)) {
            if ('_or_over' === mb_substr($item_name, -8) || '_or_less' === mb_substr($item_name, -8)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns whether the number is within range.
     *
     * @param Array $item_def Item definition information
     * @param Array $item_def Item definition information
     * @return int -1：Less than, 0: in range, 1: over
     */
    function checkNumberRange($item_def, $value)
    {
        if ('int' === $item_def['value_type']) {
            if (isset($item_def['value_range_min']) && '' !== $item_def['value_range_min']) {
                if ((int)$value < (int)$item_def['value_range_min']) {
                    return -1;
                }
            } elseif (isset($item_def['value_range_max']) && '' !== $item_def['value_range_max']) {
                if ((int)$value > (int)$item_def['value_range_max']) {
                    return 1;
                }
            }
        } elseif (isset($item_def['value_range_min']) && '' !== $item_def['value_range_min']) {
                if ((float)$value < (float)$item_def['value_range_min']) {
                    return -1;
                }
            } elseif (isset($item_def['value_range_max']) && '' !== $item_def['value_range_max']) {
                if ((float)$value > (float)$item_def['value_range_max']) {
                    return 1;
                }

        }

        return 0;
    }

    /**
     * Returns a constant for the module front screen (_MD_).
     *
     * @param String $const_name Constant name
     * @return String Constant value
     */
    function getMDConst($const_name)
    {
        global $affix;

        return constant('_MD_' . $affix . $const_name);
    }

    /**
     * Generate a form for input.
     *
     * @param Array  &$item_defs Array of item definition information
     */
    function makeInputForms(&$item_defs)
    {
        foreach ($item_defs as $item_name => $item_def) {
            if ('text' === $item_def['type'] || 'number' === $item_def['type']) {
                $item_defs[$item_name]['value'] = makeTextForm($item_name, $item_def, $item_def['raw']);
            } elseif ('cbox' === $item_def['type']) {
                $item_defs[$item_name]['value'] = makeCboxForm($item_name, $item_def, $item_def['raw']);
            } elseif ('radio' === $item_def['type']) {
                $item_defs[$item_name]['value'] = makeRadioForm($item_name, $item_def, $item_def['raw']);
            } elseif ('select' === $item_def['type']) {
                $item_defs[$item_name]['value'] = makeSelectForm($item_name, $item_def, $item_def['raw']);
            } elseif ('mselect' === $item_def['type']) {
                $item_defs[$item_name]['value'] = makeMSelectForm($item_name, $item_def, $item_def['raw']);
            } elseif ('tarea' === $item_def['type']) {
                $item_defs[$item_name]['value'] = makeTAreaForm($item_name, $item_def, $item_def['raw']);
            } elseif ('xtarea' === $item_def['type']) {
                $item_defs[$item_name]['value'] = makeXTAreaForm($item_name, $item_def, $item_def['raw']);
            } elseif ('file' === $item_def['type'] || 'image' === $item_def['type']) {
                $item_defs[$item_name]['value'] = makeFileForm($item_name, $item_def);
            } elseif ('date' === $item_def['type']) {
                $item_defs[$item_name]['value'] = makeDateForm($item_name, $item_def, $item_def['raw']);
            }
        }
    }

    /**
     * Initialize the input value.
     *
     * @param Array   $item_def           Project Item Definition
     * @param String  $item_name          Item name
     * @param Array  &$item_defs          Array of item definition information
     * @param Array  &$uploaded_file_defs Array of upload file item names
     * @param Array  &$errors             Array of error messages
     * @param String  $type               Processing type
     * @return mixed|string
     */
    function initInput($item_def, $item_name, &$item_defs, &$uploaded_file_defs, &$errors, $type)
    {
        $myts = MyTextSanitizer::getInstance();

        $ret = '';
        if ($item_def[$type]) {
            // For files and images
            if ('file' === $item_def['type'] || 'image' === $item_def['type']) {
                if (isset($_FILES[$item_name]['tmp_name']) && '' !== $_FILES[$item_name]['tmp_name']) {
                    if (!in_array($_FILES[$item_name]['type'], $item_def['allowed_mimes'], true)) {
                        $errors[]                       = sprintf(getMDConst('_FILE_TYPE_ERR_MSG'), htmlspecialchars($_FILES[$item_name]['type'], ENT_QUOTES | ENT_HTML5), $item_def['caption']);
                        $item_defs[$item_name]['error'] = '<br>' . sprintf(getMDConst('_FILE_TYPE_ERR_MSG'), htmlspecialchars($_FILES[$item_name]['type'], ENT_QUOTES | ENT_HTML5), $item_def['caption']);
                    } elseif (!in_array(pathinfo($_FILES[$item_name]['name'], PATHINFO_EXTENSION), $item_def['allowed_exts'], true)) {
                        $errors[]                       = sprintf(getMDConst('_FILE_EXT_ERR_MSG'), htmlspecialchars(pathinfo($_FILES[$item_name]['name'], PATHINFO_EXTENSION), ENT_QUOTES | ENT_HTML5), $item_def['caption']);
                        $item_defs[$item_name]['error'] = '<br>' . sprintf(getMDConst('_FILE_EXT_ERR_MSG'), htmlspecialchars(pathinfo($_FILES[$item_name]['name'], PATHINFO_EXTENSION), ENT_QUOTES | ENT_HTML5), $item_def['caption']);
                    } elseif (($item_def['max_file_size'] * 1024 * 1024) < $_FILES[$item_name]['size']) {
                        $errors[]                       = sprintf(getMDConst('_FILE_SIZE_ERR_MSG'), $item_def['caption']);
                        $item_defs[$item_name]['error'] = '<br>' . sprintf(getMDConst('_FILE_SIZE_ERR_MSG'), $item_def['caption']);
                    } else {
                        $ret                            = $_FILES[$item_name]['name'];
                        $uploaded_file_defs[$item_name] = $item_def;
                    }
                } elseif ('add' === $type && $item_def['required']) {
                        $errors[]                       = sprintf(getMDConst('_REQ_ERR_MSG'), $item_def['caption']);
                        $item_defs[$item_name]['error'] = '<br>' . sprintf(getMDConst('_REQ_ERR_MSG'), $item_def['caption']);
                }
            } elseif ('number' === $item_def['type']) {
                // For numbers
                if (isset($_POST[$item_name]) && '' !== $_POST[$item_name]) {
                    $ret = $_POST[$item_name];
                    // Whether it is an integer format
                    if ('int' === $item_def['value_type'] && !isInteger($ret)) {
                        $errors[]                       = sprintf(getMDConst('_INT_ERR_MSG'), $item_def['caption']);
                        $item_defs[$item_name]['error'] = '<br>' . sprintf(getMDConst('_INT_ERR_MSG'), $item_def['caption']);
                    } elseif ('float' === $item_def['value_type'] && !isFloat($ret)) {
                            // Whether it is in decimal format
                            if (!isFloat($ret . '.0')) {
                                $errors[]                       = sprintf(getMDConst('_FLOAT_ERR_MSG'), $item_def['caption']);
                                $item_defs[$item_name]['error'] = '<br>' . sprintf(getMDConst('_FLOAT_ERR_MSG'), $item_def['caption']);
                            }
                        }

                    // Range check
                    if (0 !== checkNumberRange($item_def, $ret)) {
                        $errors[]                       = sprintf(getMDConst('_RANGE_ERR_MSG'), $item_def['caption'], getRangeText($item_def['value_range_min'], $item_def['value_range_max']));
                        $item_defs[$item_name]['error'] = '<br>' . sprintf(getMDConst('_RANGE_ERR_MSG'), $item_def['caption'], getRangeText($item_def['value_range_min'], $item_def['value_range_max']));
                    }
                } elseif ($item_def['required']) {
                            $errors[]                       = sprintf(getMDConst('_REQ_ERR_MSG'), $item_def['caption']);
                            $item_defs[$item_name]['error'] = '<br>' . sprintf(getMDConst('_REQ_ERR_MSG'), $item_def['caption']);
                    }
                } elseif ('date' === $item_def['type']) {
                    // For dates
                    if (isset($_POST[$item_name]) && '' !== $_POST[$item_name]) {
                        $ret = $_POST[$item_name];
                        if (!isValidDate($ret)) {
                            $errors[]                       = sprintf(getMDConst('_DATE_ERR_MSG'), $item_def['caption']);
                            $item_defs[$item_name]['error'] = '<br>' . sprintf(getMDConst('_DATE_ERR_MSG'), $item_def['caption']);
                        }
                    } elseif ($item_def['required']) {
                            $errors[]                       = sprintf(getMDConst('_REQ_ERR_MSG'), $item_def['caption']);
                            $item_defs[$item_name]['error'] = '<br>' . sprintf(getMDConst('_REQ_ERR_MSG'), $item_def['caption']);

                    }
                    // In other cases
                } elseif (isset($_POST[$item_name]) && '' !== $_POST[$item_name]) {
                        $ret = $_POST[$item_name];
                    } elseif ($item_def['required']) {
                            $errors[]                       = sprintf(getMDConst('_REQ_ERR_MSG'), $item_def['caption']);
                            $item_defs[$item_name]['error'] = '<br>' . sprintf(getMDConst('_REQ_ERR_MSG'), $item_def['caption']);
                }
            } else {
                // If the item is hidden
                $ret = $item_def['default'];
            }

            $item_defs[$item_name]['raw'] = $ret;

            return $ret;
        }

        /**
         * Check the date format.
         *
         * @param String $date Date
         * @return bool true if correct, false if incorrect
         */
        function isValidDate($date)
        {
            $vals = explode('-', $date);
            if (3 == count($vals)) {
                $year  = (int)$vals[0];
                $month = (int)$vals[1];
                $day   = (int)$vals[2];

                return checkdate($month, $day, $year);
            }

            return false;
        }

        /**
         * Check for duplicate records.
         *
         * @param String  $value     The value to check
         * @param String  $item_name Item name
         * @param Array  &$item_defs Array of item definition information
         * @param Array  &$errors     Array of error messages
         * @param int     $did       Data ID to exclude
         */
        function checkDuplicate($value, $item_name, &$item_defs, &$errors, $did = 0)
        {
            global $data_tbl;
            $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();

            $sql         = "SELECT * FROM $data_tbl WHERE ";
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
                $item_defs[$item_name]['error'] = '<br>' . getMDConst('_DUPLICATE_ERR_MSG');
                if (!in_array(getMDConst('_DUPLICATE_ERR_MSG'), $errors, true)) {
                    $errors[] = getMDConst('_DUPLICATE_ERR_MSG');
                }
            }
        }

        /**
         * Assign detailed information to variables.
         *
         * @param Array   $row            row record
         * @param Array  &$item_defs      Array of item definition information
         * @param String  $target_dirname Module directory name
         */
        function assignDetail($row, &$item_defs, $target_dirname)
        {
            global $cfg_date_format, $cfg_time_format, $cfg_main_img_wd, $dirname;
            $myts       = MyTextSanitizer::getInstance();
            $upload_dir = XOOPS_UPLOAD_PATH . '/' . $target_dirname;

            foreach ($row as $key => $value) {
                if ('did' === $key || 'add_uid' === $key || 'update_uid' === $key || 'uname' === $key) {
                    $item_defs[$key]['value'] = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5);
                } elseif ('add_date' === $key || 'update_date' === $key) {
                    $item_defs[$key]['value'] = date($cfg_date_format . ' ' . $cfg_time_format, strtotime($value));
                } elseif (!isset($item_defs[$key])) {
                    continue;
                } elseif ('text' === $item_defs[$key]['type'] || 'number' === $item_defs[$key]['type'] || 'radio' === $item_defs[$key]['type'] || 'select' === $item_defs[$key]['type'] || 'date' === $item_defs[$key]['type']) {
                    $item_defs[$key]['value'] = sanitize($value, $item_defs[$key]);
                } elseif ('cbox' === $item_defs[$key]['type'] || 'mselect' === $item_defs[$key]['type']) {
                    $values                   = string2array($value);
                    $item_defs[$key]['value'] = '';
                    foreach ($values as $value) {
                        $item_defs[$key]['value'] .= sanitize($value, $item_defs[$key]) . '<br>';
                    }
                } elseif ('tarea' === $item_defs[$key]['type'] || 'xtarea' === $item_defs[$key]['type']) {
                    $item_defs[$key]['value'] = $myts->displayTarea($value, $item_defs[$key]['html'], $item_defs[$key]['smily'], $item_defs[$key]['xcode'], $item_defs[$key]['image'], $item_defs[$key]['br']);
                } elseif ('image' === $item_defs[$key]['type']) {
                    $filename = $upload_dir . '/' . getRealFileName($row['did'], $key, $value);
                    if ('' != $value && file_exists($filename)) {
                        $item_defs[$key]['width'] = getImageWidth($filename, $cfg_main_img_wd);
                    }
                    $item_defs[$key]['value'] = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5);
                } elseif ('file' === $item_defs[$key]['type']) {
                    $item_defs[$key]['value'] = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5);
                }
            }
        }

        /**
         * Initialize the input value of the search condition.
         *
         * @param String  $op          Operation type
         * @param String  $item_name   Item name
         * @param Array  &$search_defs Array of item definition information
         * @param Array  &$errors      Array of error message
         * @return string
         */
        function initSearchInput($op, $item_name, &$search_defs, &$errors)
        {
            $ret = '';
            if ('search' === $op) {
                if (isset($_POST[$item_name]) && '' !== $_POST[$item_name]) {
                    $ret                                  = $_POST[$item_name];
                    $_SESSION['search_conds'][$item_name] = $ret;
                } elseif (isset($_GET[$item_name]) && '' !== $_GET[$item_name]) {
                    $ret                                  = $_GET[$item_name];
                    $_SESSION['search_conds'][$item_name] = $ret;
                }
            } elseif ('back_search' === $op) {
                if (isset($_SESSION['search_conds'][$item_name]) && '' !== $_SESSION['search_conds'][$item_name]) {
                    $ret = $_SESSION['search_conds'][$item_name];
                }
            }

            if ('number' === $search_defs[$item_name]['type']) {
                // For numbers
                if (isset($ret) && '' !== $ret) {
                    // Whether it is an integer format
                    if ('int' === $search_defs[$item_name]['value_type'] && !isInteger($ret)) {
                        $errors[]                         = sprintf(getMDConst('_INT_ERR_MSG'), $search_defs[$item_name]['caption']);
                        $search_defs[$item_name]['error'] = '<br>' . sprintf(getMDConst('_INT_ERR_MSG'), $search_defs[$item_name]['caption']);
                    } elseif ('float' === $search_defs[$item_name]['value_type'] && !isFloat($ret)) {
                        // Whether it is in decimal format
                        if (!isFloat($ret . '.0')) {
                            $errors[]                         = sprintf(getMDConst('_FLOAT_ERR_MSG'), $search_defs[$item_name]['caption']);
                            $search_defs[$item_name]['error'] = '<br>' . sprintf(getMDConst('_FLOAT_ERR_MSG'), $search_defs[$item_name]['caption']);
                        }
                    }
                }
            } elseif ('date' === $search_defs[$item_name]['type']) {
                // For dates
                if (isset($ret) && '' !== $ret) {
                    if (!isValidDate($ret)) {
                        $errors[]                         = sprintf(getMDConst('_DATE_ERR_MSG'), $search_defs[$item_name]['caption']);
                        $search_defs[$item_name]['error'] = '<br>' . sprintf(getMDConst('_DATE_ERR_MSG'), $search_defs[$item_name]['caption']);
                    }
                }
            }

            return $ret;
        }

        /**
         * @param $did
         * @return array
         */
        function getHistories($did)
        {
            global $his_tbl, $cfg_date_format, $cfg_time_format;
            $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();

            $histories = [];

            $sql = "SELECT hid, operation, update_uid, update_date FROM $his_tbl WHERE did = $did ORDER BY hid ASC";
            $res = $xoopsDB->query($sql);

            while ([
                $hid,
                $operation,
                $update_uid,
                $update_date,
            ] = $xoopsDB->fetchRow($res)) {
                $history['hid']           = $hid;
                $history['operation_raw'] = $operation;
                $history['operation']     = getOperation($operation);
                $history['update_uname']  = XoopsUser::getUnameFromId($update_uid);
                $history['update_uid']    = $update_uid;
                $history['update_date']   = date($cfg_date_format . ' ' . $cfg_time_format, strtotime($update_date));

                $histories[] = $history;
            }

            return $histories;
        }

        /**
         * @param $key
         * @return string
         */
        function getOperation($key)
        {
            if ('trans' === $key) {
                return getMDConst('_TRANS');
            }

            if ('add' === $key) {
                return getMDConst('_ADD');
            } elseif ('update' === $key) {
                return getMDConst('_UPDATE');
            } elseif ('delete' === $key) {
                return getMDConst('_DELETE');
            }

            return '';
        }

        /**
         * @return array
         */
        function getHisSearchDefs()
        {
            $ret = [];

            // Update history ID
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

            // Processing content
            $item                = [];
            $item['caption']     = getMDConst('_OPERATION');
            $item['type']        = 'mselect';
            $item['required']    = '0';
            $item['sequence']    = '0';
            $item['search']      = '1';
            $item['list']        = '1';
            $item['add']         = '0';
            $item['update']      = '0';
            $item['detail']      = '0';
            $item['site_search'] = '0';
            $item['duplicate']   = '0';
            $item['search_desc'] = '';
            $item['show_desc']   = '';
            $item['input_desc']  = '';
            $item['disp_cond']   = 0;
            $item['search_cond'] = 0;

            $item['value_type'] = 'string';
            $item['default']    = [];
            $item['size']       = 4;
            $item['options']    = [
                getMDConst('_TRANS')  => 'trans',
                getMDConst('_ADD')    => 'add',
                getMDConst('_UPDATE') => 'update',
                getMDConst('_DELETE') => 'delete',
            ];

            $ret['operation'] = $item;

            // Processing date and time
            $item                = [];
            $item['caption']     = getMDConst('_UPDATE_DATE');
            $item['type']        = 'date';
            $item['required']    = '0';
            $item['sequence']    = '0';
            $item['search']      = '1';
            $item['list']        = '1';
            $item['add']         = '0';
            $item['update']      = '0';
            $item['detail']      = '0';
            $item['site_search'] = '0';
            $item['duplicate']   = '0';
            $item['search_desc'] = '';
            $item['show_desc']   = '';
            $item['input_desc']  = '';
            $item['disp_cond']   = 0;
            $item['search_cond'] = 0;

            $item['default'] = '';

            $ret['update_date'] = $item;

            // Processing date and time (after)
            $ret['update_date_or_over']                  = $item;
            $ret['update_date_or_over']['is_range_item'] = true;
            $ret['update_date_or_over']['caption']       = getMDConst('_UPDATE_DATE') . '(' . getMDConst('_SINCE') . ')';

            // Processing date and time (previous)
            $ret['update_date_or_less']                  = $item;
            $ret['update_date_or_less']['is_range_item'] = true;
            $ret['update_date_or_less']['caption']       = getMDConst('_UPDATE_DATE') . '(' . getMDConst('_UNTIL') . ')';

            return $ret;
        }

        /**
         * Returns whether GD (gif, jpeg, png) is supported.
         *
         * @return Boolean true if GD (gif, jpeg, png) is supported, false otherwise
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

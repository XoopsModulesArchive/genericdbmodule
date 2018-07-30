<?php

$dirname = basename(dirname(__DIR__));

eval('function ' . $dirname . '_xgdb_search($keywords, $andor, $limit, $offset, $userid){
    return xgdb_search("' . $dirname . '", $keywords, $andor, $limit, $offset, $userid);
}');

if (!function_exists('xgdb_search')) {
    function xgdb_search($dirname, $keywords, $andor, $limit, $offset, $userid)
    {
        global $xoopsUser;
        require_once XOOPS_ROOT_PATH . '/modules/' . $dirname . '/include/functions.php';
        $myts = MyTextSanitizer::getInstance();
        $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();
        $data_tbl = $xoopsDB->prefix($dirname . '_xgdb_data');
        $ret = [];

        $module_handler = xoops_gethandler('module');
        $xoopsModule = $module_handler->getByDirname($dirname);

        if (is_object($xoopsUser)) {
            $gids = $xoopsUser->getGroups();
        } else {
            $gids = [3];
        }

        $item_defs = getItemDefs($dirname, $gids);
        $site_search_defs = getDefs($item_defs, 'site_search');

        // GP�ѿ����ͤΥޥ��å��������Ȥ�̵����
        if (get_magic_quotes_gpc()) {
            $_POST = array_map('stripSlashesDeep', $_POST);
            $_GET = array_map('stripSlashesDeep', $_GET);
        }

        $andor = mb_strtoupper($andor);
        if ('AND' != $andor && 'OR' != $andor && 'EXACT' != $andor) {
            $andor = 'AND';
        }
        $userid = intval($userid);

        $wheres = [];
        if (is_array($keywords)) {
            foreach ($keywords as $keyword) {
                $where = '(';
                foreach ($site_search_defs as $item_name => $item_def) {
                    if ('date' == $item_def['type']) {
                        if (false !== strtotime($keyword)) {
                            $where .= $item_name . " = '" . addslashes($keyword) . "' OR ";
                        }
                    } else {
                        $where .= $item_name . " LIKE '%" . addslashes($keyword) . "%' OR ";
                    }
                }
                $wheres[] = mb_substr($where, 0, -4) . ')';
            }
        }

        $sql = 'SELECT did, add_date, add_uid FROM ' . $xoopsDB->prefix($dirname . '_xgdb_data');
        if (0 < count($wheres) || 0 < $userid) {
            $sql .= ' WHERE ';
        }
        foreach ($wheres as $where) {
            $sql .= $where . ' ' . $andor . ' ';
        }
        if (0 < count($wheres)) {
            $sql = mb_substr($sql, 0, -1 * (mb_strlen($andor) + 2));
        }
        if (0 < $userid) {
            if (0 < count($wheres)) {
                $sql .= ' AND ';
            }
            $sql .= " add_uid = $userid";
        }
        $sql .= ' ORDER BY did DESC';

        $res = $xoopsDB->query($sql, $limit, $offset);
        while ([$did, $add_date, $add_uid] = $xoopsDB->fetchRow($res)) {
            $ret[] = ['link' => "detail.php?did=$did", 'title' => $xoopsModule->getVar('name'), 'time' => strtotime($add_date), 'uid' => $add_uid];
        }

        return $ret;
    }
}

<?php

$dirname = basename(dirname(dirname(__FILE__)));

eval('function ' . $dirname . '_xgdb_search($keywords, $andor, $limit, $offset, $userid){
	return xgdb_search("' . $dirname . '", $keywords, $andor, $limit, $offset, $userid);
}');

if (!function_exists('xgdb_search')) {
    function xgdb_search($dirname, $keywords, $andor, $limit, $offset, $userid) {
        global $xoopsUser;
        require_once XOOPS_ROOT_PATH . '/modules/' . $dirname . '/include/functions.php';
        $myts = &MyTextsanitizer::getInstance();
        $xoopsDB = &Database::getInstance();
        $data_tbl = $xoopsDB->prefix($dirname . '_xgdb_data');
        $ret = array();

        $module_handler = &xoops_gethandler('module');
        $xoopsModule = &$module_handler->getByDirname($dirname);

        if (is_object($xoopsUser)) $gids = $xoopsUser->getGroups();
        else $gids = array(3);

        $item_defs = getItemDefs($dirname, $gids);
        $site_search_defs = getDefs($item_defs, 'site_search');

        // GP変数の値のマジッククォートを無効化する
        if (get_magic_quotes_gpc()) {
            $_POST = array_map('stripSlashesDeep', $_POST);
            $_GET = array_map('stripSlashesDeep', $_GET);
        }

        $andor = strtoupper($andor);
        if ($andor != 'AND' && $andor != 'OR' && $andor != 'EXACT') $andor = 'AND';
        $userid = intval($userid);

        $wheres = array();
        if (is_array($keywords)) {
            foreach ($keywords as $keyword) {
                $where = '(';
                foreach ($site_search_defs as $item_name => $item_def) {
                    if ($item_def['type'] == 'date') {
                        if (strtotime($keyword) !== false) {
                            $where .= $item_name . " = '" . addslashes($keyword) . "' OR ";
                        }
                    } else {
                        $where .= $item_name . " LIKE '%" . addslashes($keyword) . "%' OR ";
                    }
                }
                $wheres[] = substr($where, 0, -4) . ')';
            }
        }

        $sql = "SELECT did, add_date, add_uid FROM " . $xoopsDB->prefix($dirname . '_xgdb_data');
        if (count($wheres) > 0 || $userid > 0) $sql .= ' WHERE ';
        foreach ($wheres as $where) {
            $sql .= $where . ' ' . $andor . ' ';
        }
        if (count($wheres) > 0) $sql = substr($sql, 0, -1 * (strlen($andor) + 2));
        if ($userid > 0) {
            if (count($wheres) > 0) $sql .= ' AND ';
            $sql .= " add_uid = $userid";
        }
        $sql .= " ORDER BY did DESC";

        $res = $xoopsDB->query($sql, $limit, $offset);
        while (list($did, $add_date, $add_uid) = $xoopsDB->fetchRow($res)) {
            $ret[] = array('link' => "detail.php?did=$did", 'title' => $xoopsModule->getVar('name'), 'time' => strtotime($add_date), 'uid' => $add_uid);
        }
        return $ret;
    }
}

?>
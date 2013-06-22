<?php

$dirname = basename(dirname(dirname(__FILE__)));

eval('function xoops_module_uninstall_' . $dirname . '($module){
    return xgdb_onuninstall($module, "' . $dirname . '");
}');

if (!function_exists('xgdb_onuninstall')) {
    function xgdb_onuninstall($module, $dirname) {
        global $ret;
        if (!is_array($ret)) $ret = array();
        $xoopsDB = &Database::getInstance();
        $mid = $module->getVar('mid');

        $upload_dir = opendir(XOOPS_UPLOAD_PATH . "/" . $dirname);
        while (false !== ($file = readdir($upload_dir))) {
            if ($file == "." || $file == "..") continue;
            unlink(XOOPS_UPLOAD_PATH . "/$dirname/$file");
        }
        closedir($upload_dir);
        rmdir(XOOPS_UPLOAD_PATH . "/" . $dirname);

        $sql_file = XOOPS_ROOT_PATH . '/modules/' . $dirname . '/sql/mysql.sql';
        $prefix = $xoopsDB->prefix() . '_' . $dirname;
        if (file_exists($sql_file)) {
            $ret[] = "SQL file found at <b>" . htmlspecialchars($sql_file) . "</b>.<br  /> Deleting tables...<br />";
            $sql_lines = file($sql_file);
            foreach ($sql_lines as $sql_line) {
                if (preg_match('/^CREATE TABLE \`?([a-zA-Z0-9_-]+)\`? /i', $sql_line, $regs)) {
                    $sql = 'DROP TABLE ' . addslashes($prefix . '_' . $regs[1]);
                    if (!$xoopsDB->query($sql)) {
                        $ret[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not drop table <b>' . htmlspecialchars($prefix . '_' . $regs[1]) . '</b>.</span><br />';
                    } else {
                        $ret[] = '&nbsp;&nbsp;Table <b>' . htmlspecialchars($prefix . '_' . $regs[1]) . '</b> dropped.<br />';
                    }
                }
            }
        }

        $tplfile_handler = &xoops_gethandler('tplfile');
        $templates = &$tplfile_handler->find(null, null, $mid);
        $count = count($templates);
        if ($count > 0) {
            $ret[] = 'Deleting templates...';
            for ($i = 0; $i < $count; $i++) {
                if (!$tplfile_handler->delete($templates[$i])) {
                    $ret[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not delete template ' . $templates[$i]->getVar('tpl_file', 's') . ' from the database. Template ID: <b>' . $templates[$i]->getVar('tpl_id', 's') . '</b></span><br />';
                } else {
                    $ret[] = '&nbsp;&nbsp;Template <b>' . $templates[$i]->getVar('tpl_file', 's') . '</b> deleted from the database. Template ID: <b>' . $templates[$i]->getVar('tpl_id', 's') . '</b><br />';
                }
            }
        }
        unset($templates);

        return true;
    }
}

?>
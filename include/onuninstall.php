<?php

$dirname = basename(dirname(__DIR__));

eval(
    'function xoops_module_uninstall_' . $dirname . '($module){
    return xgdb_onuninstall($module, "' . $dirname . '");
}'
);

if (!function_exists('xgdb_onuninstall')) {
    /**
     * @param $module
     * @param $dirname
     * @return bool
     */
    function xgdb_onuninstall($module, $dirname)
    {
        global $ret;

        $ret = [];

        $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();
        $mid     = $module->getVar('mid');

        $upload_dir = opendir(XOOPS_UPLOAD_PATH . '/' . $dirname);
        while (false !== ($file = readdir($upload_dir))) {
            if ('.' === $file || '..' === $file) {
                continue;
            }
            unlink(XOOPS_UPLOAD_PATH . "/$dirname/$file");
        }
        closedir($upload_dir);
        rmdir(XOOPS_UPLOAD_PATH . '/' . $dirname);

        $sql_file = XOOPS_ROOT_PATH . '/modules/' . $dirname . '/sql/mysql.sql';
        $prefix   = $xoopsDB->prefix() . '_' . $dirname;
        if (is_file($sql_file)) {
            $ret[]     = 'SQL file found at <b>' . htmlspecialchars($sql_file, ENT_QUOTES | ENT_HTML5) . '</b>.<br > Deleting tables...<br>';
            $sql_lines = file($sql_file);
            foreach ($sql_lines as $sql_line) {
                if (preg_match('/^CREATE TABLE \`?([a-zA-Z0-9_-]+)\`? /i', $sql_line, $regs)) {
                    $sql = 'DROP TABLE ' . addslashes($prefix . '_' . $regs[1]);
                    if ($xoopsDB->query($sql)) {
                        $ret[] = '&nbsp;&nbsp;Table <b>' . htmlspecialchars($prefix . '_' . $regs[1], ENT_QUOTES | ENT_HTML5) . '</b> dropped.<br>';
                    } else {
                        $ret[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not drop table <b>' . htmlspecialchars($prefix . '_' . $regs[1], ENT_QUOTES | ENT_HTML5) . '</b>.</span><br>';
                    }
                }
            }
        }

        $tplfileHandler = xoops_getHandler('tplfile');
        $templates      = $tplfileHandler->find(null, null, $mid);
        $count          = count($templates);
        if (0 < $count) {
            $ret[] = 'Deleting templates...';
            foreach ($templates as $iValue) {
                if ($tplfileHandler->delete($iValue)) {
                    $ret[] = '&nbsp;&nbsp;Template <b>' . $iValue->getVar('tpl_file', 's') . '</b> deleted from the database. Template ID: <b>' . $iValue->getVar('tpl_id', 's') . '</b><br>';
                } else {
                    $ret[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not delete template ' . $iValue->getVar('tpl_file', 's') . ' from the database. Template ID: <b>' . $iValue->getVar('tpl_id', 's') . '</b></span><br>';
                }
            }
        }
        unset($templates);

        return true;
    }
}

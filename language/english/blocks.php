<?php

$dirname      = basename(dirname(__DIR__, 2));
$lang_dirname = basename(__DIR__);
$affix        = mb_strtoupper(3 <= mb_strlen($dirname) ? mb_substr($dirname, 0, 3) : $dirname);

require_once XOOPS_ROOT_PATH . "/modules/$dirname/language/$lang_dirname/common.php";

$block_consts = [
    '_SHOW_NUM' => 'Show Num',
    '_ADD_DATE' => 'Add Date',
    '_UNAME'    => 'User Name',
    '_FILE'     => 'File',
];

foreach ($block_consts as $key => $value) {
    if (!defined('_MB_' . $affix . $key)) {
        define('_MB_' . $affix . $key, $value);
    }
}

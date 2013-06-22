<?php

require_once '../../mainfile.php';
require_once XOOPS_ROOT_PATH . '/header.php';
require_once './include/common.php';

$did = 0;
$col_name = '';
if (isset($_GET['did'])) $did = intval($_GET['did']);
foreach ($item_defs as $item_name => $item_def) {
    if ($item_name === $_GET['col_name']) $col_name = $_GET['col_name'];
}
if ($did < 1 || $col_name == '') {
    header("HTTP/1.1 404 Not Found");
    exit;
}

if (!isset($item_defs[$col_name])) {
    header("HTTP/1.1 404 Not Found");
    exit;
}

$sql = "SELECT $col_name FROM $data_tbl WHERE did = $did";
$res = $xoopsDB->query($sql);
if ($xoopsDB->getRowsNum($res) == 0) {
    header("HTTP/1.1 404 Not Found");
    exit;
}
list($file_name) = $xoopsDB->fetchRow($res);
$browser = getenv("HTTP_USER_AGENT");
if (preg_match("/MSIE/i", $browser)) {
    $original_file_name = mb_convert_encoding($file_name, 'Shift_JIS', _CHARSET);
} else {
    $original_file_name = mb_convert_encoding($file_name, 'UTF-8', _CHARSET);
}

$file_name = getRealFileName($did, $col_name, $file_name);
$filepath = XOOPS_ROOT_PATH . "/uploads/$dirname/$file_name";
if (!file_exists($filepath)) {
    header("HTTP/1.1 404 Not Found");
    exit;
}

header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Disposition: attachment; filename=\"$original_file_name\"");
header("Content-Length: " . filesize($filepath));
header("Content-Type: application/octet-stream;");

$fp = fopen($filepath, "r");
while (!feof($fp))
    echo fgets($fp, 1024);
fclose($fp);

exit;

?>

<?php

$common_consts = array(
        '_COMMA'        => '��',
        '_NOT_SELECTED' => '̤����',
        '_MORE_THAN'    => '�ʾ�',
        '_LESS_THAN'    => '�ʲ�');

foreach ($common_consts as $key => $value) {
    if (!defined('_' . $affix . $key)) {
        define('_' . $affix . $key, $value);
    }
}

?>
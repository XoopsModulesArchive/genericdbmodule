<?php

$common_consts = array(
        '_COMMA'        => ', ',
        '_NOT_SELECTED' => 'Not Selected',
        '_MORE_THAN'    => 'And Over',
        '_LESS_THAN'    => 'Or Less');

foreach ($common_consts as $key => $value) {
    if (!defined('_' . $affix . $key)) {
        define('_' . $affix . $key, $value);
    }
}

?>
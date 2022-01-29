<?php

$moduleDirName = basename(__DIR__);
$GLOBALS['xoopsOption']['template_main'] = $moduleDirName . '_xgdb_index.tpl';

require_once dirname(__DIR__, 2) . '/mainfile.php';
require_once XOOPS_ROOT_PATH . '/header.php';
require_once __DIR__ . '/include/common.php';


if (isset($_POST['op'])) {
    $op = $_POST['op'];
} elseif (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = '';
}

$errors      = [];
$search_defs = getDefs($item_defs, 'search');

// Search process
if ('search' === $op || 'back_search' === $op) {
    $queries = 'op=search&amp;';
    if ('search' === $op) {
        unset($_SESSION['search_conds']);
    }

    foreach ($search_defs as $item_name => $item_def) {
        $$item_name = initSearchInput($op, $item_name, $search_defs, $errors);

        if ('' !== $$item_name) {
            if (is_array($$item_name)) {
                foreach ($$item_name as $value) {
                    $queries .= $item_name . '[]=' . urlencode(htmlspecialchars($value, ENT_QUOTES | ENT_HTML5)) . '&amp;';
                }
            } else {
                $queries .= $item_name . '=' . urlencode(htmlspecialchars($$item_name, ENT_QUOTES | ENT_HTML5)) . '&amp;';
            }
        }

        $andor_item_name  = $item_name . '_andor';
        $$andor_item_name = '';
        if ('search' === $op) {
            if (isset($_POST[$andor_item_name]) && '' !== $_POST[$andor_item_name]) {
                $$andor_item_name                           = $_POST[$andor_item_name];
                $_SESSION['search_conds'][$andor_item_name] = $$andor_item_name;
            } elseif (isset($_GET[$andor_item_name]) && '' !== $_GET[$andor_item_name]) {
                $$andor_item_name                           = $_GET[$andor_item_name];
                $_SESSION['search_conds'][$andor_item_name] = $$andor_item_name;
            }
        } elseif ('back_search' === $op) {
            if (isset($_SESSION['search_conds'][$andor_item_name]) && '' !== $_SESSION['search_conds'][$andor_item_name]) {
                $$andor_item_name = $_SESSION['search_conds'][$andor_item_name];
            }
        }
        if ('' !== $$andor_item_name) {
            $queries .= $andor_item_name . '=' . urlencode(htmlspecialchars($$andor_item_name, ENT_QUOTES | ENT_HTML5)) . '&amp;';
        }
    }

    $params = ['order_item', 'order', 'start'];
    foreach ($params as $param_name) {
        if ('search' === $op) {
            if (isset($_POST[$param_name]) && '' !== $_POST[$param_name]) {
                $$param_name                           = $_POST[$param_name];
                $_SESSION['search_conds'][$param_name] = $$param_name;
                $queries                               .= $param_name . '=' . urlencode(htmlspecialchars($$param_name, ENT_QUOTES | ENT_HTML5)) . '&amp;';
            } elseif (isset($_GET[$param_name]) && '' !== $_GET[$param_name]) {
                $$param_name                           = $_GET[$param_name];
                $_SESSION['search_conds'][$param_name] = $$param_name;
                $queries                               .= $param_name . '=' . urlencode(htmlspecialchars($$param_name, ENT_QUOTES | ENT_HTML5)) . '&amp;';
            } else {
                $$param_name = '';
            }
        } elseif ('back_search' === $op) {
            if (isset($_SESSION['search_conds'][$param_name]) && '' !== $_SESSION['search_conds'][$param_name]) {
                $$param_name                           = $_SESSION['search_conds'][$param_name];
                $_SESSION['search_conds'][$param_name] = $$param_name;
                $queries                               .= $param_name . '=' . urlencode(htmlspecialchars($$param_name, ENT_QUOTES | ENT_HTML5)) . '&amp;';
            } else {
                $$param_name = '';
            }
        }
    }

    if (0 == count($errors)) {
        $sql = 'SELECT d.*, u.uname';
        $sql .= " FROM $data_tbl AS d LEFT OUTER JOIN $users_tbl AS u ON d.add_uid = u.uid";

        // Generate query after WHERE section
        $where = ' WHERE ';
        foreach ($search_defs as $item_name => $item_def) {
            // Numerical value
            if ('number' === $item_def['type'] && !isset($item_def['is_range_item'])) {
                $item_name_or_over = $item_name . '_or_over';
                $item_name_or_less = $item_name . '_or_less';
                if ('' !== $$item_name_or_over) {
                    if ('int' === $item_def['value_type']) {
                        $where .= " d.$item_name >= '" . addslashes($$item_name_or_over) . "' AND ";
                    } elseif ('float' === $item_def['value_type']) {
                        $where .= " d.$item_name >= '" . addslashes((float)$$item_name_or_over - 0.000001) . "' AND ";
                    }
                }
                if ('' !== $$item_name_or_less) {
                    if ('int' === $item_def['value_type']) {
                        $where .= " d.$item_name <= '" . addslashes($$item_name_or_less) . "' AND ";
                    } elseif ('float' === $item_def['value_type']) {
                        $where .= " d.$item_name <= '" . addslashes((float)$$item_name_or_less + 0.000001) . "' AND ";
                    }
                }
            } elseif ('date' === $item_def['type'] && !isset($item_def['is_range_item'])) {
                // date
                $item_name_or_over = $item_name . '_or_over';
                $item_name_or_less = $item_name . '_or_less';
                if ('' !== $$item_name_or_over) {
                    $where .= " d.$item_name >= '" . addslashes($$item_name_or_over) . "' AND ";
                }
                if ('' !== $$item_name_or_less) {
                    $where .= " d.$item_name <= '" . addslashes($$item_name_or_less) . "' AND ";
                }
            } elseif ('' !== $$item_name) {
                // For non-numeric values and dates
                $andor_item_name = $item_name . '_andor';
                if ('' === $$andor_item_name) {
                    $$andor_item_name = (int)$item_def['search_cond'];
                }

                // Text area (fuzzy match)
                if ('tarea' === $item_def['type'] || 'xtarea' === $item_def['type']) {
                    $where .= " d.$item_name LIKE '%" . addslashes($$item_name) . "%' AND ";
                } elseif ('text' === $item_def['type'] || 'image' === $item_def['type'] || 'file' === $item_def['type']) {
                    // Text boxes (strings), files, images
                    if ($$andor_item_name) {
                        $where .= " d.$item_name = '" . addslashes($$item_name) . "' AND ";
                    } else {
                        $where .= " d.$item_name LIKE '%" . addslashes($$item_name) . "%' AND ";
                    }
                } elseif ('radio' === $item_def['type'] || 'select' === $item_def['type']) {
                    // Radio buttons, pull-down menu
                    $where .= ' (';
                    foreach ($$item_name as $value) {
                        $where .= " d.$item_name = '" . addslashes($value) . "' OR ";
                    }
                    $where = mb_substr($where, 0, -4) . ') AND ';
                } elseif ('cbox' === $item_def['type'] || 'mselect' === $item_def['type']) {
                    // Checkbox, list box
                    if ($$andor_item_name) {
                        $where .= " d.$item_name = '";
                        foreach ($$item_name as $value) {
                            $where .= addslashes($value) . '|';
                        }
                        $where = mb_substr($where, 0, -1) . "' AND ";
                    } else {
                        $where .= ' (';
                        foreach ($$item_name as $value) {
                            $where .= " d.$item_name LIKE '%" . addslashes($value) . "%' OR ";
                        }
                        $where = mb_substr($where, 0, -4) . ') AND ';
                    }
                }
            }
        }

        if (' WHERE ' !== $where) {
            $sql .= mb_substr($where, 0, -5);
        }

        if (array_key_exists($order_item, $item_defs) && ('desc' === $order || 'asc' === $order)) {
            $sql .= " ORDER BY d.$order_item $order";
        } elseif ('did' === $order_item && ('desc' === $order || 'asc' === $order)) {
            $sql .= " ORDER BY d.did $order";
        } elseif ('uname' === $order_item && ('desc' === $order || 'asc' === $order)) {
            $sql .= " ORDER BY u.uname $order";
        } elseif ('add_date' === $order_item && ('desc' === $order || 'asc' === $order)) {
            $sql .= " ORDER BY d.add_date $order";
        } else {
            $sql .= ' ORDER BY d.did ASC';
        }
        $res   = $xoopsDB->query($sql);
        $total = $xoopsDB->getRowsNum($res);

        // Page switching
        $start = (int)$start;
        $xoopsTpl->assign('start', $start);
        if ('' !== $queries) {
            $queries = mb_substr($queries, 0, -5);
        }
        $xoopsTpl->assign('queries', $queries);
        $xoopsTpl->assign('order_item', $order_item);
        $xoopsTpl->assign('order', $order);
        if ($total > $cfg_result_num) {
            require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
            $pagenavi      = new \XoopsPageNav($total, $cfg_result_num, $start, 'start', $queries);
            $pagenavi_html = $pagenavi->renderNav();
            $xoopsTpl->assign('pagenavi_html', $pagenavi_html);
            $res = $xoopsDB->query($sql, $cfg_result_num, $start);
        }
        $last = $start + $cfg_result_num;
        if ($last > $total) {
            $last = $total;
        }
        $pagenavi_info = sprintf(getMDConst('_PAGENAVI_INFO'), number_format($total), number_format($start + 1), number_format($last));
        $xoopsTpl->assign('pagenavi_info', $pagenavi_info);

        // Display value assignment
        while (false !== ($row = $xoopsDB->fetchArray($res))) {
            $info = [];
            foreach ($row as $key => $value) {
                if ('did' === $key || 'add_uid' === $key || 'uname' === $key) {
                    $info[$key] = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5);
                } elseif ('add_date' === $key) {
                    $info[$key] = date($cfg_date_format . ' ' . $cfg_time_format, strtotime($value));
                } elseif (!isset($item_defs[$key])) {
                    continue;
                } elseif ('text' === $item_defs[$key]['type'] || 'number' === $item_defs[$key]['type'] || 'radio' === $item_defs[$key]['type'] || 'select' === $item_defs[$key]['type'] || 'date' === $item_defs[$key]['type']) {
                    $info[$key] = sanitize($value, $item_defs[$key]);
                } elseif ('cbox' === $item_defs[$key]['type'] || 'mselect' === $item_defs[$key]['type']) {
                    $values     = string2array($value);
                    $info[$key] = '';
                    foreach ($values as $value) {
                        $info[$key] .= sanitize($value, $item_defs[$key]) . '<br>';
                    }
                } elseif ('tarea' === $item_defs[$key]['type'] || 'xtarea' === $item_defs[$key]['type']) {
                    $info[$key] = $myts->displayTarea($value, $item_defs[$key]['html'], $item_defs[$key]['smily'], $item_defs[$key]['xcode'], $item_defs[$key]['image'], $item_defs[$key]['br']);
                } elseif ('image' === $item_defs[$key]['type'] && '' != $value) {
                    $item_defs[$key]['width'] = getImageWidth($module_upload_dir . '/' . getRealFileName($row['did'], $key, $value), $cfg_list_img_wd);
                    $info[$key]               = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5);
                } elseif ('file' === $item_defs[$key]['type'] && '' != $value) {
                    $info[$key] = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5);
                }
            }
            $xoopsTpl->append('infos', $info);
        }
        $list_defs = getDefs($item_defs, 'list');
        $xoopsTpl->assign('list_item_num', count($list_defs) + 1);
        $xoopsTpl->assign('item_defs', $item_defs);
        $xoopsTpl->assign('op', 'search');
    }
} else {
    foreach ($search_defs as $item_name => $item_def) {
        $$item_name = '';
        if ('text' === $item_def['type'] || 'cbox' === $item_def['type'] || 'mselect' === $item_def['type'] || 'file' === $item_def['type'] || 'image' === $item_def['type']) {
            $andor_item_name  = $item_name . '_andor';
            $$andor_item_name = $item_def['search_cond'];
        }
    }
}

// Form generation
foreach ($search_defs as $item_name => $item_def) {
    if ('text' === $item_def['type'] || 'tarea' === $item_def['type'] || 'xtarea' === $item_def['type'] || 'file' === $item_def['type'] || 'image' === $item_def['type']) {
        $search_defs[$item_name]['value'] = makeTextForm($item_name, $item_def, $$item_name);
    } elseif ('number' === $item_def['type'] && !isset($item_def['is_range_item'])) {
        $item_name_or_over                = $item_name . '_or_over';
        $item_name_or_less                = $item_name . '_or_less';
        $search_defs[$item_name]['value'] = makeTextForm($item_name_or_over, $item_def, $$item_name_or_over);
        $search_defs[$item_name]['value'] .= ' ' . getMDConst('_OR_OVER') . ' - ';
        $search_defs[$item_name]['value'] .= makeTextForm($item_name_or_less, $item_def, $$item_name_or_less) . ' ' . getMDConst('_OR_LESS');
        if (isset($search_defs[$item_name_or_over]['error'])) {
            if (!isset($search_defs[$item_name]['error'])) {
                $search_defs[$item_name]['error'] = '';
            }
            $search_defs[$item_name]['error'] .= $search_defs[$item_name_or_over]['error'];
        }
        if (isset($search_defs[$item_name_or_less]['error'])) {
            if (!isset($search_defs[$item_name]['error'])) {
                $search_defs[$item_name]['error'] = '';
            }
            $search_defs[$item_name]['error'] .= $search_defs[$item_name_or_less]['error'];
        }
    } elseif ('date' === $item_def['type'] && !isset($item_def['is_range_item'])) {
        $item_name_or_over                = $item_name . '_or_over';
        $item_name_or_less                = $item_name . '_or_less';
        $search_defs[$item_name]['value'] = makeDateForm($item_name_or_over, $item_def, $$item_name_or_over);
        $search_defs[$item_name]['value'] .= ' ' . getMDConst('_SINCE') . ' - ';
        $search_defs[$item_name]['value'] .= makeDateForm($item_name_or_less, $item_def, $$item_name_or_less) . ' ' . getMDConst('_UNTIL');
        if (isset($search_defs[$item_name_or_over]['error'])) {
            if (!isset($search_defs[$item_name]['error'])) {
                $search_defs[$item_name]['error'] = '';
            }
            $search_defs[$item_name]['error'] .= $search_defs[$item_name_or_over]['error'];
        }
        if (isset($search_defs[$item_name_or_less]['error'])) {
            if (!isset($search_defs[$item_name]['error'])) {
                $search_defs[$item_name]['error'] = '';
            }
            $search_defs[$item_name]['error'] .= $search_defs[$item_name_or_less]['error'];
        }
    } elseif ('cbox' === $item_def['type'] || 'radio' === $item_def['type']) {
        $search_defs[$item_name]['value'] = makeCboxForm($item_name, $item_def, $$item_name);
    } elseif ('select' === $item_def['type'] || 'mselect' === $item_def['type']) {
        $search_defs[$item_name]['value'] = makeMSelectForm($item_name, $item_def, $$item_name);
    }

    if ('text' === $item_def['type'] || 'file' === $item_def['type'] || 'image' === $item_def['type']) {
        $andor_item_name                      = $item_name . '_andor';
        $search_defs[$item_name]['condition'] = makeCondForm($andor_item_name, [getMDConst('_COMP_MATCH') => 1, getMDConst('_PART_MATCH') => 0], $$andor_item_name);
    } elseif ('radio' === $item_def['type'] || 'select' === $item_def['type']) {
        $search_defs[$item_name]['condition'] = '<br>' . getMDConst('_COND_LABEL') . ' ' . getMDConst('_OR_MATCH');
    } elseif ('tarea' === $item_def['type'] || 'xtarea' === $item_def['type']) {
        $search_defs[$item_name]['condition'] = '<br>' . getMDConst('_COND_LABEL') . ' ' . getMDConst('_PART_MATCH');
    } elseif ('cbox' === $item_def['type'] || 'mselect' === $item_def['type']) {
        $andor_item_name                      = $item_name . '_andor';
        $search_defs[$item_name]['condition'] = makeCondForm($andor_item_name, [getMDConst('_AND_MATCH') => 1, getMDConst('_OR_MATCH') => 0], $$andor_item_name);
    }
}
$xoopsTpl->assign('search_defs', $search_defs);
$xoopsTpl->assign('errors', $errors);

require_once XOOPS_ROOT_PATH . '/footer.php';

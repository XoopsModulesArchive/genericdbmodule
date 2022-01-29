<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @since
 * @author       XOOPS Development Team
 * @version      $Id $
 */

use Xmf\Module\Admin;

require_once dirname(__DIR__, 3) . '/include/cp_header.php';
require_once __DIR__ . '/admin_header.php';

xoops_cp_header();

$adminObject = Admin::getInstance();

$adminObject->displayNavigation('index.php');
$adminObject->displayIndex();

require __DIR__ . '/admin_footer.php';

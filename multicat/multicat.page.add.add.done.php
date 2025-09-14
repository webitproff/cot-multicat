<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=page.add.add.done
[END_COT_EXT]
==================== */

/**
 * Multicat plugin for Page Module, CMF Cotonti Siena v.0.9.26, PHP v.8.4+, MySQL v.8.0
 * Filename: multicat.page.add.add.done.php
 * Purpose: Хук для подключения к page.add.add.done в modules\page\inc\page.functions.php str 728. Сохраняет категории (structure_id) после добавления страницы.
 * Date=2025-09-14
 * @package multicat
 * @version 1.1.0
 * @author webitproff
 * @copyright Copyright (c) webitproff 2025 | https://github.com/webitproff
 * @license BSD
 */


defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('multicat', 'plug');

if (!isset($_POST['rcat']) || !is_array($_POST['rcat']) || empty($_POST['rcat'])) {
    cot_error($L['multicat_error_no_category']);
} else {
    multicat_save_cats($newid, $_POST['rcat']);
}
<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=page.edit.update.import
[END_COT_EXT]
==================== */

/**
 * Multicat plugin for Page Module, CMF Cotonti Siena v.0.9.26, PHP v.8.4+, MySQL v.8.0
 * Filename: multicat.page.edit.import.php
 * Purpose: Хук для page.edit.update.import, modules\page\inc\page.edit.php, str 72. Импортирует категории из POST и устанавливает первую как page_cat.
 * Date=2025-09-14
 * @package multicat
 * @version 1.1.0
 * @author webitproff
 * @copyright Copyright (c) webitproff 2025 | https://github.com/webitproff
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

if (!isset($_POST['rcat']) || !is_array($_POST['rcat']) || empty($_POST['rcat'])) {
    cot_error($L['multicat_error_no_category']);
} else {
    global $db, $db_structure;
    $first_cat_id = (int) reset($_POST['rcat']);
    $sql = "SELECT structure_code FROM $db_structure
            WHERE structure_id = $first_cat_id AND structure_area = 'page'";
    $rpage['page_cat'] = $db->query($sql)->fetchColumn() ?: $rpage['page_cat'];
}

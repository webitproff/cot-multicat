<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=page.edit.update.import
[END_COT_EXT]
==================== */

/**
 * Multicat plugin for Page Module, CMF Cotonti v.1.0.0, PHP v.8.4+, MySQL v.8.0
 * Filename: plugins/multicat/multicat.page.edit.import.php
 * Purpose: Хук для page.edit.update.import, modules\page\inc\page.edit.php.
 *          Импортирует категории из POST и устанавливает первую как page_cat.
 *          Если POST['rcat'] пуст, но у страницы уже есть основная категория в БД —
 *          сохраняем её (без ошибки).
 * Date=Sep 11, 2026
 * @package multicat
 * @version 1.7.9
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

global $db, $db_structure;

/* --- Собираем выбранные ID категорий из POST --- */
$rcats = [];
if (isset($_POST['rcat']) && is_array($_POST['rcat'])) {
    foreach ($_POST['rcat'] as $v) {
        $v = (int)$v;
        if ($v > 0) {
            $rcats[] = $v;
        }
    }
    $rcats = array_values(array_unique($rcats));
}

if (!empty($rcats)) {
    /* --- Есть выбранные категории: первая становится основной --- */
    $first_cat_id = (int) reset($rcats);
    $code = $db->query(
        "SELECT structure_code FROM $db_structure
         WHERE structure_id = ? AND structure_area = 'page'",
        [$first_cat_id]
    )->fetchColumn();

    $rpage['page_cat'] = $code ?: $rpage['page_cat'];
} else {
    /* --- POST['rcat'] пуст: смотрим, есть ли уже основная категория в БД --- */
    $edit_id = cot_import('id', 'G', 'INT');
    if ($edit_id <= 0) {
        $edit_id = cot_import('id', 'P', 'INT');
    }

    $existing_cat = '';
    if ($edit_id > 0) {
        $existing_cat = (string) $db->query(
            "SELECT page_cat FROM " . Cot::$db->pages . " WHERE page_id = ?",
            [$edit_id]
        )->fetchColumn();
    }

    if ($existing_cat === '') {
        /* Новая страница без категорий — это ошибка */
        cot_error($L['multicat_error_no_category']);
    } else {
        /* Страница уже имеет основную категорию — оставляем её */
        $rpage['page_cat'] = $existing_cat;
    }
}
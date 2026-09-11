<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=page.edit.tags
[END_COT_EXT]
==================== */

/**
 * Multicat plugin for Page Module, CMF Cotonti v.1.0.0, PHP v.8.5+, MySQL v.8.4
 * Filename: plugins/multicat/multicat.page.edit.tags.php
 * Purpose: Хук page.edit.tags. Подключается в page.edit.php перед выводом
 *          шаблона. В области видимости доступны $pag (данные страницы),
 *          $id, $t. Передаёт в шаблон теги {PAGEFORM_CAT} (список чекбоксов
 *          категорий с отмеченной основной и текущими мультикатегориями)
 *          и {PAGEFORM_CAT_HINT}.
 * Date=Sep 12, 2026
 * @package multicat
 * @version 2.0.0
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('multicat', 'plug');
//require_once cot_langfile('multicat', 'plug'); уже в файле функций 

$id = cot_import('id', 'G', 'INT');

// Текущие мультикатегории страницы (из БД).
$selected = ($id > 0) ? multicat_get_cats($id) : [];

// Основная категория должна быть отмечена в списке чекбоксов.
// В page.edit.php переменная называется $pag (это $row_page).
global $pag;
$main_code = '';
if ($id > 0) {
    if (isset($pag['page_cat'])) {
        $main_code = (string)$pag['page_cat'];
    }
    if ($main_code === '') {
        $main_code = (string) Cot::$db->query(
            "SELECT page_cat FROM " . Cot::$db->pages . " WHERE page_id = ?",
            [$id]
        )->fetchColumn();
    }
}
if ($main_code !== '') {
    $main_cat_id = multicat_code_to_id($main_code);
    if ($main_cat_id > 0 && !in_array($main_cat_id, $selected)) {
        $selected[] = $main_cat_id;
    }
}

$t->assign([
    'PAGEFORM_CAT'      => multicat_build_checkbox_html($selected),
    'PAGEFORM_CAT_HINT' => $L['multicat_select'],
]);

$id = cot_import('id', 'G', 'INT');

// Текущие мультикатегории страницы (из БД).
$selected = ($id > 0) ? multicat_get_cats($id) : [];

// Основная категория должна быть отмечена в списке чекбоксов.
// В page.edit.php переменная называется $pag (это $row_page).
global $pag;
$main_code = '';
if ($id > 0) {
    if (isset($pag['page_cat'])) {
        $main_code = (string)$pag['page_cat'];
    }
    if ($main_code === '') {
        $main_code = (string) Cot::$db->query(
            "SELECT page_cat FROM " . Cot::$db->pages . " WHERE page_id = ?",
            [$id]
        )->fetchColumn();
    }
}
if ($main_code !== '') {
    $main_cat_id = multicat_code_to_id($main_code);
    if ($main_cat_id > 0 && !in_array($main_cat_id, $selected)) {
        $selected[] = $main_cat_id;
    }
}

$t->assign([
    'PAGEFORM_CAT'      => multicat_build_checkbox_html($selected),
    'PAGEFORM_CAT_HINT' => $L['multicat_select'],
]);
<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=page.list.query
[END_COT_EXT]
==================== */

/**
 * Multicat plugin for Page Module, CMF Cotonti v.1.0.0, PHP v.8.5+, MySQL v.8.4
 * Filename: plugins/multicat/multicat.page.list.query.php
 * Purpose: Хук для page.list.query, modules\page\inc\page.list.php.
 *          Расширяет фильтр списка страниц по выбранной категории $c,
 *          учитывая мультикатегории из таблицы cot_page_multicats.
 *          Если модуль уже установил $where['cat'] — расширяем его через OR,
 *          если нет — формируем своё условие с учётом подкатегорий.
 * Date=Sep 12, 2026
 * @package multicat
 * @version 2.0.0
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

Cot::$db->registerTable('page_multicats');

global $c, $db, $db_structure, $db_page_multicats, $where;

// Если категория не задана — ничего не делаем
if (empty($c)) {
    return;
}

// Получаем structure_id по коду категории (area = 'page')
$cat_id = (int)$db->query(
    "SELECT structure_id
       FROM $db_structure
      WHERE structure_code = " . $db->quote($c) . "
        AND structure_area = 'page'"
)->fetchColumn();

if ($cat_id <= 0) {
    return;
}

// Дополнительное условие: страница привязана к этой категории
// через таблицу мультикатегорий. Используем EXISTS — быстрее, чем IN.
$multi_exists = "EXISTS (
    SELECT 1
      FROM $db_page_multicats AS pc
     WHERE pc.pcat_page_id = p.page_id
       AND pc.pcat_cat_id = " . $cat_id . "
)";

if (isset($where['cat'])) {
    // Модуль уже установил условие по категории (с подкатегориями и правами) —
    // расширяем его через OR, не теряя исходную логику.
    $where['cat'] = "(" . $where['cat'] . " OR " . $multi_exists . ")";
} else {
    // Модуль не установил условие — формируем своё: страницы в выбранной
    // категории и всех её подкатегориях ИЛИ привязанные через мультикатегории.
    $catsub = cot_structure_children('page', $c, true);
    $catsub[] = $c;
    $catsub_quoted = array_map([$db, 'quote'], $catsub);

    $main_cond = "p.page_cat IN (" . implode(',', $catsub_quoted) . ")";

    $where['cat'] = "(" . $main_cond . " OR " . $multi_exists . ")";
}
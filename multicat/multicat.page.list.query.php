<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=page.list.query
[END_COT_EXT]
==================== */

/**
 * Multicat plugin for Page Module, CMF Cotonti Siena v.0.9.26, PHP v.8.4+, MySQL v.8.0
 * Filename: multicat.page.list.query.php
 * Purpose: Хук для page.list.query, modules\page\inc\page.list.php, str 195. Фильтр списка страниц по выбранной категории $c, учитывая мультикатегории.
 * Date=2025-09-14
 * @package multicat
 * @version 1.1.0
 * @author webitproff
 * @copyright Copyright (c) webitproff 2025 | https://github.com/webitproff
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

global $c, $db, $db_structure, $db_page_multicats, $where;

if (!empty($c)) {
    // Получаем ID структуры по коду категории
    $sql = "SELECT structure_id
              FROM $db_structure
             WHERE structure_code = " . $db->quote($c) . "
               AND structure_area = 'page'";
    $cat_id = (int)$db->query($sql)->fetchColumn();

    if ($cat_id) {
        // Если категория найдена, подменяем условие фильтра
        // Сохраняем возможность фильтрации по основной категории + мультикатегории
        if (isset($where['cat'])) {
            // объединяем с существующим условием
            $where['cat'] = "(" . $where['cat'] . " OR EXISTS (
                SELECT 1
                  FROM $db_page_multicats AS pc
                 WHERE pc.pcat_page_id = p.page_id
                   AND pc.pcat_cat_id = " . $cat_id . "
            ))";
        } else {
            // ставим новое условие, если ещё нет
            $where['cat'] = "(p.page_cat = " . $db->quote($c) . " OR EXISTS (
                SELECT 1
                  FROM $db_page_multicats AS pc
                 WHERE pc.pcat_page_id = p.page_id
                   AND pc.pcat_cat_id = " . $cat_id . "
            ))";
        }
    }
}

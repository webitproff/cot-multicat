<?php

/**
 * Multicat plugin for Page Module, CMF Cotonti v.1.0.0, PHP v.8.4+, MySQL v.8.0
 * Filename: plugins/multicat/inc/multicat.functions.php
 * Purpose: Основные функции для обработки множественных категорий страниц в плагине Multicat. Использует $structure['page'] для получения заголовков и прямые SQL-запросы для работы с cot_page_multicats.
 * Date=Sep 11, 2026
 * @package multicat
 * @version 1.7.9
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

require_once cot_langfile('multicat', 'plug');

Cot::$db->registerTable('page_multicats');

/**
 * Получает список ID категорий (structure_id) для указанной страницы.
 *
 * @param int $page_id ID страницы.
 * @return array Массив ID категорий (structure_id).
 */
function multicat_get_cats($page_id)
{
    global $db, $db_page_multicats;
    $page_id = (int)$page_id;
    $sql = "SELECT pcat_cat_id FROM $db_page_multicats WHERE pcat_page_id = $page_id";
    $res = $db->query($sql);
    return array_column($res->fetchAll(), 'pcat_cat_id');
}

/**
 * Получает заголовки категорий для указанной страницы, используя structure_id.
 *
 * @param int $page_id ID страницы.
 * @return array Массив заголовков категорий.
 */
function multicat_get_cat_titles($page_id)
{
    global $db, $db_structure, $structure;
    $cats = multicat_get_cats($page_id);
    $titles = [];
    if (!empty($cats)) {
        // Расширяем SQL, чтобы получить structure_code (нужен для fallback и отладки)
        $sql = "SELECT structure_id, structure_title, structure_code FROM $db_structure
                 WHERE structure_id IN (" . implode(',', array_map('intval', $cats)) . ")
                   AND structure_area = 'page'";
        $res = $db->query($sql);

        // Индексируем по structure_id
        $db_cats = [];
        foreach ($res->fetchAll() as $row) {
            $db_cats[$row['structure_id']] = $row;
        }

        foreach ($cats as $cat_id) {
            if (isset($db_cats[$cat_id])) {
                $titles[] = $db_cats[$cat_id]['structure_title'];
            } else {
                // Fallback: ищем в $structure['page'] по structure_id
                $found = false;
                foreach ($structure['page'] as $struct_code => $cat_data) {
                    if (isset($cat_data['id']) && $cat_data['id'] == $cat_id) {
                        $titles[] = $cat_data['title'] ?? $struct_code;
                        $found = true;
                        break;
                    }
                }
                // Если не нашли — пропускаем
                if (!$found) {
                    continue;
                }
            }
        }
    }
    return $titles;
}

/**
 * Сохраняет категории (structure_id) для страницы, заменяя существующие связи
 * в таблице cot_page_multicats.
 *
 * Логика:
 *   1. Удаляет ВСЕ существующие связи указанной страницы (безусловно).
 *   2. Если переданный массив пуст — это валидная операция «снять все
 *      мультикатегории»; функция возвращает true, ничего не вставляя.
 *   3. Иначе вставляет по одной записи на каждый уникальный положительный
 *      structure_id.
 *
 * Замечание:
 *   Раньше при пустом массиве функция возвращала false и НЕ удаляла старые
 *   связи. Это не давало пользователю снять все мультикатегории у страницы
 *   через форму редактирования. Теперь удаление выполняется всегда, а пустой
 *   набор трактуется как «очистить связи».
 *
 * @param int   $page_id ID страницы (page_id).
 * @param array $cats    Массив ID категорий (structure_id, area = 'page').
 * @return bool          Всегда true — операция выполнена.
 */
function multicat_save_cats($page_id, $cats)
{
    global $db, $db_page_multicats;

    // Приводим ID страницы к целому
    $page_id = (int)$page_id;

    // Нормализуем входной массив: только целые, только уникальные,
    // без нулей и пустых значений
    $cats = is_array($cats)
        ? array_unique(array_map('intval', array_filter($cats)))
        : [];

    // Шаг 1. Удаляем все существующие связи страницы — безусловно.
    // Это позволяет корректно обработать случай «пользователь снял все
    // галочки в дереве мультикатегорий»: старые связи не останутся висеть.
    $db->delete($db_page_multicats, "pcat_page_id = $page_id");

    // Шаг 2. Пустой набор — валидная операция: очистка всех мультикатегорий.
    // Ничего не вставляем, возвращаем true как признак успешного выполнения.
    if (empty($cats)) {
        return true;
    }

    // Шаг 3. Вставляем по одной записи на каждый уникальный structure_id
    foreach ($cats as $cat_id) {
        $db->insert($db_page_multicats, ['pcat_page_id' => $page_id, 'pcat_cat_id' => $cat_id]);
    }

    return true;
}
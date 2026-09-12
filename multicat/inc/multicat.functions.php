<?php

/**
 * Multicat plugin for Page Module, CMF Cotonti v.1.0.0, PHP v.8.5+, MySQL v.8.4
 * Filename: plugins/multicat/inc/multicat.functions.php
 * Purpose: Основные функции для обработки множественных категорий страниц в плагине Multicat. Использует $structure['page'] для получения заголовков и прямые SQL-запросы для работы с cot_page_multicats.
 * Date=Sep 12, 2026
 * @package multicat
 * @version 2.0.0
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

require_once cot_langfile('multicat', 'plug'); // локализации

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
 *   2. Основная категория ($main_cat_id) всегда входит в набор связей,
 *      даже если её нет в $cats.
 *   3. Если итоговый набор пуст — это валидная операция «снять все
 *      мультикатегории»; функция возвращает true, ничего не вставляя.
 *
 * @param int   $page_id     ID страницы (page_id).
 * @param array $cats        Массив ID категорий (structure_id, area = 'page') —
 *                           дополнительные мультикатегории из чекбоксов.
 * @param int   $main_cat_id ID основной категории (structure_id). Может быть 0,
 *                           если по какой-то причине основная неизвестна.
 * @return bool              Всегда true — операция выполнена.
 */
function multicat_save_cats($page_id, $cats, $main_cat_id = 0)
{
    global $db, $db_page_multicats;

    $page_id     = (int)$page_id;
    $main_cat_id = (int)$main_cat_id;

    // Нормализуем входной массив: только целые, только уникальные,
    // без нулей и пустых значений
    $cats = is_array($cats)
        ? array_unique(array_map('intval', array_filter($cats)))
        : [];

    // Основная категория всегда должна быть в наборе связей
    if ($main_cat_id > 0) {
        $cats[] = $main_cat_id;
        $cats = array_unique($cats);
    }

    // Шаг 1. Удаляем все существующие связи страницы — безусловно.
    $db->delete($db_page_multicats, "pcat_page_id = $page_id");

    // Шаг 2. Пустой набор — валидная операция: очистка всех мультикатегорий.
    if (empty($cats)) {
        return true;
    }

    // Шаг 3. Вставляем по одной записи на каждый уникальный structure_id
    foreach ($cats as $cat_id) {
        $db->insert($db_page_multicats, ['pcat_page_id' => $page_id, 'pcat_cat_id' => $cat_id]);
    }

    return true;
}

/**
 * Возвращает structure_id по structure_code для area = 'page'.
 * Утилитарная функция для хуков, которым нужен ID основной категории.
 *
 * @param string $code Код категории (page_cat).
 * @return int structure_id или 0, если не найдено.
 */
function multicat_code_to_id($code)
{
    $code = (string)$code;
    if ($code === '') {
        return 0;
    }
    return (int) Cot::$db->query(
        "SELECT structure_id FROM " . Cot::$db->structure
        . " WHERE structure_code = ? AND structure_area = 'page'",
        [$code]
    )->fetchColumn();
}



/**
 * Разобрать base64-строку back-URL и вернуть готовый для редиректа URL.
 * Возвращает null, если back не задан или не декодируется.
 *
 * @param string|null $backB64
 * @return string|null
 */
function multicat_resolve_back($backB64)
{
    if (empty($backB64)) {
        return null;
    }
    $decoded = base64_decode($backB64, true);
    if ($decoded === false) {
        return null;
    }
    return str_replace('&amp;', '&', $decoded);
}




/**
 * Рекурсивная генерация HTML-списка чекбоксов категорий.
 *
 * @param string $parent     Код родительской категории ('' для корня)
 * @param array  $selected   Массив выбранных structure_id
 * @param array  $code_to_id Соответствие code => structure_id
 * @param array  $blacklist  Чёрный список кодов категорий
 * @return string HTML-код вложенных <li>
 */
function multicat_render_checkbox_tree($parent, $selected, $code_to_id, $blacklist)
{
    global $structure;

    if (empty($parent)) {
        // Корневой уровень: только категории без точек в path
        $allcat = cot_structure_children('page', '');
        $children = [];
        foreach ($allcat as $code) {
            if (
                !isset($structure['page'][$code]['path']) ||
                mb_substr_count($structure['page'][$code]['path'], '.') != 0 ||
                in_array($code, $blacklist)
            ) {
                continue;
            }
            $children[] = $code;
        }
    } else {
        if (!isset($structure['page'][$parent]['subcats'])) {
            return '';
        }
        $children = array_filter($structure['page'][$parent]['subcats'], function ($code) use ($blacklist) {
            return !in_array($code, $blacklist);
        });
    }

    if (empty($children)) {
        return '';
    }

    $html = '';
    foreach ($children as $code) {
        if (!cot_auth('page', $code, 'W')) {
            continue;
        }
        if (!isset($code_to_id[$code])) {
            continue;
        }

        $cat_id = $code_to_id[$code];
        $title  = $structure['page'][$code]['title'] ?? $code;

        $checked = in_array($cat_id, $selected) ? ' checked="checked"' : '';
        $id_attr = 'rcat_' . $cat_id;

        if (empty($parent) && $html !== '') {
            $html .= '<hr>';
        }

        $html .= '<li class="list-group-item bg-transparent border-0 py-0">';
        $html .= '<div class="form-check" style="margin: 0;">';
        $html .= '<input class="form-check-input" type="checkbox" name="rcat[]" value="' . $cat_id . '"' . $checked . ' id="' . $id_attr . '">';
        $html .= '<label class="form-check-label" for="' . $id_attr . '">' . htmlspecialchars($title) . '</label>';
        $html .= '</div>';

        $sub = multicat_render_checkbox_tree($code, $selected, $code_to_id, $blacklist);
        if ($sub !== '') {
            $html .= '<ul>' . $sub . '</ul>';
        }
        $html .= '</li>';
    }
    return $html;
}

/**
 * Собирает готовый HTML для тега {PAGEFORM_CAT} — скроллируемый список
 * чекбоксов категорий с отмеченными $selected.
 *
 * @param array $selected Массив выбранных structure_id.
 * @return string HTML-код.
 */
function multicat_build_checkbox_html($selected = [])
{
    global $db, $db_structure;

    // Чёрный список категорий (если используется в модуле Page)
    $blacklist_cfg = Cot::$cfg['page']['blacktreecatspage'] ?? '';
    $blacklist = array_map('trim', explode(',', $blacklist_cfg));

    // Кэш соответствий code => structure_id
    $code_to_id = [];
    $res = $db->query("SELECT structure_code, structure_id FROM $db_structure WHERE structure_area = 'page'");
    foreach ($res->fetchAll() as $row) {
        $code_to_id[$row['structure_code']] = (int) $row['structure_id'];
    }

    $selected = is_array($selected) ? $selected : [];

    $html  = '<div style="max-height: 480px; overflow-y: auto; width: 100%;">';
    $html .= '<ul class="list-group list-group-flush">';
    $html .= multicat_render_checkbox_tree('', $selected, $code_to_id, $blacklist);
    $html .= '</ul>';
    $html .= '</div>';

    return $html;
}

/**
 * Возвращает категории страницы с данными, готовыми для вывода в шаблон:
 * id, code, title (с учётом перевода i18n), url.
 *
 * Использует:
 *   — таблицу cot_page_multicats для получения списка structure_id;
 *   — таблицу cot_structure для code/title по structure_id;
 *   — плагин i18n (если активен) для перевода названия категории
 *     через функцию cot_i18n_get_cat(), данные из cot_i18n_structure.
 *
 * @param int         $page_id ID страницы.
 * @param string|null $locale  Локаль для перевода. null = текущая из i18n.
 * @return array Список массивов:
 *               [
 *                 'id'    => int,    // structure_id
 *                 'code'  => string, // structure_code
 *                 'title' => string, // заголовок (с переводом)
 *                 'url'   => string, // URL категории
 *               ]
 */
function multicat_get_cats_with_data($page_id, $locale = null)
{
    global $db, $db_structure, $structure;

    $page_id = (int)$page_id;
    if ($page_id <= 0) {
        return [];
    }

    $cat_ids = multicat_get_cats($page_id);
    if (empty($cat_ids)) {
        return [];
    }

    // Базовая информация по всем категориям — одним запросом
    $sql = "SELECT structure_id, structure_code, structure_title FROM $db_structure
             WHERE structure_id IN (" . implode(',', array_map('intval', $cat_ids)) . ")
               AND structure_area = 'page'";
    $res = $db->query($sql);

    $db_cats = [];
    foreach ($res->fetchAll() as $row) {
        $db_cats[(int)$row['structure_id']] = $row;
    }

    // Активность плагина i18n
    $i18nActive = cot_plugin_active('i18n') && function_exists('cot_i18n_get_cat');

    // Локаль: если не передали — берём текущую из i18n или язык пользователя
    if ($locale === null) {
        global $i18n_locale;
        if (!empty($i18n_locale)) {
            $locale = (string)$i18n_locale;
        } elseif (!empty(Cot::$usr['lang'])) {
            $locale = (string)Cot::$usr['lang'];
        } else {
            $locale = (string)Cot::$cfg['defaultlang'];
        }
    }

    $result = [];
    foreach ($cat_ids as $cat_id) {
        $cat_id = (int)$cat_id;

        $code  = '';
        $title = '';

        if (isset($db_cats[$cat_id])) {
            $code  = (string)$db_cats[$cat_id]['structure_code'];
            $title = (string)$db_cats[$cat_id]['structure_title'];
        } else {
            // Fallback: ищем в $structure['page'] по structure_id
            foreach ($structure['page'] as $scode => $cdata) {
                if (isset($cdata['id']) && (int)$cdata['id'] === $cat_id) {
                    $code  = (string)$scode;
                    $title = (string)($cdata['title'] ?? $scode);
                    break;
                }
            }
            if ($code === '') {
                continue;
            }
        }

        // Применяем перевод категории (i18n), если активен
        if ($i18nActive && !empty($locale)) {
            $translated = cot_i18n_get_cat($code, $locale);
            if ($translated && !empty($translated['title'])) {
                $title = $translated['title'];
            }
        }

        $result[] = [
            'id'    => $cat_id,
            'code'  => $code,
            'title' => $title,
            'url'   => cot_url('page', ['c' => $code]),
        ];
    }

    return $result;
}


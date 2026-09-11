<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=page.edit.tags
[END_COT_EXT]
==================== */

/**
 * Multicat plugin for Page Module, CMF Cotonti v.1.0.0, PHP v.8.4+, MySQL v.8.0
 * Filename: plugins/multicat/multicat.page.edit.tags.php
 * Purpose: Хук для page.edit.tags. Генерирует иерархический список чекбоксов категорий,
 *          используя тот же принцип получения детей, что и cot_build_structure_page_tree.
 *          Передаёт в шаблон теги {PAGEFORM_CAT} и {PAGEFORM_CAT_HINT}.
 * Date=Sep 11, 2026
 * @package multicat
 * @version 1.7.9
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('multicat', 'plug');
require_once cot_langfile('multicat', 'plug');

$id = cot_import('id', 'G', 'INT');
$selected = ($id > 0) ? multicat_get_cats($id) : [];

global $db, $db_structure, $structure, $cfg;

// Чёрный список категорий (если используется в модуле Page)
$blacklist_cfg = Cot::$cfg['page']['blacktreecatspage'] ?? '';
$blacklist = array_map('trim', explode(',', $blacklist_cfg));

// Кэш соответствий code => structure_id
$code_to_id = [];
$res = $db->query("SELECT structure_code, structure_id FROM $db_structure WHERE structure_area = 'page'");
foreach ($res->fetchAll() as $row) {
    $code_to_id[$row['structure_code']] = (int) $row['structure_id'];
}

/**
 * Рекурсивная генерация HTML-списка чекбоксов.
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

    // Получаем дочерние категории по аналогии с cot_build_structure_page_tree
    if (empty($parent)) {
        // Корневой уровень: все категории, у которых в path нет точек
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
        // Подкатегории: берём из subcats родителя
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

        // Разделитель <hr> только между корневыми категориями
        if (empty($parent) && $html !== '') {
            $html .= '<hr>';
        }

        $html .= '<li class="list-group-item bg-transparent border-0 py-0">';
        $html .= '<div class="form-check" style="margin: 0;">';
        $html .= '<input class="form-check-input" type="checkbox" name="rcat[]" value="' . $cat_id . '"' . $checked . ' id="' . $id_attr . '">';
        $html .= '<label class="form-check-label" for="' . $id_attr . '">' . htmlspecialchars($title) . '</label>';
        $html .= '</div>';

        // Рекурсивно рендерим подкатегории
        $sub = multicat_render_checkbox_tree($code, $selected, $code_to_id, $blacklist);
        if ($sub !== '') {
            $html .= '<ul>' . $sub . '</ul>';
        }
        $html .= '</li>';
    }
    return $html;
}

// Генерация полного списка в скроллируемом контейнере высотой 480px
$list_html = '<div style="max-height: 480px; overflow-y: auto; width: 100%;">';
$list_html .= '<ul class="list-group list-group-flush">';
$list_html .= multicat_render_checkbox_tree('', $selected, $code_to_id, $blacklist);
$list_html .= '</ul>';
$list_html .= '</div>';

$t->assign([
    'PAGEFORM_CAT'      => $list_html,
    'PAGEFORM_CAT_HINT' => $L['multicat_select'],
]);
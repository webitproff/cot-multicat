<?php

/* ====================
[BEGIN_COT_EXT]
Hooks=page.edit.tags
[END_COT_EXT]
==================== */

/**
 * Multicat plugin for Page Module, CMF Cotonti Siena v.0.9.26, PHP v.8.4+, MySQL v.8.0
 * Filename: multicat.page.edit.tags.php
 * Purpose: Хук для page.edit.tags, modules\page\inc\page.edit.php, str 223. Генерирует чекбоксы категорий (structure_id) в форме редактирования страницы. Использует $structure['page'] для списка категорий, с проверкой прав доступа.
 * Date=2025-09-14
 * @package multicat
 * @version 1.1.0
 * @author webitproff
 * @copyright Copyright (c) webitproff 2025 | https://github.com/webitproff
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('multicat', 'plug');
require_once cot_langfile('multicat', 'plug');

$id = cot_import('id', 'G', 'INT');
$selected = ($id > 0) ? multicat_get_cats($id) : [];
$cats_values = [];
$cats_titles = [];
global $db, $db_structure, $structure;

foreach ($structure['page'] as $code => $cat_data) {
    if (cot_auth('page', $code, 'W')) {
        $sql = "SELECT structure_id FROM $db_structure WHERE structure_code = " . $db->quote($code) . " AND structure_area = 'page'";
        $cat_id = $db->query($sql)->fetchColumn();
        if ($cat_id) {
            $cats_values[] = (int)$cat_id;
            $cats_titles[] = $cat_data['title'] ?? $code;
        }
    }
}

$t->assign([
    'PAGEFORM_CAT' => cot_checklistbox(
        $selected,
        'rcat',
        $cats_values,
        $cats_titles,
        ['class' => 'checkbox'],
        '<br />',
        true,
        ''
    ),
    'PAGEFORM_CAT_HINT' => $L['multicat_select'],
]);
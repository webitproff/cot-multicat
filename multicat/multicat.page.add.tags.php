<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=page.add.tags
[END_COT_EXT]
==================== */

/**
 * Multicat plugin for Page Module, CMF Cotonti v.1.0.0, PHP v.8.5+, MySQL v.8.4
 * Filename: plugins/multicat/multicat.page.add.tags.php
 * Purpose: Хук page.add.tags. В page.add.php переменная данных страницы
 *          называется $rpage. Передаёт в шаблон добавления страницы
 *          теги {PAGEFORM_CAT} и {PAGEFORM_CAT_HINT}. Если основная
 *          категория уже известна (например, при клонировании), она
 *          отображается как отмеченная.
 * Date=Sep 12, 2026
 * @package multicat
 * @version 2.0.0
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('multicat', 'plug');

$selected = [];

// Если основная категория уже известна — отмечаем её в списке.
// В page.add.php данные формы лежат в $rpage.
if (!empty($rpage['page_cat'])) {
    $main_cat_id = multicat_code_to_id($rpage['page_cat']);
    if ($main_cat_id > 0) {
        $selected[] = $main_cat_id;
    }
}

$t->assign([
    'PAGEFORM_CAT'      => multicat_build_checkbox_html($selected),
    'PAGEFORM_CAT_HINT' => $L['multicat_select'],
]);
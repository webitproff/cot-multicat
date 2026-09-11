<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=page.add.add.done
[END_COT_EXT]
==================== */

/**
 * Multicat plugin for Page Module, CMF Cotonti v.1.0.0, PHP v.8.5+, MySQL v.8.4
 * Filename: plugins/multicat/multicat.page.add.add.done.php
 * Purpose: Хук page.add.add.done. Подключается ВНУТРИ cot_page_add(&$rpage, $auth)
 *          сразу после insert в cot_pages. В области видимости доступны
 *          $id (ID новой страницы), $rpage (данные страницы), $auth.
 *          Сохраняет основную категорию и дополнительные мультикатегории
 *          из $_POST['rcat'] в таблицу cot_page_multicats.
 * Date=Sep 12, 2026
 * @package multicat
 * @version 2.0.0
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('multicat', 'plug');

/* $id — ID только что созданной страницы (устанавливается в cot_page_add).
   $rpage — данные страницы (второй параметр функции, передан по ссылке). */
$page_id = (isset($id) && (int)$id > 0) ? (int)$id : 0;

if ($page_id > 0) {
    // Основная категория — из $rpage['page_cat']; если по какой-то причине
    // её там нет, читаем из БД (страховка).
    $main_code = '';
    if (isset($rpage['page_cat'])) {
        $main_code = (string)$rpage['page_cat'];
    }
    if ($main_code === '') {
        $main_code = (string) Cot::$db->query(
            "SELECT page_cat FROM " . Cot::$db->pages . " WHERE page_id = ?",
            [$page_id]
        )->fetchColumn();
    }

    $main_cat_id = multicat_code_to_id($main_code);
    $rcats       = (isset($_POST['rcat']) && is_array($_POST['rcat'])) ? $_POST['rcat'] : [];

    multicat_save_cats($page_id, $rcats, $main_cat_id);
}
<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=page.delete.first
[END_COT_EXT]
==================== */

/**
 * Multicat plugin for Page Module, CMF Cotonti v.1.0.0, PHP v.8.5+, MySQL v.8.0
 * Filename: plugins/multicat/multicat.page.delete.first.php
 * Purpose: Хук page.delete.first. Подключается ВНУТРИ
 *          PageControlService::delete($id), где переменная называется $id,
 *          а не $page_id. Удаляет связи страницы перед её удалением.
 * Date=Sep 12, 2026
 * @package multicat
 * @version 2.0.0
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('multicat', 'plug');

global $db, $db_page_multicats;

/* В PageControlService::delete() переменная называется $id.
   Оставляем фолбэк на $page_id на случай других вызовов. */
$article_id = 0;
if (isset($id) && (int)$id > 0) {
    $article_id = (int)$id;
} elseif (isset($page_id) && (int)$page_id > 0) {
    $article_id = (int)$page_id;
}

if ($article_id > 0) {
    $db->delete($db_page_multicats, "pcat_page_id = " . $article_id);
}
<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=page.delete.first
[END_COT_EXT]
==================== */

/**
 * Multicat plugin for Page Module, CMF Cotonti v.1.0.0, PHP v.8.4+, MySQL v.8.0
 * Filename: plugins/multicat/multicat.page.delete.first.php
 * Purpose: Хук для page.delete.first, modules\page\inc\PageControlService.php.
 *          Удаляет связи категорий перед удалением страницы.
 * Date=Sep 12, 2026
 * @package multicat
 * @version 1.7.9
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('multicat', 'plug');

global $db, $db_page_multicats, $page_id;

$db->delete($db_page_multicats, "pcat_page_id = " . (int)$page_id);
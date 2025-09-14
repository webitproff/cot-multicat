<?php

/* ====================
[BEGIN_COT_EXT]
Hooks=page.delete.first
[END_COT_EXT]
==================== */

/**
 * Multicat plugin for Page Module, CMF Cotonti Siena v.0.9.26, PHP v.8.4+, MySQL v.8.0
 * Filename: multicat.page.delete.first.php
 * Purpose: Хук для page.delete.first \modules\page\inc\PageControlService.php str 58. Удаляет связи категорий перед удалением страницы.
 * Date=2025-09-14
 * @package multicat
 * @version 1.1.0
 * @author webitproff
 * @copyright Copyright (c) webitproff 2025 | https://github.com/webitproff
 * @license BSD
 */
 
defined('COT_CODE') or die('Wrong URL');

global $db, $db_page_multicats, $page_id;

$db->delete($db_page_multicats, "pcat_page_id = " . (int)$page_id);
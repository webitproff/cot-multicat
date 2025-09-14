<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=global
[END_COT_EXT]
==================== */

/**
 * Multicat plugin for Page Module, CMF Cotonti Siena v.0.9.26, PHP v.8.4+, MySQL v.8.0
 * Filename: multicat.global.php
 * Purpose: Глобальный хук для плагина Multicat. Определяет имя таблицы и загружает языковой файл.
 * Date=2025-09-14
 * @package multicat
 * @version 1.1.0
 * @author webitproff
 * @copyright Copyright (c) webitproff 2025 | https://github.com/webitproff
 * @license BSD
 */
 
defined('COT_CODE') or die('Wrong URL');

require_once cot_langfile('multicat', 'plug');

global $db_page_multicats, $db_x, $cfg;

$db_page_multicats = $db_x . 'page_multicats';
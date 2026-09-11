<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=global
[END_COT_EXT]
==================== */

/**
 * Multicat plugin for Page Module, CMF Cotonti v.1.0.0, PHP v.8.4+, MySQL v.8.0
 * Filename: plugins/multicat/multicat.global.php
 * Purpose: Глобальный хук для плагина Multicat. Регистрирует таблицу
 *          cot_page_multicats, определяет её псевдоним и загружает языковой файл.
 * Date=Sep 12, 2026
 * @package multicat
 * @version 1.7.9
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('multicat', 'plug');

Cot::$db->registerTable('page_multicats');

global $db_page_multicats, $db_x, $cfg;

$db_page_multicats = $db_x . 'page_multicats';
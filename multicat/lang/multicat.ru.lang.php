<?php
/**
 * Multicat plugin for Page Module, CMF Cotonti Siena v.0.9.26, PHP v.8.4+, MySQL v.8.0
 * Filename: lang/multicat.ru.lang.php
 * Purpose: Russian language file for the Multicat plugin. Defines the strings for the UI
 * Date=2025-09-14
 * @package multicat
 * @version 1.1.0
 * @author webitproff
 * @copyright Copyright (c) webitproff 2025 | https://github.com/webitproff
 * @license BSD
 */
 

defined('COT_CODE') or die('Wrong URL');
/**
 * Plugin Conf
 */
$L['cfg_enabled'] = 'Включить множественные категории';

/**
 * Plugin Info
 */
$L['info_name'] = 'Multicat for Page Module';
$L['info_desc'] = 'Позволяет назначать страницы сразу в несколько категорий';
$L['info_notes'] = 'в шаблоны page.edit.tpl/page.add.tpl: Добавить {PAGEFORM_CAT} и {PAGEFORM_CAT_HINT} сразу после категорий.';


$L['multicat_select'] = 'Выберите категории (можно выбрать несколько)';
$L['multicat_cats'] = 'Категории';
$L['multicat_error_no_category'] = 'Ошибка: необходимо выбрать хотя бы одну категорию';

$L['multicat_help'] = 'в шаблоны page.edit.tpl/page.add.tpl: Добавить {PAGEFORM_CAT} и {PAGEFORM_CAT_HINT} сразу после категорий.';
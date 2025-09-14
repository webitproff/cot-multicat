<?php
/**
 * Multicat plugin for Page Module, CMF Cotonti Siena v.0.9.26, PHP v.8.4+, MySQL v.8.0
 * Filename: lang/multicat.en.lang.php
 * Purpose: English language file for the Multicat plugin. Defines the strings for the UI
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
$L['cfg_enabled'] = 'Enable multiple categories';

/**
 * Plugin Info
 */
$L['info_name'] = 'Multicat for Page Module';
$L['info_desc'] = 'Allows assigning pages to multiple categories at once';
$L['info_notes'] = 'In templates page.edit.tpl/page.add.tpl: Add {PAGEFORM_CAT} and {PAGEFORM_CAT_HINT} right after the category field.';

$L['multicat_select'] = 'Select categories (multiple selection allowed)';
$L['multicat_cats'] = 'Categories';
$L['multicat_error_no_category'] = 'Error: you must select at least one category';

$L['multicat_help'] = 'In templates page.edit.tpl/page.add.tpl: Add {PAGEFORM_CAT} and {PAGEFORM_CAT_HINT} right after the category field.';

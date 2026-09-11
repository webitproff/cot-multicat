<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=page.edit.update.done
[END_COT_EXT]
==================== */

/**
 * Multicat plugin for Page Module, CMF Cotonti v.1.0.0, PHP v.8.4+, MySQL v.8.0
 * Filename: plugins/multicat/multicat.page.edit.update.done.php
 * Purpose: Хук для page.edit.update.done, modules\page\inc\page.functions.php.
 *          Сохраняет категории (structure_id) после обновления страницы.
 * Date=Sep 11, 2026
 * @package multicat
 * @version 1.7.9
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('multicat', 'plug');

$page_id = (int)$id;
if ($page_id > 0) {
    $rcats = (isset($_POST['rcat']) && is_array($_POST['rcat'])) ? $_POST['rcat'] : [];
    multicat_save_cats($page_id, $rcats);
}
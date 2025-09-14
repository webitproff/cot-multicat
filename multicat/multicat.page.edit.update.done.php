<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=page.edit.update.done
[END_COT_EXT]
==================== */

/**
 * Multicat plugin for Page Module, CMF Cotonti Siena v.0.9.26, PHP v.8.4+, MySQL v.8.0
 * Filename: multicat.page.edit.update.done.php
 * Purpose: Хук для page.edit.update.done, modules\page\inc\page.functions.php, str 796. Сохраняет категории (structure_id) после обновления страницы.
 * Date=2025-09-14
 * @package multicat
 * @version 1.1.0
 * @author webitproff
 * @copyright Copyright (c) webitproff 2025 | https://github.com/webitproff
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('multicat', 'plug');

if (!empty($_POST['rcat']) && is_array($_POST['rcat'])) {
    $pageid = (int)$id;
    if ($pageid > 0) {
        multicat_save_cats($pageid, $_POST['rcat']);
    }
}

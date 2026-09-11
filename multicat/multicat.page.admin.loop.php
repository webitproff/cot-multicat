<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=page.admin.loop
[END_COT_EXT]
==================== */

/**
 * Multicat plugin for Page Module, CMF Cotonti v.1.0.0, PHP v.8.5+, MySQL v.8.4
 * Filename: plugins/multicat/multicat.page.admin.loop.php
 * Purpose: Хук для page.admin.loop, modules\page\page.admin.php.
 *          Отображает список категорий (structure_title) в админ-списке страниц.
 * Date=Sep 12, 2026
 * @package multicat
 * @version 2.0.0
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('multicat', 'plug');

/**
 * @var array $row Current page row
 * @var XTemplate $t Current template object
 */

if (!empty($row['page_id'])) {
    $multicats = multicat_get_cat_titles($row['page_id']);
    $t->assign([
        'ADMIN_PAGE_MULTICATS' => !empty($multicats) ? implode(', ', $multicats) : ''
    ]);
}
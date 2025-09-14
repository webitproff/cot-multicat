<?php

/* ====================
[BEGIN_COT_EXT]
Hooks=page.admin.loop
[END_COT_EXT]
==================== */

/**
 * Multicat plugin for Page Module, CMF Cotonti Siena v.0.9.26, PHP v.8.4+, MySQL v.8.0
 * Filename: multicat.page.admin.loop.php
 * Purpose: Хук для page.admin.loop modules\page\page.admin.php str 373. Отображает список категорий (structure_title) в админ-списке страниц.
 * Date=2025-09-14
 * @package multicat
 * @version 1.1.0
 * @author webitproff
 * @copyright Copyright (c) webitproff 2025 | https://github.com/webitproff
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

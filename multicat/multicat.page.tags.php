<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=page.tags
[END_COT_EXT]
==================== */

/**
 * Multicat plugin for Page Module, CMF Cotonti v.1.0.0, PHP v.8.5+, MySQL v.8.4
 *
 * Filename: multicat.page.tags.php
 *
 * Path:     plugins/multicat/multicat.page.tags.php
 *
 * ============================================================
 * ДОКУМЕНТАЦИЯ ПО ФАЙЛУ multicat.page.tags.php
 * ============================================================
 *
 * Назначение:
 *   Хук page.tags модуля Page. Передаёт в шаблон список всех категорий,
 *   в которых показывается текущая статья (страница), включая основную
 *   и дополнительные мультикатегории. Заголовки категорий берутся из
 *   cot_structure с учётом переводов плагина i18n (таблица
 *   cot_i18n_structure), если текущий язык отличается от основного.
 *
 * Вызывается:
 *   modules/page/page.php перед финальным парсингом шаблона, в блоке
 *   cot_getextplugins('page.tags').
 *
 * В области видимости доступны:
 *   $pag — массив данных страницы (page_id и др.);
 *   $id  — ID страницы;
 *   $al  — алиас страницы;
 *   $c   — код основной категории страницы;
 *   $t   — объект XTemplate (шаблон страницы).
 *
 * Что делает:
 *   1. Получает ID категорий страницы через multicat_get_cats_with_data().
 *   2. Если категорий нет — молча выходит.
 *   3. Для каждой категории присваивает теги PAGE_MULTICATS_ROW_* и
 *      парсит вложенный блок PAGE_MULTICATS_LIST.PAGE_MULTICATS_ROW.
 *   4. Парсит внешний блок MAIN.PAGE_MULTICATS_LIST.
 *
 * ВАЖНО (переменные цикла):
 *   Используется $mc, а НЕ $c. Переменная $c в глобальной области видимости
 *   Cotonti содержит код основной категории страницы. Перезапись $c массивом
 *   приводит к warning "Array to string conversion" в
 *   system/cotemplate.php при вычислении {PHP.c} в callback-аргументах
 *   шаблона.
 *
 * ============================================================
 * ПРИМЕР ПОДКЛЮЧЕНИЯ В ШАБЛОНЕ page.tpl
 * ============================================================
 *
 * Внутри блока <!-- BEGIN: MAIN --> один раз, там где должен выводиться
 * список мультикатегорий:
 *
 * <!-- BEGIN: PAGE_MULTICATS_LIST -->
 * <div class="card mb-4">
 *     <div class="card-header">
 *         <h3 class="h6 mb-0">{PHP.L.multicat_page_cats_links}</h3>
 *         <small>{PHP.L.multicat_page_cats_links_hint}</small>
 *     </div>
 *     <div class="card-body">
 *         <ul class="list-group list-group-flush">
 *             <!-- BEGIN: PAGE_MULTICATS_ROW -->
 *             <li class="list-group-item">
 *                 <a href="{PAGE_MULTICATS_ROW_URL}">{PAGE_MULTICATS_ROW_TITLE}</a>
 *             </li>
 *             <!-- END: PAGE_MULTICATS_ROW -->
 *         </ul>
 *     </div>
 * </div>
 * <!-- END: PAGE_MULTICATS_LIST -->
 *
 * ============================================================
 * ЗАВИСИМОСТИ
 * ============================================================
 *   — inc/multicat.functions.php: multicat_get_cats_with_data($page_id, $locale)
 *   — lang/multicat.<locale>.lang.php: $L['multicat_page_cats_links'],
 *                                      $L['multicat_page_cats_links_hint']
 *   — модуль page, хук page.tags (Cotonti 1.0.0+)
 *
 * Source and updates   https://github.com/webitproff/cot-multicat
 * ReadMeMore:          https://abuyfile.com/ru/market/cotonti/plugs/multicat-plugin-pages-cotonti-siena
 * Support:             https://abuyfile.com/ru/forums/cotonti/custom/plugs/topic167
 *
 * Date: Sep 12, 2026
 *
 * @package multicat
 * @version 2.0.0
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff
 * @license BSD
 */

				
					
defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('multicat', 'plug');
require_once cot_langfile('multicat', 'plug');

if (empty($pag['page_id'])) {
    return;
}

$pageCats = multicat_get_cats_with_data((int)$pag['page_id']);

if (empty($pageCats)) {
    return;
}

foreach ($pageCats as $mc) {
    $t->assign([
        'PAGE_MULTICATS_ROW_ID'    => (int)$mc['id'],
        'PAGE_MULTICATS_ROW_CODE'  => htmlspecialchars($mc['code']),
        'PAGE_MULTICATS_ROW_TITLE' => htmlspecialchars($mc['title']),
        'PAGE_MULTICATS_ROW_URL'   => $mc['url'],
    ]);
    $t->parse('MAIN.PAGE_MULTICATS_LIST.PAGE_MULTICATS_ROW');
}

$t->parse('MAIN.PAGE_MULTICATS_LIST');
<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=tools
[END_COT_EXT]
==================== */

/**
 * Admin panel for Multicat – Список связей, Добавление, Редактирование,
 * Очистка, Статистика, Массовые операции
 *
 * Filename: plugins/multicat/multicat.admin.php
 *
 * Multicat plugin for Page Module, CMF Cotonti v1.0.0+, PHP 8.4+, MySQL 8.0+
 *
 * Source and updates:  https://github.com/webitproff
 * Date=Sep 12, 2026
 *
 * @package multicat
 * @version 2.0.0
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

// require_once cot_langfile('multicat', 'plug'); уже подключен в plugins/multicat/inc/multicat.functions.php
require_once cot_incfile('multicat', 'plug'); // подключаем наш multicat.functions.php
require_once cot_incfile('page', 'module');

Cot::$db->registerTable('page_multicats');
Cot::$db->registerTable('pages');

cot_block(Cot::$usr['isadmin']);

/* ============================================================
 * ИНИЦИАЛИЗАЦИЯ
 * ============================================================ */

$tab = cot_import('tab', 'G', 'ALP') ?: 'list';
$a   = cot_import('a',   'G', 'ALP');

$t = new XTemplate(cot_tplfile('multicat.admin', 'plug', true));

$t->assign([
    'TAB_LIST_ACTIVE'  => $tab === 'list'  ? 'active' : '',
    'TAB_ADD_ACTIVE'   => $tab === 'add'   ? 'active' : '',
    'TAB_CLEAN_ACTIVE' => $tab === 'clean' ? 'active' : '',
    'TAB_STATS_ACTIVE' => $tab === 'stats' ? 'active' : '',
    'TAB_MASS_ACTIVE'  => $tab === 'mass'  ? 'active' : '',
    'URL_LIST'  => cot_url('admin', ['m' => 'other', 'p' => 'multicat', 'tab' => 'list']),
    'URL_ADD'   => cot_url('admin', ['m' => 'other', 'p' => 'multicat', 'tab' => 'add']),
    'URL_CLEAN' => cot_url('admin', ['m' => 'other', 'p' => 'multicat', 'tab' => 'clean']),
    'URL_STATS' => cot_url('admin', ['m' => 'other', 'p' => 'multicat', 'tab' => 'stats']),
    'URL_MASS'  => cot_url('admin', ['m' => 'other', 'p' => 'multicat', 'tab' => 'mass']),
]);

$listUrl = cot_url('admin', ['m' => 'other', 'p' => 'multicat', 'tab' => 'list']);


/* ============================================================
 * ОБРАБОТКА ДЕЙСТВИЙ (POST)
 * ============================================================ */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* -------- Добавление связи -------- */
    if ($a === 'add') {
        $page_id  = cot_import('page_id', 'P', 'INT');
        $cat_code = cot_import('cat_id',  'P', 'TXT');
        $cat_id   = multicat_code_to_id($cat_code);

        if ($page_id > 0 && $cat_id > 0) {
            $exists = (int) Cot::$db->query(
                "SELECT COUNT(*) FROM " . Cot::$db->pages . " WHERE page_id = ?",
                [$page_id]
            )->fetchColumn();

            if (!$exists) {
                cot_error(Cot::$L['multicat_item_not_found']);
            } else {
                $dup = (int) Cot::$db->query(
                    "SELECT COUNT(*) FROM " . Cot::$db->page_multicats
                    . " WHERE pcat_page_id = ? AND pcat_cat_id = ?",
                    [$page_id, $cat_id]
                )->fetchColumn();

                if ($dup) {
                    cot_error(Cot::$L['multicat_already_exists']);
                } else {
                    Cot::$db->insert(
                        Cot::$db->page_multicats,
                        ['pcat_page_id' => $page_id, 'pcat_cat_id' => $cat_id]
                    );
                    cot_message(Cot::$L['multicat_added']);
                    cot_redirect($listUrl);
                }
            }
        } else {
            cot_error(Cot::$L['multicat_fill_required']);
        }
    }

    /* -------- Изменение связи (вкладка edit) -------- */
    if ($a === 'update') {
        $old_pid  = cot_import('old_pid', 'P', 'INT');
        $old_cid  = cot_import('old_cid', 'P', 'INT');
        $cat_code = cot_import('cat_id',  'P', 'TXT');
        $new_cat  = multicat_code_to_id($cat_code);

        $backUrl = cot_url('admin', [
            'm' => 'other', 'p' => 'multicat', 'tab' => 'edit',
            'pid' => $old_pid, 'cid' => $old_cid,
        ]);

        if ($old_pid > 0 && $old_cid > 0 && $new_cat > 0) {
            if ($new_cat === $old_cid) {
                cot_redirect($listUrl);
            }

            $dup = (int) Cot::$db->query(
                "SELECT COUNT(*) FROM " . Cot::$db->page_multicats
                . " WHERE pcat_page_id = ? AND pcat_cat_id = ?",
                [$old_pid, $new_cat]
            )->fetchColumn();

            if ($dup) {
                cot_error(Cot::$L['multicat_already_exists']);
                cot_redirect($backUrl);
            }

            Cot::$db->update(
                Cot::$db->page_multicats,
                ['pcat_cat_id' => $new_cat],
                "pcat_page_id = $old_pid AND pcat_cat_id = $old_cid"
            );
            cot_message(Cot::$L['multicat_updated']);
            cot_redirect($listUrl);
        }

        cot_error(Cot::$L['multicat_fill_required']);
        cot_redirect($backUrl);
    }

    /* -------- Массовая очистка связей у выбранных страниц -------- */
    if ($a === 'massunlink') {
        $ids         = cot_import('ids',  'P', 'ARR');
        $backB64     = cot_import('back', 'P', 'HTM');
        $items_count = 0;
        $links_count = 0;

        if (!empty($ids)) {
            foreach ((array)$ids as $pid) {
                $pid = (int)$pid;
                if ($pid <= 0) {
                    continue;
                }
                $del = (int) Cot::$db->delete(
                    Cot::$db->page_multicats,
                    "pcat_page_id = $pid"
                );
                if ($del > 0) {
                    $links_count += $del;
                    $items_count++;
                }
            }
        }

        if ($links_count > 0) {
            cot_message(sprintf(Cot::$L['multicat_massunlinked'], $links_count, $items_count));
        }

        // Возвращаемся на исходную страницу списка (пагинация + фильтры)
        $resolvedBack = multicat_resolve_back($backB64);
        cot_redirect($resolvedBack !== null ? $resolvedBack : $listUrl);
    }

    /* -------- Очистка мусора -------- */
    if ($a === 'clean') {
        $deleted  = 0;
        $deleted += (int) Cot::$db->delete(Cot::$db->page_multicats, "pcat_cat_id = 0");
        $deleted += (int) Cot::$db->delete(
            Cot::$db->page_multicats,
            "pcat_page_id NOT IN (SELECT page_id FROM " . Cot::$db->pages . ")"
        );
        $deleted += (int) Cot::$db->delete(
            Cot::$db->page_multicats,
            "pcat_cat_id NOT IN (SELECT structure_id FROM " . Cot::$db->structure . " WHERE structure_area = 'page')"
        );

        cot_message(sprintf(Cot::$L['multicat_clean_done'], $deleted));
        cot_redirect(cot_url('admin', ['m' => 'other', 'p' => 'multicat', 'tab' => 'clean']));
    }

    /* -------- Массовая привязка -------- */
    if ($a === 'massbind') {
        $cat_code = cot_import('cat_id',   'P', 'TXT');
        $cat_id   = multicat_code_to_id($cat_code);
        $ids_raw  = cot_import('page_ids', 'P', 'TXT');
        $page_ids = array_values(array_filter(array_map('intval', explode(',', (string)$ids_raw))));

        if ($cat_id > 0 && !empty($page_ids)) {
            $added = 0;
            foreach ($page_ids as $pid) {
                if ($pid <= 0) {
                    continue;
                }
                $dup = (int) Cot::$db->query(
                    "SELECT COUNT(*) FROM " . Cot::$db->page_multicats
                    . " WHERE pcat_page_id = ? AND pcat_cat_id = ?",
                    [$pid, $cat_id]
                )->fetchColumn();
                if (!$dup) {
                    Cot::$db->insert(
                        Cot::$db->page_multicats,
                        ['pcat_page_id' => $pid, 'pcat_cat_id' => $cat_id]
                    );
                    $added++;
                }
            }
            cot_message(sprintf(Cot::$L['multicat_massbound'], $added));
        } else {
            cot_error(Cot::$L['multicat_fill_required']);
        }
        cot_redirect(cot_url('admin', ['m' => 'other', 'p' => 'multicat', 'tab' => 'mass']));
    }

    /* -------- Массовая отвязка -------- */
    if ($a === 'massunbind') {
        $cat_code = cot_import('cat_id',   'P', 'TXT');
        $cat_id   = multicat_code_to_id($cat_code);
        $ids_raw  = cot_import('page_ids', 'P', 'TXT');
        $page_ids = array_values(array_filter(array_map('intval', explode(',', (string)$ids_raw))));

        if ($cat_id > 0 && !empty($page_ids)) {
            $pid_list = implode(',', $page_ids);
            Cot::$db->delete(
                Cot::$db->page_multicats,
                "pcat_page_id IN ($pid_list) AND pcat_cat_id = $cat_id"
            );
            cot_message(sprintf(Cot::$L['multicat_massunbound'], count($page_ids)));
        } else {
            cot_error(Cot::$L['multicat_fill_required']);
        }
        cot_redirect(cot_url('admin', ['m' => 'other', 'p' => 'multicat', 'tab' => 'mass']));
    }
}

/* ============================================================
 * ОБРАБОТКА ДЕЙСТВИЙ (GET)
 * ============================================================ */

/* Удаление ВСЕХ связей одной страницы (кнопка в строке) */
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $a === 'unlinkall') {
    cot_check_xg();

    $pid     = cot_import('pid',  'G', 'INT');
    $backB64 = cot_import('back', 'G', 'HTM');

    if ($pid > 0) {
        $del = (int) Cot::$db->delete(
            Cot::$db->page_multicats,
            "pcat_page_id = $pid"
        );
        if ($del > 0) {
            cot_message(sprintf(Cot::$L['multicat_unlinked'], $del));
        }
    }

    // Возвращаемся на исходную страницу списка (пагинация + фильтры)
    $resolvedBack = multicat_resolve_back($backB64);
    cot_redirect($resolvedBack !== null ? $resolvedBack : $listUrl);
}

/* ============================================================
 * ВКЛАДКА: СПИСОК СТРАНИЦ С МУЛЬТИКАТЕГОРИЯМИ
 * ============================================================ */

if ($tab === 'list') {

    $perPage = 50;
    list($pg, $d, $durl) = cot_import_pagenav('d', $perPage);

    /* ---- Фильтр по категории ---- */
    $filter_cat_code = cot_import('c', 'G', 'TXT');
    $filter_cat_code = ($filter_cat_code !== null) ? trim($filter_cat_code) : '';
    $filter_cat_id   = multicat_code_to_id($filter_cat_code);

    /* ---- Поиск по названию страницы ---- */
    $search_title = cot_import('ftitle', 'G', 'TXT');
    $search_title = ($search_title !== null) ? trim($search_title) : '';

    /* ---- Фильтр по наличию связей: all | with | without ---- */
    $linkFilter = cot_import('lf', 'G', 'ALP');
    if (!in_array($linkFilter, ['all', 'with', 'without'], true)) {
        $linkFilter = 'all';
    }

    /* ---- URL-параметры (для пагинации и back-URL) ---- */
    $urlParams = [
        'm'   => 'other',
        'p'   => 'multicat',
        'tab' => 'list',
    ];
    if ($filter_cat_code !== '') {
        $urlParams['c'] = $filter_cat_code;
    }
    if ($search_title !== '') {
        $urlParams['ftitle'] = $search_title;
    }
    if ($linkFilter !== 'all') {
        $urlParams['lf'] = $linkFilter;
    }

    /* ---- Полный URL текущей страницы (с пагинацией) → base64 для back ---- */
    $currentUrlParams = $urlParams;
    if (!empty($durl)) {
        $currentUrlParams['d'] = $durl;
    }
    // cot_url с htmlspecialchars = false, чтобы не получить &amp; в base64
    $currentListUrl = cot_url('admin', $currentUrlParams, '', false);
    $backB64        = base64_encode($currentListUrl);

    /* ---- Токен x для cot_url (разбираем в массив, чтобы не вкладывать строку) ---- */
    $xArr = [];
    parse_str(cot_xg(), $xArr); // $xArr = ['x' => 'token']

    /* ---- WHERE по страницам ---- */
    $where  = ["p.page_title IS NOT NULL AND p.page_title != ''"];
    $params = [];

    if ($filter_cat_code !== '') {
        if ($filter_cat_id > 0) {
            $where[]  = "EXISTS (SELECT 1 FROM " . Cot::$db->page_multicats
                      . " AS mc2 WHERE mc2.pcat_page_id = p.page_id AND mc2.pcat_cat_id = ?)";
            $params[] = $filter_cat_id;
        } else {
            $where[] = "1 = 0";
        }
    }

    if ($search_title !== '') {
        $where[]  = "p.page_title LIKE ?";
        $params[] = '%' . $search_title . '%';
    }

    if ($linkFilter === 'with') {
        $where[] = "EXISTS (SELECT 1 FROM " . Cot::$db->page_multicats
                 . " AS mc3 WHERE mc3.pcat_page_id = p.page_id)";
    } elseif ($linkFilter === 'without') {
        $where[] = "NOT EXISTS (SELECT 1 FROM " . Cot::$db->page_multicats
                 . " AS mc3 WHERE mc3.pcat_page_id = p.page_id)";
    }

    $where_sql = 'WHERE ' . implode(' AND ', $where);

    /* ---- Общее количество страниц ---- */
    $total = (int) Cot::$db->query(
        "SELECT COUNT(*) FROM " . Cot::$db->pages . " AS p $where_sql",
        $params
    )->fetchColumn();

    /* ---- Страница статей ---- */
    $products = Cot::$db->query(
        "SELECT p.page_id, p.page_title, p.page_alias, p.page_cat
         FROM " . Cot::$db->pages . " AS p
         $where_sql
         ORDER BY p.page_id DESC
         LIMIT $d, $perPage",
        $params
    )->fetchAll();

    /* ---- Связи для страниц текущей страницы пагинации ---- */
    $linksByPage = [];
    $pageIds = [];
    foreach ($products as $p) {
        $pageIds[] = (int)$p['page_id'];
    }
    if (!empty($pageIds)) {
        $id_list = implode(',', $pageIds);
        $linkRows = Cot::$db->query(
            "SELECT mc.pcat_page_id, mc.pcat_cat_id,
                    s.structure_title, s.structure_path
             FROM " . Cot::$db->page_multicats . " AS mc
             LEFT JOIN " . Cot::$db->structure . " AS s
                 ON s.structure_id = mc.pcat_cat_id AND s.structure_area = 'page'
             WHERE mc.pcat_page_id IN ($id_list)
             ORDER BY s.structure_path ASC, mc.pcat_cat_id ASC"
        )->fetchAll();
        foreach ($linkRows as $lr) {
            $linksByPage[(int)$lr['pcat_page_id']][] = $lr;
        }
    }

    /* ---- Названия основных категорий (по page_cat) ---- */
    $mainCatTitles = [];
    foreach ($products as $p) {
        $code = (string)$p['page_cat'];
        if ($code === '' || isset($mainCatTitles[$code])) {
            continue;
        }
        $mainCatTitles[$code] = isset(Cot::$structure['page'][$code]['title'])
            ? Cot::$structure['page'][$code]['title']
            : $code;
    }

    /* ---- Селект категории: fallback на cot_selectbox_structure ---- */
    $listFilterCatSelect = cot_selectbox_structure('page', $filter_cat_code, 'c', '', true, true, true);

    $t->assign([
        'LIST_FORM_ACTION'       => cot_url('admin'),
        'LIST_FILTER_CAT_SELECT' => $listFilterCatSelect,
        'LIST_FILTER_TITLE'      => cot_inputbox(
            'text',
            'ftitle',
            htmlspecialchars($search_title),
            'class="form-control" placeholder="' . Cot::$L['multicat_filter_title_placeholder'] . '"'
        ),
        'LIST_FILTER_LINK_ALL_CHECKED'     => ($linkFilter === 'all')     ? 'checked="checked"' : '',
        'LIST_FILTER_LINK_WITH_CHECKED'    => ($linkFilter === 'with')    ? 'checked="checked"' : '',
        'LIST_FILTER_LINK_WITHOUT_CHECKED' => ($linkFilter === 'without') ? 'checked="checked"' : '',
        'LIST_RESET_URL'         => $listUrl,
        'LIST_MASSDELETE_URL'    => cot_url('admin', ['m' => 'other', 'p' => 'multicat', 'tab' => 'list', 'a' => 'massunlink']),
        'LIST_BACK_B64'          => $backB64,
        'LIST_TOTAL'             => $total,
    ]);

    if (!empty($products)) {
        foreach ($products as $p) {
            $page_id = (int)$p['page_id'];

            $itemUrl = cot_url('page', !empty($p['page_alias'])
                ? ['c' => $p['page_cat'], 'al' => $p['page_alias']]
                : ['c' => $p['page_cat'], 'id' => $page_id]
            );

            $main_cat_title = $mainCatTitles[$p['page_cat']] ?? $p['page_cat'];

            $links = $linksByPage[$page_id] ?? [];
            if (!empty($links)) {
                $linksHtml = '<ul class="list-unstyled mb-0">';
                foreach ($links as $lk) {
                    $linksHtml .= '<li>'
                        . '<span class="text-primary">' . (int)$lk['pcat_cat_id'] . '</span>'
                        . ' - [<span class="text-danger">' . htmlspecialchars((string)($lk['structure_path'] ?? '')) . '</span>] '
                        . htmlspecialchars((string)($lk['structure_title'] ?? Cot::$L['multicat_not_exists']))
                        . '</li>';
                }
                $linksHtml .= '</ul>';
            } else {
                $linksHtml = '<span class="text-muted">' . Cot::$L['multicat_no_links'] . '</span>';
            }

            // URL для очистки всех связей одной страницы: собираем через cot_url()
            // с массивом, чтобы back и x корректно ушли в query string
            $rowDeleteUrl = cot_url('admin', array_merge([
                'm'    => 'other',
                'p'    => 'multicat',
                'tab'  => 'list',
                'a'    => 'unlinkall',
                'pid'  => $page_id,
                'back' => $backB64,
            ], $xArr));

            $t->assign([
                'ROW_PAGE_ID'       => $page_id,
                'ROW_ITEM_URL'      => $itemUrl,
                'ROW_TITLE'         => htmlspecialchars((string)$p['page_title']),
                'ROW_MAIN_CAT'      => htmlspecialchars((string)$main_cat_title),
                'ROW_MAIN_CAT_CODE' => htmlspecialchars((string)$p['page_cat']),
                'ROW_LINKS'         => $linksHtml,
                'ROW_LINKS_COUNT'   => count($links),
                'ROW_CHECKBOX'      => $page_id,
                'ROW_EDIT_URL'      => cot_url('page', ['m' => 'edit', 'id' => $page_id]),
                'ROW_DELETE_URL'    => $rowDeleteUrl,
            ]);
            $t->parse('MAIN.LIST_ROW');
        }
    } else {
        $t->parse('MAIN.LIST_EMPTY');
    }

    $pagenav = cot_pagenav('admin', $urlParams, $d, $total, $perPage, 'd');
    $t->assign(cot_generatePaginationTags($pagenav));

    $t->parse('MAIN.LIST');
}

/* ============================================================
 * ВКЛАДКА: РЕДАКТИРОВАНИЕ СВЯЗИ
 * ============================================================ */

if ($tab === 'edit') {
    $pid = cot_import('pid', 'G', 'INT');
    $cid = cot_import('cid', 'G', 'INT');

    $row = Cot::$db->query(
        "SELECT mc.*, p.page_title
         FROM " . Cot::$db->page_multicats . " AS mc
         LEFT JOIN " . Cot::$db->pages . " AS p ON p.page_id = mc.pcat_page_id
         WHERE mc.pcat_page_id = ? AND mc.pcat_cat_id = ?",
        [$pid, $cid]
    )->fetch();

    if ($row) {
        $cat_code = (string) Cot::$db->query(
            "SELECT structure_code FROM " . Cot::$db->structure
            . " WHERE structure_id = ? AND structure_area = 'page'",
            [$row['pcat_cat_id']]
        )->fetchColumn();

        $t->assign([
            'EDIT_FORM_URL'   => cot_url('admin', ['m' => 'other', 'p' => 'multicat', 'tab' => 'edit', 'a' => 'update']),
            'EDIT_BACK_URL'   => $listUrl,
            'EDIT_PAGE_ID'    => $row['pcat_page_id'],
            'EDIT_PAGE_TITLE' => htmlspecialchars((string)($row['page_title'] ?? Cot::$L['multicat_not_exists'])),
            'EDIT_OLD_CID'    => $row['pcat_cat_id'],
            'EDIT_CAT_SELECT' => cot_selectbox_structure('page', $cat_code, 'cat_id', ''),
        ]);
        $t->parse('MAIN.EDIT');
    } else {
        cot_error(Cot::$L['multicat_not_exists']);
        cot_redirect($listUrl);
    }
}

/* ============================================================
 * ВКЛАДКА: ДОБАВЛЕНИЕ
 * ============================================================ */

if ($tab === 'add') {
    $t->assign([
        'ADD_FORM_URL'   => cot_url('admin', ['m' => 'other', 'p' => 'multicat', 'tab' => 'add', 'a' => 'add']),
        'ADD_PAGE_ID'    => cot_inputbox('number', 'page_id', '', 'class="form-control" min="1" required'),
        'ADD_CAT_SELECT' => cot_selectbox_structure('page', '', 'cat_id', Cot::$L['multicat_select_none']),
    ]);
    $t->parse('MAIN.ADD');
}

/* ============================================================
 * ВКЛАДКА: ОЧИСТКА
 * ============================================================ */

if ($tab === 'clean') {
    $trash_zero   = (int) Cot::$db->query(
        "SELECT COUNT(*) FROM " . Cot::$db->page_multicats . " WHERE pcat_cat_id = 0"
    )->fetchColumn();
    $orphan_items = (int) Cot::$db->query(
        "SELECT COUNT(*) FROM " . Cot::$db->page_multicats
        . " WHERE pcat_page_id NOT IN (SELECT page_id FROM " . Cot::$db->pages . ")"
    )->fetchColumn();
    $orphan_cats  = (int) Cot::$db->query(
        "SELECT COUNT(*) FROM " . Cot::$db->page_multicats
        . " WHERE pcat_cat_id NOT IN (SELECT structure_id FROM " . Cot::$db->structure . " WHERE structure_area = 'page')"
    )->fetchColumn();

    $total_trash = $trash_zero + $orphan_items + $orphan_cats;

    $t->assign([
        'CLEAN_ZERO'         => $trash_zero,
        'CLEAN_ORPHAN_ITEMS' => $orphan_items,
        'CLEAN_ORPHAN_CATS'  => $orphan_cats,
        'CLEAN_TOTAL'        => $total_trash,
        'CLEAN_FORM_URL'     => cot_url('admin', ['m' => 'other', 'p' => 'multicat', 'tab' => 'clean', 'a' => 'clean']),
        'CLEAN_ALL_CLEAN'    => $total_trash === 0 ? '1' : '',
    ]);
    $t->parse('MAIN.CLEAN');
}

/* ============================================================
 * ВКЛАДКА: СТАТИСТИКА
 * ============================================================ */

if ($tab === 'stats') {
    $total_links = (int) Cot::$db->query("SELECT COUNT(*) FROM " . Cot::$db->page_multicats)->fetchColumn();
    $total_items = (int) Cot::$db->query("SELECT COUNT(DISTINCT pcat_page_id) FROM " . Cot::$db->page_multicats)->fetchColumn();
    $total_cats  = (int) Cot::$db->query("SELECT COUNT(DISTINCT pcat_cat_id) FROM " . Cot::$db->page_multicats)->fetchColumn();
    $avg         = $total_items > 0 ? round($total_links / $total_items, 2) : 0;

    $top_cats = Cot::$db->query(
        "SELECT s.structure_title, s.structure_code, COUNT(*) AS cnt
         FROM " . Cot::$db->page_multicats . " AS mc
         LEFT JOIN " . Cot::$db->structure . " AS s
             ON mc.pcat_cat_id = s.structure_id AND s.structure_area = 'page'
         GROUP BY mc.pcat_cat_id
         ORDER BY cnt DESC
         LIMIT 10"
    )->fetchAll();

    $t->assign([
        'STATS_TOTAL_LINKS'  => $total_links,
        'STATS_TOTAL_ITEMS'  => $total_items,
        'STATS_TOTAL_CATS'   => $total_cats,
        'STATS_AVG_PER_ITEM' => $avg,
    ]);

    if (!empty($top_cats)) {
        $rank = 1;
        foreach ($top_cats as $cat) {
            $t->assign([
                'TOP_RANK'      => $rank++,
                'TOP_CAT_TITLE' => htmlspecialchars((string)($cat['structure_title'] ?? Cot::$L['multicat_not_exists'])),
                'TOP_CAT_CODE'  => htmlspecialchars((string)($cat['structure_code'] ?? '')),
                'TOP_CAT_COUNT' => $cat['cnt'],
            ]);
            $t->parse('MAIN.STATS_ROW');
        }
    } else {
        $t->parse('MAIN.STATS_EMPTY');
    }
    $t->parse('MAIN.STATS');
}

/* ============================================================
 * ВКЛАДКА: МАССОВЫЕ ОПЕРАЦИИ
 * ============================================================ */

if ($tab === 'mass') {
    $t->assign([
        'MASS_BIND_FORM_URL'     => cot_url('admin', ['m' => 'other', 'p' => 'multicat', 'tab' => 'mass', 'a' => 'massbind']),
        'MASS_UNBIND_FORM_URL'   => cot_url('admin', ['m' => 'other', 'p' => 'multicat', 'tab' => 'mass', 'a' => 'massunbind']),
        'MASS_BIND_CAT_SELECT'   => cot_selectbox_structure('page', '', 'cat_id', Cot::$L['multicat_select_none']),
        'MASS_UNBIND_CAT_SELECT' => cot_selectbox_structure('page', '', 'cat_id', Cot::$L['multicat_select_none']),
    ]);
    $t->parse('MAIN.MASS');
}

/* ============================================================
 * ВЫВОД
 * ============================================================ */

cot_display_messages($t);
$t->parse('MAIN');
$pluginBody = $t->text('MAIN');
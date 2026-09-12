<?php
/**
 * Multicat plugin for Page Module, CMF Cotonti v.1.0.0, PHP v.8.5+, MySQL v.8.4
 * Filename: plugins/multicat/lang/multicat.ru.lang.php
 * Purpose: Russian language file for the Multicat plugin. Defines the strings for the UI
 * Date=Sep 12, 2026
 * @package multicat
 * @version 2.0.0
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff
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
$L['info_name']  = 'Multicat for Page Module';
$L['info_desc']  = 'Для модуля Page. Позволяет назначать страницы сразу в несколько категорий';
$L['info_notes'] = 'в шаблоны page.edit.tpl/page.add.tpl: Добавить {PAGEFORM_CAT} и {PAGEFORM_CAT_HINT} сразу после категорий.';

// Общие строки фронтенда
$L['multicat_select']            = 'Выберите категории (можно выбрать несколько)';
$L['multicat_cats']              = 'Мультикатегории';
$L['multicat_cats_edit']         = 'Показывать в категориях';
$L['multicat_error_no_category'] = 'Ошибка: необходимо выбрать хотя бы одну категорию';
$L['multicat_help']              = 'в шаблоны page.edit.tpl/page.add.tpl: Добавить {PAGEFORM_CAT} и {PAGEFORM_CAT_HINT} сразу после категорий.';

// Заголовок страницы админки
$L['multicat_admin_title'] = 'Управление мультикатегориями';

// Вкладки
$L['multicat_tab_list']  = 'Список связей';
$L['multicat_tab_add']   = 'Добавить связь';
$L['multicat_tab_clean'] = 'Очистка мусора';
$L['multicat_tab_stats'] = 'Статистика';
$L['multicat_tab_mass']  = 'Массовые операции';
$L['multicat_warning_tab_under_develop']  = 'Эта вкладка еще на стадии разработки. Функционал ограничен и может работать не корректно.';

// Заголовки разделов
$L['multicat_list_title']  = 'Список связей';
$L['multicat_add_title']   = 'Добавление новой связи';
$L['multicat_edit_title']  = 'Редактирование связи';
$L['multicat_clean_title'] = 'Очистка мусора';
$L['multicat_stats_title'] = 'Статистика';
$L['multicat_mass_title']  = 'Массовые операции';

// Сообщения действий
$L['multicat_deleted']         = 'Связь удалена.';
$L['multicat_massdeleted']     = 'Удалено связей: %d';
$L['multicat_clean_done']      = 'Удалено %d мусорных записей.';
$L['multicat_added']           = 'Связь добавлена.';
$L['multicat_updated']         = 'Связь обновлена.';
$L['multicat_item_not_found']  = 'Страница с таким ID не найдена.';
$L['multicat_fill_required']   = 'Заполните обязательные поля.';
$L['multicat_already_exists']  = 'Такая связь уже существует.';
$L['multicat_massbound']       = 'Привязано страниц: %d';
$L['multicat_massunbound']     = 'Отвязано страниц: %d';
$L['multicat_not_exists']      = '[не существует]';
$L['multicat_no_records']      = 'Записей не найдено.';

// Список / фильтр
$L['multicat_filter_title_placeholder'] = 'Название страницы';
$L['multicat_filter_btn']               = 'Фильтр';
$L['multicat_reset']                    = 'Сбросить';
$L['multicat_col_id']                   = 'ID страницы';
$L['multicat_col_title']                = 'Название страницы';
$L['multicat_col_cat_id']               = 'ID категории';
$L['multicat_col_category']             = 'Категория';
$L['multicat_col_code']                 = 'Код';
$L['multicat_col_actions']              = 'Действия';
$L['multicat_btn_edit_short']           = 'Изм.';
$L['multicat_btn_delete_short']         = 'Уд.';
$L['multicat_confirm_delete']           = 'Точно удалить?';
$L['multicat_confirm_massdelete']       = 'Удалить выбранные связи?';
$L['multicat_btn_delete_selected']      = 'Удалить выбранные';

// Добавление
$L['multicat_add_label_page']  = 'Страница (ID)';
$L['multicat_add_label_cat']   = 'Категория';
$L['multicat_add_page_hint']   = 'Укажите числовой ID страницы из таблицы pages.';
$L['multicat_btn_add']         = 'Добавить связь';
$L['multicat_select_none']     = '-- Выберите категорию --';

// Редактирование
$L['multicat_edit_label_page']    = 'Страница';
$L['multicat_edit_label_new_cat'] = 'Новая категория';
$L['multicat_btn_save']           = 'Сохранить';
$L['multicat_back_to_list']       = 'Назад к списку';

// Очистка
$L['multicat_clean_header']         = 'Мусорные записи';
$L['multicat_clean_desc']           = 'Проверьте количество битых записей и при необходимости запустите очистку.';
$L['multicat_clean_zero_cat']       = 'С нулевым cat_id';
$L['multicat_clean_orphan_items']   = 'С несуществующей страницей';
$L['multicat_clean_orphan_cats']    = 'С несуществующей категорией';
$L['multicat_clean_total']          = 'Всего к удалению';
$L['multicat_clean_all_clean']      = 'Мусорных записей не обнаружено.';
$L['multicat_confirm_clean']        = 'Удалить все мусорные записи?';
$L['multicat_btn_clean_all']        = 'Очистить всё';

// Статистика
$L['multicat_stats_header']       = 'Общая статистика';
$L['multicat_stats_total_links']  = 'Всего связей';
$L['multicat_stats_unique_items'] = 'Уникальных страниц';
$L['multicat_stats_total_cats']   = 'Уникальных категорий';
$L['multicat_stats_avg']          = 'Среднее на страницу';
$L['multicat_stats_top_cats']     = 'Топ-10 категорий';
$L['multicat_stats_col_category'] = 'Категория';
$L['multicat_stats_col_count']    = 'Связей';

// Массовые операции
$L['multicat_mass_header']         = 'Массовые операции';
$L['multicat_mass_bind_title']     = 'Привязать страницы к категории';
$L['multicat_mass_bind_desc']      = 'Пакетно добавляет связи между указанными страницами и выбранной категорией.';
$L['multicat_mass_unbind_title']   = 'Отвязать страницы от категории';
$L['multicat_mass_unbind_desc']    = 'Пакетно удаляет связи между указанными страницами и выбранной категорией.';
$L['multicat_mass_label_ids']      = 'ID страниц (через запятую)';
$L['multicat_mass_ids_hint']       = 'Например: 101,102,103';
$L['multicat_mass_label_bind_cat'] = 'Привязать к категории';
$L['multicat_btn_bind']            = 'Привязать';
$L['multicat_mass_label_ids_unbind'] = 'ID страниц (через запятую)';
$L['multicat_mass_label_unbind_cat'] = 'Отвязать от категории';
$L['multicat_btn_unbind']            = 'Отвязать';

// Список: колонки
$L['multicat_col_main_cat'] = 'Основная категория';
$L['multicat_col_links']    = 'Мультикатегории';

// Фильтр по наличию связей
$L['multicat_filter_link_label']   = 'Наличие связей';
$L['multicat_filter_link_all']     = 'Все';
$L['multicat_filter_link_with']    = 'Только со связями';
$L['multicat_filter_link_without'] = 'Только без связей';

// Пустой список связей
$L['multicat_no_links'] = '— нет связей —';

// Массовая очистка / очистка всех связей страницы
$L['multicat_unlinked']          = 'Удалено связей: %d';
$L['multicat_massunlinked']      = 'Удалено связей: %d у %d страниц';
$L['multicat_confirm_unlink_all']   = 'Удалить все связи этой страницы?';
$L['multicat_confirm_massunlink']   = 'Удалить все связи выбранных страниц?';
$L['multicat_btn_unlink_short']     = 'Очистить связи';
$L['multicat_btn_unlink_selected']  = 'Очистить связи выбранных';
$L['multicat_btn_edit_product']     = 'Редактировать страницу';


$L['multicat_page_cats_links']         = 'Мультикатегории статьи';
$L['multicat_page_cats_links_hint']    = 'Дополнительные категории, в которых эта статья показывается как похожая.';

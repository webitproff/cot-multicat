<?php
/**
 * Multicat plugin for Page Module, CMF Cotonti v.1.0.0, PHP v.8.5+, MySQL v.8.4
 * Filename: plugins/multicat/lang/multicat.ua.lang.php
 * Purpose: Ukrainian language file for the Multicat plugin. Defines the strings for the UI
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
$L['cfg_enabled'] = 'Увімкнути множинні категорії';

/**
 * Plugin Info
 */
$L['info_name']  = 'Multicat for Page Module';
$L['info_desc']  = 'Для модуля Page. Дозволяє призначати сторінки одразу до кількох категорій';
$L['info_notes'] = 'у шаблони page.edit.tpl / page.add.tpl: додати {PAGEFORM_CAT} та {PAGEFORM_CAT_HINT} одразу після категорій.';

// Загальні рядки фронтенду
$L['multicat_select']            = 'Виберіть категорії (можна вибрати декілька)';
$L['multicat_cats']              = 'Мультикатегорії';
$L['multicat_cats_edit']         = 'Показувати в категоріях';
$L['multicat_error_no_category'] = 'Помилка: необхідно вибрати хоча б одну категорію';
$L['multicat_help']              = 'у шаблони page.edit.tpl / page.add.tpl: додати {PAGEFORM_CAT} та {PAGEFORM_CAT_HINT} одразу після категорій.';

// Заголовок сторінки адмінки
$L['multicat_admin_title'] = 'Керування мультикатегоріями';

// Вкладки
$L['multicat_tab_list']  = 'Список зв\'язків';
$L['multicat_tab_add']   = 'Додати зв\'язок';
$L['multicat_tab_clean'] = 'Очищення сміття';
$L['multicat_tab_stats'] = 'Статистика';
$L['multicat_tab_mass']  = 'Масові операції';
$L['multicat_warning_tab_under_develop']  = 'Ця вкладка ще на стадії розробки. Функціонал обмежений і може працювати некоректно.';

// Заголовки розділів
$L['multicat_list_title']  = 'Список зв\'язків';
$L['multicat_add_title']   = 'Додавання нового зв\'язку';
$L['multicat_edit_title']  = 'Редагування зв\'язку';
$L['multicat_clean_title'] = 'Очищення сміття';
$L['multicat_stats_title'] = 'Статистика';
$L['multicat_mass_title']  = 'Масові операції';

// Повідомлення дій
$L['multicat_deleted']         = 'Зв\'язок видалено.';
$L['multicat_massdeleted']     = 'Видалено зв\'язків: %d';
$L['multicat_clean_done']      = 'Видалено %d сміттєвих записів.';
$L['multicat_added']           = 'Зв\'язок додано.';
$L['multicat_updated']         = 'Зв\'язок оновлено.';
$L['multicat_item_not_found']  = 'Сторінку з таким ID не знайдено.';
$L['multicat_fill_required']   = 'Заповніть обов\'язкові поля.';
$L['multicat_already_exists']  = 'Такий зв\'язок вже існує.';
$L['multicat_massbound']       = 'Прив\'язано сторінок: %d';
$L['multicat_massunbound']     = 'Відв\'язано сторінок: %d';
$L['multicat_not_exists']      = '[не існує]';
$L['multicat_no_records']      = 'Записів не знайдено.';

// Список / фільтр
$L['multicat_filter_title_placeholder'] = 'Назва сторінки';
$L['multicat_filter_btn']               = 'Фільтр';
$L['multicat_reset']                    = 'Скинути';
$L['multicat_col_id']                   = 'ID сторінки';
$L['multicat_col_title']                = 'Назва сторінки';
$L['multicat_col_cat_id']               = 'ID категорії';
$L['multicat_col_category']             = 'Категорія';
$L['multicat_col_code']                 = 'Код';
$L['multicat_col_actions']              = 'Дії';
$L['multicat_btn_edit_short']           = 'Ред.';
$L['multicat_btn_delete_short']         = 'Вид.';
$L['multicat_confirm_delete']           = 'Точно видалити?';
$L['multicat_confirm_massdelete']       = 'Видалити вибрані зв\'язки?';
$L['multicat_btn_delete_selected']      = 'Видалити вибрані';

// Додавання
$L['multicat_add_label_page']  = 'Сторінка (ID)';
$L['multicat_add_label_cat']   = 'Категорія';
$L['multicat_add_page_hint']   = 'Вкажіть числовий ID сторінки з таблиці pages.';
$L['multicat_btn_add']         = 'Додати зв\'язок';
$L['multicat_select_none']     = '-- Виберіть категорію --';

// Редагування
$L['multicat_edit_label_page']    = 'Сторінка';
$L['multicat_edit_label_new_cat'] = 'Нова категорія';
$L['multicat_btn_save']           = 'Зберегти';
$L['multicat_back_to_list']       = 'Назад до списку';

// Очищення
$L['multicat_clean_header']         = 'Сміттєві записи';
$L['multicat_clean_desc']           = 'Перевірте кількість битих записів і за потреби запустіть очищення.';
$L['multicat_clean_zero_cat']       = 'З нульовим cat_id';
$L['multicat_clean_orphan_items']   = 'З неіснуючою сторінкою';
$L['multicat_clean_orphan_cats']    = 'З неіснуючою категорією';
$L['multicat_clean_total']          = 'Всього до видалення';
$L['multicat_clean_all_clean']      = 'Сміттєвих записів не виявлено.';
$L['multicat_confirm_clean']        = 'Видалити всі сміттєві записи?';
$L['multicat_btn_clean_all']        = 'Очистити все';

// Статистика
$L['multicat_stats_header']       = 'Загальна статистика';
$L['multicat_stats_total_links']  = 'Всього зв\'язків';
$L['multicat_stats_unique_items'] = 'Унікальних сторінок';
$L['multicat_stats_total_cats']   = 'Унікальних категорій';
$L['multicat_stats_avg']          = 'Середнє на сторінку';
$L['multicat_stats_top_cats']     = 'Топ-10 категорій';
$L['multicat_stats_col_category'] = 'Категорія';
$L['multicat_stats_col_count']    = 'Зв\'язків';

// Масові операції
$L['multicat_mass_header']         = 'Масові операції';
$L['multicat_mass_bind_title']     = 'Прив\'язати сторінки до категорії';
$L['multicat_mass_bind_desc']      = 'Пакетно додає зв\'язки між вказаними сторінками та вибраною категорією.';
$L['multicat_mass_unbind_title']   = 'Відв\'язати сторінки від категорії';
$L['multicat_mass_unbind_desc']    = 'Пакетно видаляє зв\'язки між вказаними сторінками та вибраною категорією.';
$L['multicat_mass_label_ids']      = 'ID сторінок (через кому)';
$L['multicat_mass_ids_hint']       = 'Наприклад: 101,102,103';
$L['multicat_mass_label_bind_cat'] = 'Прив\'язати до категорії';
$L['multicat_btn_bind']            = 'Прив\'язати';
$L['multicat_mass_label_ids_unbind'] = 'ID сторінок (через кому)';
$L['multicat_mass_label_unbind_cat'] = 'Відв\'язати від категорії';
$L['multicat_btn_unbind']            = 'Відв\'язати';

// Список: колонки
$L['multicat_col_main_cat'] = 'Основна категорія';
$L['multicat_col_links']    = 'Мультикатегорії';

// Фільтр за наявністю зв\'язків
$L['multicat_filter_link_label']   = 'Наявність зв\'язків';
$L['multicat_filter_link_all']     = 'Всі';
$L['multicat_filter_link_with']    = 'Тільки зі зв\'язками';
$L['multicat_filter_link_without'] = 'Тільки без зв\'язків';

// Порожній список зв\'язків
$L['multicat_no_links'] = '— немає зв\'язків —';

// Масова очистка / очистка всіх зв\'язків сторінки
$L['multicat_unlinked']          = 'Видалено зв\'язків: %d';
$L['multicat_massunlinked']      = 'Видалено %d зв\'язків у %d сторінок';
$L['multicat_confirm_unlink_all']   = 'Видалити всі зв\'язки цієї сторінки?';
$L['multicat_confirm_massunlink']   = 'Видалити всі зв\'язки вибраних сторінок?';
$L['multicat_btn_unlink_short']     = 'Очистити зв\'язки';
$L['multicat_btn_unlink_selected']  = 'Очистити зв\'язки вибраних';
$L['multicat_btn_edit_product']     = 'Редагувати сторінку';


$L['multicat_page_cats_links']         = 'Мультикатегорії статті';
$L['multicat_page_cats_links_hint']    = 'Додаткові категорії, в яких ця стаття показується як схожа.';

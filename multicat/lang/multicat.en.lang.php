<?php
/**
 * Multicat plugin for Page Module, CMF Cotonti v.1.0.0, PHP v.8.4+, MySQL v.8.0
 * Filename: plugins/multicat/lang/multicat.en.lang.php
 * Purpose: English language file for the Multicat plugin. Defines the strings for the UI
 * Date=Sep 11, 2026
 * @package multicat
 * @version 1.7.9
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff
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
$L['info_name']  = 'Multicat for Page Module';
$L['info_desc']  = 'For the Page module. Allows assigning a page to several categories at once';
$L['info_notes'] = 'in page.edit.tpl / page.add.tpl templates: add {PAGEFORM_CAT} and {PAGEFORM_CAT_HINT} right after the categories.';

// Common front-end strings
$L['multicat_select']            = 'Select categories (multiple selection allowed)';
$L['multicat_cats']              = 'Multicategories';
$L['multicat_cats_edit']         = 'Show in categories';
$L['multicat_error_no_category'] = 'Error: at least one category must be selected';
$L['multicat_help']              = 'in page.edit.tpl / page.add.tpl templates: add {PAGEFORM_CAT} and {PAGEFORM_CAT_HINT} right after the categories.';

// Admin page title
$L['multicat_admin_title'] = 'Multicategory management';

// Tabs
$L['multicat_tab_list']  = 'Links list';
$L['multicat_tab_add']   = 'Add link';
$L['multicat_tab_clean'] = 'Cleanup';
$L['multicat_tab_stats'] = 'Statistics';
$L['multicat_tab_mass']  = 'Bulk operations';
$L['multicat_warning_tab_under_develop']  = 'This tab is still under development. Functionality is limited and may not work correctly.';

// Section titles
$L['multicat_list_title']  = 'Links list';
$L['multicat_add_title']   = 'Add new link';
$L['multicat_edit_title']  = 'Edit link';
$L['multicat_clean_title'] = 'Cleanup';
$L['multicat_stats_title'] = 'Statistics';
$L['multicat_mass_title']  = 'Bulk operations';

// Action messages
$L['multicat_deleted']         = 'Link deleted.';
$L['multicat_massdeleted']     = 'Links deleted: %d';
$L['multicat_clean_done']      = 'Removed %d orphan records.';
$L['multicat_added']           = 'Link added.';
$L['multicat_updated']         = 'Link updated.';
$L['multicat_item_not_found']  = 'No page found with this ID.';
$L['multicat_fill_required']   = 'Please fill in the required fields.';
$L['multicat_already_exists']  = 'This link already exists.';
$L['multicat_massbound']       = 'Pages bound: %d';
$L['multicat_massunbound']     = 'Pages unbound: %d';
$L['multicat_not_exists']      = '[does not exist]';
$L['multicat_no_records']      = 'No records found.';

// List / filter
$L['multicat_filter_title_placeholder'] = 'Page title';
$L['multicat_filter_btn']               = 'Filter';
$L['multicat_reset']                    = 'Reset';
$L['multicat_col_id']                   = 'Page ID';
$L['multicat_col_title']                = 'Page title';
$L['multicat_col_cat_id']               = 'Category ID';
$L['multicat_col_category']             = 'Category';
$L['multicat_col_code']                 = 'Code';
$L['multicat_col_actions']              = 'Actions';
$L['multicat_btn_edit_short']           = 'Ed.';
$L['multicat_btn_delete_short']         = 'Del.';
$L['multicat_confirm_delete']           = 'Really delete?';
$L['multicat_confirm_massdelete']       = 'Delete the selected links?';
$L['multicat_btn_delete_selected']      = 'Delete selected';

// Add
$L['multicat_add_label_page']  = 'Page (ID)';
$L['multicat_add_label_cat']   = 'Category';
$L['multicat_add_page_hint']   = 'Enter the numeric ID of the page from the pages table.';
$L['multicat_btn_add']         = 'Add link';
$L['multicat_select_none']     = '-- Select a category --';

// Edit
$L['multicat_edit_label_page']    = 'Page';
$L['multicat_edit_label_new_cat'] = 'New category';
$L['multicat_btn_save']           = 'Save';
$L['multicat_back_to_list']       = 'Back to list';

// Cleanup
$L['multicat_clean_header']         = 'Orphan records';
$L['multicat_clean_desc']           = 'Check the number of broken records and run cleanup if necessary.';
$L['multicat_clean_zero_cat']       = 'With zero cat_id';
$L['multicat_clean_orphan_items']   = 'With non-existent page';
$L['multicat_clean_orphan_cats']    = 'With non-existent category';
$L['multicat_clean_total']          = 'Total to delete';
$L['multicat_clean_all_clean']      = 'No orphan records found.';
$L['multicat_confirm_clean']        = 'Delete all orphan records?';
$L['multicat_btn_clean_all']        = 'Clean all';

// Statistics
$L['multicat_stats_header']       = 'General statistics';
$L['multicat_stats_total_links']  = 'Total links';
$L['multicat_stats_unique_items'] = 'Unique pages';
$L['multicat_stats_total_cats']   = 'Unique categories';
$L['multicat_stats_avg']          = 'Average per page';
$L['multicat_stats_top_cats']     = 'Top 10 categories';
$L['multicat_stats_col_category'] = 'Category';
$L['multicat_stats_col_count']    = 'Links';

// Bulk operations
$L['multicat_mass_header']         = 'Bulk operations';
$L['multicat_mass_bind_title']     = 'Bind pages to a category';
$L['multicat_mass_bind_desc']      = 'Batch-adds links between the specified pages and the selected category.';
$L['multicat_mass_unbind_title']   = 'Unbind pages from a category';
$L['multicat_mass_unbind_desc']    = 'Batch-removes links between the specified pages and the selected category.';
$L['multicat_mass_label_ids']      = 'Page IDs (comma-separated)';
$L['multicat_mass_ids_hint']       = 'For example: 101,102,103';
$L['multicat_mass_label_bind_cat'] = 'Bind to category';
$L['multicat_btn_bind']            = 'Bind';
$L['multicat_mass_label_ids_unbind'] = 'Page IDs (comma-separated)';
$L['multicat_mass_label_unbind_cat'] = 'Unbind from category';
$L['multicat_btn_unbind']            = 'Unbind';

// List: columns
$L['multicat_col_main_cat'] = 'Main category';
$L['multicat_col_links']    = 'Multicategories';

// Filter by link presence
$L['multicat_filter_link_label']   = 'Links presence';
$L['multicat_filter_link_all']     = 'All';
$L['multicat_filter_link_with']    = 'With links only';
$L['multicat_filter_link_without'] = 'Without links only';

// Empty links list
$L['multicat_no_links'] = '— no links —';

// Bulk cleanup / clear all links of a page
$L['multicat_unlinked']          = 'Links deleted: %d';
$L['multicat_massunlinked']      = 'Deleted %d links from %d pages';
$L['multicat_confirm_unlink_all']   = 'Delete all links of this page?';
$L['multicat_confirm_massunlink']   = 'Delete all links of the selected pages?';
$L['multicat_btn_unlink_short']     = 'Clear links';
$L['multicat_btn_unlink_selected']  = 'Clear links of selected';
$L['multicat_btn_edit_product']     = 'Edit page';
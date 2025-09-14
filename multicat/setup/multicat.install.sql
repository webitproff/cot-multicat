/**
 * Multicat plugin for Page Module, CMF Cotonti Siena v.0.9.26, PHP v.8.4+, MySQL v.8.0
 * Filename: setup/multicat.install.sql
 * Purpose: Создаёт таблицу связей для плагина Multicat и мигрирует существующие категории из cot_pages. Таблица заполняется при установке автоматически.
 * Date=2025-09-14
 * package multicat
 * version 1.1.0
 * author webitproff
 * copyright Copyright (c) webitproff 2025 | https://github.com/webitproff
 * license BSD
 */


CREATE TABLE IF NOT EXISTS `cot_page_multicats` (
  `pcat_page_id` int UNSIGNED NOT NULL,
  `pcat_cat_id` mediumint UNSIGNED NOT NULL,
  UNIQUE KEY `pcat_unique` (`pcat_page_id`, `pcat_cat_id`),
  KEY `pcat_page_id` (`pcat_page_id`),
  KEY `pcat_cat_id` (`pcat_cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Миграция существующих категорий, только валидные page_id и structure_id
INSERT IGNORE INTO `cot_page_multicats` (`pcat_page_id`, `pcat_cat_id`)
SELECT p.page_id, s.structure_id
FROM `cot_pages` p
JOIN `cot_structure` s 
  ON p.page_cat = s.structure_code
WHERE p.page_id > 0
  AND s.structure_id > 0
  AND p.page_cat != ''
  AND s.structure_area = 'page';

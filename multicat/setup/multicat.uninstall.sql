-- 
-- Multicat plugin for Page Module, CMF Cotonti v.1.0.0, PHP v.8.4+, MySQL v.8.0
-- Filename: plugins/multicat/setup/multicat.uninstall.sql
-- Purpose: Удаляет таблицу связей cot_page_multicats при деинсталляции плагина Multicat.
-- Date=Sep 12, 2026
-- package multicat
-- version 1.7.9
-- author webitproff
-- copyright Copyright (c) webitproff 2026 | https://github.com/webitproff
-- license BSD
-- 


DROP TABLE IF EXISTS `cot_page_multicats`;
# Multicat — Multiple Categories for the Page Module in CMF Cotonti

[![Cotonti](https://img.shields.io/badge/Cotonti-v1.0.0+-blue)](https://github.com/Cotonti/Cotonti)
[![PHP](https://img.shields.io/badge/PHP-8.4%2B-green)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-yellow)](https://www.mysql.com/)
[![License](https://img.shields.io/badge/License-BSD-red)](https://github.com/webitproff/cot-multicat/blob/main/LICENSE)
[![Version](https://img.shields.io/badge/Version-1.7.9-orange)](https://github.com/webitproff/cot-multicat)

**Multicat** is a plugin for CMF Cotonti that allows a single article (Page module item) to belong to several categories at once. The plugin is implemented entirely through standard Cotonti hooks, requires no changes to the core or the Page module, and provides 100% compatibility with existing extensions.

---

## Table of Contents

1. [What is it?](#what-is-it-en)
2. [How does it work?](#how-it-works-en)
3. [Why do you need it?](#why-needed-en)
4. [Advantages](#advantages-en)
5. [Installation](#installation-en)
6. [Configuration](#configuration-en)
7. [Usage](#usage-en)
8. [Admin Panel](#admin-panel-en)
9. [File Structure](#file-structure-en)
10. [Plugin Hooks](#hooks-en)
11. [Plugin Functions](#functions-en)
12. [Database Table](#database-en)
13. [Language Files](#languages-en)
14. [Template Integration](#templates-en)
15. [For Developers](#developers-en)
16. [Common Issues and Solutions](#troubleshooting-en)
17. [Metadata](#meta-en)
18. [License](#license-en)

---

<a name="what-is-it-en"></a>
## 1. What is it?

**Multicat** is a plugin that allows the same article to belong to several categories at once on your site.

Normally in Cotonti a page can belong to only one category (e.g. "News" or "Blog"). With Multicat a single article can simultaneously be in "News", "Promotions" and "Reviews".

> **Download the plugin for free:** [https://github.com/webitproff/cot-multicat](https://github.com/webitproff/cot-multicat)

---

<a name="how-it-works-en"></a>
## 2. How does it work?

- During installation the plugin creates an additional database table `cot_page_multicats`, which stores which categories each article belongs to.
- When you edit an article, a convenient hierarchical list of category checkboxes appears, respecting access permissions. You can select as many categories as you need.
- On the site, the article is automatically displayed in all selected categories.
- The plugin's admin panel provides a dedicated section for managing links: list, statistics, cleanup, bulk operations.

---

<a name="why-needed-en"></a>
## 3. Why do you need it?

1. **Convenience for visitors** — the user does not have to look for an article in only one section. It will be found in any suitable category.
2. **SEO (search engine optimization)** — the article gets more entry points for search engines. At the same time, duplicate URLs are **not created**: the article always keeps a single primary URL, while categories act as additional "entry points".
3. **Flexibility** — handy when an article fits several topics. For example, "Phone Review" can be placed in "News", "Gadgets" and "Reviews".

---

<a name="advantages-en"></a>
## 4. Advantages

- Simple installation and operation.
- Does not break old articles: if an article has only one category, everything stays as before.
- Automatic migration of existing categories on installation.
- Dedicated admin panel with a list, statistics, cleanup, and bulk operations.
- Support for Russian, English and Ukrainian UI languages.
- Works through standard Cotonti hooks — no changes to the core or the Page module.
- Suitable for any project — blog, news site, online store.

**Bottom line**: Multicat makes your site more convenient for visitors and more useful for search engines. Articles become more visible and managing them becomes more flexible.

---

<a name="installation-en"></a>
## 5. Installation

### 5.1. Uploading files

Copy the `multicat` folder into the `plugins/` directory on your server.

### 5.2. Installing via the admin panel

1. In the admin panel go to **Extensions**.
2. Find the `Multicat` plugin and click **Install**.
3. During installation, the `cot_page_multicats` table is created automatically, and existing categories from `cot_pages` are migrated.

### 5.3. Template edits

Add the tags to your templates:

- In `page.edit.tpl` and `page.add.tpl` — add `{PAGEFORM_CAT}` and `{PAGEFORM_CAT_HINT}` right after the standard category field.

```
<!-- IF {PHP|cot_plugin_active('multicat')} -->
<div class="col-12">
	<label for="multicat" class="form-label fw-semibold">{PHP.L.multicat_cats_edit}</label>
	<div class="input-group has-validation" id="multicat">{PAGEFORM_CAT} </div>
	<small class="form-text text-muted">{PHP.L.multicat_cats}. {PAGEFORM_CAT_HINT}</small>
</div>
<!-- ENDIF -->
```

- 
- In `page.admin.tpl` — inside the `PAGE_ROW` block (e.g. after `{ADMIN_PAGE_LOCAL_STATUS}`) add `{ADMIN_PAGE_MULTICATS}`.

> **Important:** if you do not add these tags, multiple categories will not work — there will simply be no selection form and no output in the admin area.

---

<a name="configuration-en"></a>
## 6. Configuration

In the plugin configuration (**Admin → Extensions → Multicat → Configuration**) one option is available:

- `enabled` — enable multiple categories (enabled by default).

---

<a name="usage-en"></a>
## 7. Usage

### 7.1. Adding and editing a page

- In the page form, tick the categories in the hierarchical checkbox list.
- For a new page, at least one category is required. For an existing page you can uncheck everything — then the primary category stays as it was, and multicategories are cleared.
- The first ticked category becomes the primary one and is written to `page_cat`.

### 7.2. Viewing

- In the admin page list, all categories are displayed.
- On the site, the category filter shows pages from multicategories.

### 7.3. Managing links

Go to **Extensions → Multicat → Administration** (or directly `admin.php?m=other&p=multicat`). The **Links list** tab: filter, inspect, and clear links either in bulk or per page.

### 7.4. Deletion

When a page is deleted from the Page module, all its links are removed automatically (hook `page.delete.first`).

---

<a name="admin-panel-en"></a>
## 8. Admin Panel

The multicategory management section is at `admin.php?m=other&p=multicat` and consists of tabs:

| Tab | URL parameter | Purpose |
|-----|---------------|---------|
| Links list | `tab=list` | List of pages with their primary category and all multicategories. Filters: category, page title, link presence. Supports pagination and bulk link clearing. |
| Add link | `tab=add` | Add a single "page ↔ category" link. *Tab under development.* |
| Edit link | `tab=edit` | Service tab. Opened from the list. *Tab under development.* |
| Cleanup | `tab=clean` | Count and delete "orphan" records. *Tab under development.* |
| Statistics | `tab=stats` | Total number of links, unique pages and categories. *Tab under development.* |
| Bulk operations | `tab=mass` | Batch binding/unbinding of pages. *Tab under development.* |

> **Note:** some admin panel tabs are still under development. The main stable tab is **Links list**.

---

<a name="file-structure-en"></a>
## 9. File Structure

```
plugins/multicat/
├── inc/
│   └── multicat.functions.php               Core multicategory functions
├── lang/
│   ├── multicat.ru.lang.php                 Russian language file
│   ├── multicat.en.lang.php                 English language file
│   └── multicat.ua.lang.php                 Ukrainian language file
├── setup/
│   ├── multicat.install.sql                 Table creation + data migration
│   └── multicat.uninstall.sql               Table removal on uninstall
├── tpl/
│   └── multicat.admin.tpl                   Admin panel template
├── multicat.admin.php                       Hook tools — admin panel
├── multicat.global.php                      Hook global — table registration
├── multicat.page.add.add.done.php           Hook page.add.add.done
├── multicat.page.admin.loop.php             Hook page.admin.loop
├── multicat.page.delete.first.php           Hook page.delete.first
├── multicat.page.edit.import.php            Hook page.edit.update.import
├── multicat.page.edit.tags.php              Hook page.edit.tags
├── multicat.page.edit.update.done.php       Hook page.edit.update.done
├── multicat.page.list.query.php             Hook page.list.query
└── multicat.setup.php                       Plugin registration and settings
```

---

<a name="hooks-en"></a>
## 10. Plugin Hooks

| File | Hook | Purpose |
|------|------|---------|
| `multicat.global.php` | `global` | Registers the `cot_page_multicats` table, loads the language file, defines the table alias. |
| `multicat.admin.php` | `tools` | The plugin admin panel. |
| `multicat.page.add.add.done.php` | `page.add.add.done` | Saves categories after a page is added. |
| `multicat.page.admin.loop.php` | `page.admin.loop` | Displays the category list in the admin page list (tag `{ADMIN_PAGE_MULTICATS}`). |
| `multicat.page.delete.first.php` | `page.delete.first` | Removes links before a page is deleted. |
| `multicat.page.edit.tags.php` | `page.edit.tags` | Generates the hierarchical checkbox list in the edit form (tags `{PAGEFORM_CAT}` and `{PAGEFORM_CAT_HINT}`). |
| `multicat.page.edit.import.php` | `page.edit.update.import` | Imports categories from POST and sets the first one as primary (`page_cat`). |
| `multicat.page.edit.update.done.php` | `page.edit.update.done` | Saves links to the DB after a page update. |
| `multicat.page.list.query.php` | `page.list.query` | Extends the SQL filter of the page list with multicategory support. |

---

<a name="functions-en"></a>
## 11. Plugin Functions

The file `inc/multicat.functions.php` contains three core functions:

- **`multicat_get_cats($page_id)`** — returns an array of `structure_id` values for the categories the page belongs to.
- **`multicat_get_cat_titles($page_id)`** — returns an array of category titles (used in the admin area).
- **`multicat_save_cats($page_id, $cats)`** — completely rewrites the page links: deletes old records and inserts new ones. An empty array is a valid operation (clearing all multicategories).

> **Important:** do not confuse this with the `page_cat` field in the `cot_pages` table — that is the primary category of the page, which remains unchanged when working with multicategories.

---

<a name="database-en"></a>
## 12. Database Table

The plugin creates one table: `cot_page_multicats`.

```sql
CREATE TABLE IF NOT EXISTS `cot_page_multicats` (
  `pcat_page_id` int UNSIGNED NOT NULL,
  `pcat_cat_id` mediumint UNSIGNED NOT NULL,
  UNIQUE KEY `pcat_unique` (`pcat_page_id`, `pcat_cat_id`),
  KEY `pcat_page_id` (`pcat_page_id`),
  KEY `pcat_cat_id` (`pcat_cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

On installation existing pages are migrated: their `page_cat` is matched against `structure_code` from `cot_structure`, and one record is added to the new table for each valid pair.

---

<a name="languages-en"></a>
## 13. Language Files

The plugin ships with Russian (`ru`), English (`en`) and Ukrainian (`ua`) language files. The files are located in the `lang/` directory. The language file is loaded according to the current administrator language.

---

<a name="templates-en"></a>
## 14. Template Integration

### 14.1. `page.edit.tpl` and `page.add.tpl`

Add right after the standard category selector:

```html
<!-- IF {PHP|cot_plugin_active('multicat')} -->
<div class="col-12">
    <label class="form-label fw-semibold">{PHP.L.multicat_cats_edit}</label>
    <div class="multicat-checkboxes">{PAGEFORM_CAT}</div>
    <small class="form-text text-muted mt-1">{PAGEFORM_CAT_HINT}</small>
</div>
<!-- ENDIF -->
```

### 14.2. `page.admin.tpl`

Inside the `PAGE_ROW` block (e.g. after `{ADMIN_PAGE_LOCAL_STATUS}`) add:

```html
<!-- IF {PHP|cot_plugin_active('multicat')} -->
<div class="text-muted small">{ADMIN_PAGE_MULTICATS}</div>
<!-- ENDIF -->
```

---

<a name="developers-en"></a>
## 15. For Developers

### 15.1. Architecture

The plugin uses standard Cotonti hooks and requires no changes to the core. All data is stored in a separate `cot_page_multicats` table, ensuring full compatibility with existing extensions.

### 15.2. Save logic

- When a page is saved, the `page.edit.update.import` hook takes the first ticked category and writes it to `page_cat`.
- The `page.edit.update.done` hook calls `multicat_save_cats()`, which completely rewrites the links.
- When a page is deleted, the `page.delete.first` hook removes all links.

### 15.3. Extending functionality

To add your own logic you can use additional Cotonti hooks or override the plugin functions in your own extension.

---

<a name="troubleshooting-en"></a>
## 16. Common Issues and Solutions

| Issue | Solution |
|-------|----------|
| "At least one category must be selected" error | Make sure at least one category is ticked. For an existing page it is enough that the primary category already exists in the DB. |
| Categories are not shown | Check access permissions (`cot_auth`) and the presence of `structure_id` in the DB. |
| Template conflicts | Make sure the tags `{PAGEFORM_CAT}` and `{PAGEFORM_CAT_HINT}` are added. |
| Migration did not run | Check the SQL in `setup/multicat.install.sql` and run it manually. |
| The category filter does not include multicategories | Make sure the `page.list.query` hook is registered and the file `multicat.page.list.query.php` is present in the plugin root. |

---

<a name="meta-en"></a>
## 17. Metadata

- **Name:** Multicat
- **Package:** multicat
- **Version:** 1.7.9
- **Release date:** 2026-09-12
- **Author:** webitproff
- **License:** BSD
- **Repository:** [https://github.com/webitproff/cot-multicat](https://github.com/webitproff/cot-multicat)
- **Compatibility:** Cotonti v.1.0.0+, Page module, PHP 8.4+, MySQL 8.0+

---

<a name="license-en"></a>
## 18. License

BSD License. See the [LICENSE](https://github.com/webitproff/cot-multicat/blob/main/LICENSE) file for details.

---

<p align="center">
  <strong>Multicat</strong> — make your site more convenient for visitors and more useful for search engines!
</p>

---



# Multicat — множественные категории для модуля Page в CMF Cotonti

[![Cotonti](https://img.shields.io/badge/Cotonti-v1.0.0+-blue)](https://github.com/Cotonti/Cotonti)
[![PHP](https://img.shields.io/badge/PHP-8.4%2B-green)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-yellow)](https://www.mysql.com/)
[![License](https://img.shields.io/badge/License-BSD-red)](https://github.com/webitproff/cot-multicat/blob/main/LICENSE)
[![Version](https://img.shields.io/badge/Version-1.7.9-orange)](https://github.com/webitproff/cot-multicat)

**Multicat** — это плагин для CMF Cotonti, который позволяет одной и той же статье (странице модуля Page) находиться сразу в нескольких категориях. Плагин полностью реализован через стандартные хуки Cotonti, не требует изменений в ядре или модуле Page и обеспечивает 100% совместимость с существующими расширениями.

---

## Оглавление

1. [Что это такое?](#what-is-it)
2. [Как это работает?](#how-it-works)
3. [Зачем это нужно?](#why-needed)
4. [Преимущества](#advantages)
5. [Установка](#installation)
6. [Настройка](#configuration)
7. [Использование](#usage)
8. [Административная панель](#admin-panel)
9. [Структура файлов](#file-structure)
10. [Хуки плагина](#hooks)
11. [Функции плагина](#functions)
12. [Таблица базы данных](#database)
13. [Языковые файлы](#languages)
14. [Интеграция с шаблонами](#templates)
15. [Для разработчиков](#developers)
16. [Возможные проблемы и решения](#troubleshooting)
17. [Метаданные](#meta)
18. [Лицензия](#license)

---

<a name="what-is-it"></a>
## 1. Что это такое?

**Multicat** — это плагин, который позволяет одной и той же статье находиться сразу в нескольких категориях на сайте.

Обычно в Cotonti страница может быть только в одной категории (например, «Новости» или «Блог»). С Multicat одна статья может одновременно быть и в «Новости», и в «Акции», и в «Обзоры».

> **Скачать плагин бесплатно:** [https://github.com/webitproff/cot-multicat](https://github.com/webitproff/cot-multicat)

---

<a name="how-it-works"></a>
## 2. Как это работает?

- При установке плагин создаёт дополнительную таблицу `cot_page_multicats` в базе данных. В ней хранится информация, к каким категориям относится каждая статья.
- Когда вы редактируете статью, появляется удобный иерархический список чекбоксов категорий с учётом прав доступа. Можно выбрать сразу несколько.
- На сайте статья автоматически показывается во всех выбранных категориях.
- В административной панели плагина есть отдельный раздел управления связями: список, статистика, очистка мусора, массовые операции.

---

<a name="why-needed"></a>
## 3. Зачем это нужно?

1. **Удобство для посетителей** — пользователю не нужно искать статью только в одной рубрике. Он найдёт её в любой подходящей категории.
2. **SEO (поисковая оптимизация)** — статья получает больше точек входа для поисковых систем. При этом дубликаты URL **не создаются**: у статьи всегда остаётся один основной адрес, а категории выступают как дополнительные «точки входа».
3. **Гибкость** — удобно, когда статья подходит под несколько тем. Например, «Обзор телефона» можно разместить в «Новости», «Гаджеты» и «Обзоры».

---

<a name="advantages"></a>
## 4. Преимущества

- Простая установка и работа.
- Не ломает старые статьи: если у статьи только одна категория — всё остаётся, как раньше.
- Автоматическая миграция существующих категорий при установке.
- Отдельная административная панель со списком, статистикой, очисткой мусора и массовыми операциями.
- Поддержка русского, английского и украинского языков интерфейса.
- Работает через стандартные хуки Cotonti — без правок ядра и модуля Page.
- Подходит для любых проектов — блога, новостника, интернет-магазина.

**Итог**: Multicat делает ваш сайт удобнее для посетителей и полезнее для поисковых систем. Статьи становятся более заметными, а управление ими — гибче.

---

<a name="installation"></a>
## 5. Установка

### 5.1. Загрузка файлов

Скопируйте папку `multicat` в каталог `plugins/` на сервере.

### 5.2. Установка через админ-панель

1. В админ-панели перейдите в **Расширения**.
2. Найдите плагин `Multicat` и нажмите **Установить**.
3. При установке автоматически создастся таблица `cot_page_multicats` и будут мигрированы существующие категории из `cot_pages`.

### 5.3. Правки шаблонов

Добавьте теги в шаблоны:

- В `page.edit.tpl` и `page.add.tpl` — добавить `{PAGEFORM_CAT}` и `{PAGEFORM_CAT_HINT}` сразу после стандартного поля выбора категории.
- В `page.admin.tpl` — в блоке `PAGE_ROW` (например, после `{ADMIN_PAGE_LOCAL_STATUS}`) добавить `{ADMIN_PAGE_MULTICATS}`.

> **Важно:** если не добавить эти теги, множественные категории работать не будут — просто не будет формы выбора и вывода в админке.

---

<a name="configuration"></a>
## 6. Настройка

В конфигурации плагина (**Админ → Расширения → Multicat → Конфигурация**) доступна одна опция:

- `enabled` — включить множественные категории (по умолчанию включена).

---

<a name="usage"></a>
## 7. Использование

### 7.1. Добавление и редактирование страницы

- В форме страницы отметьте нужные категории в иерархическом списке чекбоксов.
- Для новой страницы обязательно хотя бы одна категория. Для существующей страницы можно снять все отметки — тогда основная категория останется прежней, а мультикатегории будут очищены.
- Первая отмеченная категория становится основной и записывается в `page_cat`.

### 7.2. Просмотр

- В админ-списке страниц отображаются все категории.
- На сайте фильтр по категории показывает страницы из мультикатегорий.

### 7.3. Управление связями

Перейдите в **Расширения → Multicat → Администрирование** (или напрямую `admin.php?m=other&p=multicat`). Вкладка **Список связей**: фильтруйте, просматривайте, чистите связи как массово, так и у отдельных страниц.

### 7.4. Удаление

При удалении страницы из модуля Page все её связи автоматически удаляются (хук `page.delete.first`).

---

<a name="admin-panel"></a>
## 8. Административная панель

Раздел управления мультикатегориями находится по адресу `admin.php?m=other&p=multicat` и состоит из вкладок:

| Вкладка | Параметр URL | Назначение |
|---------|-------------|------------|
| Список связей | `tab=list` | Список страниц с их основной категорией и всеми мультикатегориями. Фильтры: категория, название страницы, наличие связей. Поддерживает пагинацию, массовую очистку связей. |
| Добавить связь | `tab=add` | Добавление одиночной связи «страница ↔ категория». *Вкладка в стадии разработки.* |
| Редактирование связи | `tab=edit` | Служебная вкладка. Открывается из списка. *Вкладка в стадии разработки.* |
| Очистка мусора | `tab=clean` | Подсчёт и удаление «мусорных» записей. *Вкладка в стадии разработки.* |
| Статистика | `tab=stats` | Общее количество связей, уникальных страниц и категорий. *Вкладка в стадии разработки.* |
| Массовые операции | `tab=mass` | Пакетная привязка/отвязка страниц. *Вкладка в стадии разработки.* |

> **Примечание:** часть вкладок административной панели находится в стадии разработки. Основной стабильной вкладкой является **Список связей**.

---

<a name="file-structure"></a>
## 9. Структура файлов

```
plugins/multicat/
├── inc/
│   └── multicat.functions.php               Основные функции работы с мультикатегориями
├── lang/
│   ├── multicat.ru.lang.php                 Русский языковой файл
│   ├── multicat.en.lang.php                 Английский языковой файл
│   └── multicat.ua.lang.php                 Украинский языковой файл
├── setup/
│   ├── multicat.install.sql                 Создание таблицы + миграция данных
│   └── multicat.uninstall.sql               Удаление таблицы при деинсталляции
├── tpl/
│   └── multicat.admin.tpl                   Шаблон административной панели
├── multicat.admin.php                       Хук tools — административная панель
├── multicat.global.php                      Хук global — регистрация таблицы
├── multicat.page.add.add.done.php           Хук page.add.add.done
├── multicat.page.admin.loop.php             Хук page.admin.loop
├── multicat.page.delete.first.php           Хук page.delete.first
├── multicat.page.edit.import.php            Хук page.edit.update.import
├── multicat.page.edit.tags.php              Хук page.edit.tags
├── multicat.page.edit.update.done.php       Хук page.edit.update.done
├── multicat.page.list.query.php             Хук page.list.query
└── multicat.setup.php                       Регистрация плагина и настроек
```

---

<a name="hooks"></a>
## 10. Хуки плагина

| Файл | Хук | Назначение |
|------|-----|------------|
| `multicat.global.php` | `global` | Регистрация таблицы `cot_page_multicats`, загрузка языкового файла, определение псевдонима таблицы. |
| `multicat.admin.php` | `tools` | Административная панель плагина. |
| `multicat.page.add.add.done.php` | `page.add.add.done` | Сохранение категорий после добавления страницы. |
| `multicat.page.admin.loop.php` | `page.admin.loop` | Отображение списка категорий в админ-списке страниц (тег `{ADMIN_PAGE_MULTICATS}`). |
| `multicat.page.delete.first.php` | `page.delete.first` | Удаление связей перед удалением страницы. |
| `multicat.page.edit.tags.php` | `page.edit.tags` | Генерация иерархического списка чекбоксов в форме редактирования (теги `{PAGEFORM_CAT}` и `{PAGEFORM_CAT_HINT}`). |
| `multicat.page.edit.import.php` | `page.edit.update.import` | Импорт категорий из POST и установка первой как основной (`page_cat`). |
| `multicat.page.edit.update.done.php` | `page.edit.update.done` | Сохранение связей в БД после обновления страницы. |
| `multicat.page.list.query.php` | `page.list.query` | Расширение SQL-фильтра списка страниц с учётом мультикатегорий. |

---

<a name="functions"></a>
## 11. Функции плагина

Файл `inc/multicat.functions.php` содержит три основные функции:

- **`multicat_get_cats($page_id)`** — возвращает массив `structure_id` категорий, в которых находится страница.
- **`multicat_get_cat_titles($page_id)`** — возвращает массив названий категорий (используется в админке).
- **`multicat_save_cats($page_id, $cats)`** — полностью перезаписывает связи страницы: удаляет старые записи и вставляет новые. Пустой массив — валидная операция (снятие всех мультикатегорий).

> **Важно:** не путать с полем `page_cat` в таблице `cot_pages` — это основная категория страницы, которая остаётся неизменной при работе с мультикатегориями.

---

<a name="database"></a>
## 12. Таблица базы данных

Плагин создаёт одну таблицу: `cot_page_multicats`.

```sql
CREATE TABLE IF NOT EXISTS `cot_page_multicats` (
  `pcat_page_id` int UNSIGNED NOT NULL,
  `pcat_cat_id` mediumint UNSIGNED NOT NULL,
  UNIQUE KEY `pcat_unique` (`pcat_page_id`, `pcat_cat_id`),
  KEY `pcat_page_id` (`pcat_page_id`),
  KEY `pcat_cat_id` (`pcat_cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

При установке выполняется миграция существующих страниц: их `page_cat` сопоставляется с `structure_code` из `cot_structure`, и для каждой валидной пары в новую таблицу добавляется одна запись.

---

<a name="languages"></a>
## 13. Языковые файлы

Плагин поставляется с русским (`ru`), английским (`en`) и украинским (`ua`) языковыми файлами. Файлы располагаются в каталоге `lang/`. Языковой файл подключается по текущему языку администратора.

---

<a name="templates"></a>
## 14. Интеграция с шаблонами

### 14.1. `page.edit.tpl` и `page.add.tpl`

Добавить сразу после стандартного селектора категорий:

```html
<!-- IF {PHP|cot_plugin_active('multicat')} -->
<div class="col-12">
    <label class="form-label fw-semibold">{PHP.L.multicat_cats_edit}</label>
    <div class="multicat-checkboxes">{PAGEFORM_CAT}</div>
    <small class="form-text text-muted mt-1">{PAGEFORM_CAT_HINT}</small>
</div>
<!-- ENDIF -->
```

### 14.2. `page.admin.tpl`

В блоке `PAGE_ROW` (например, после `{ADMIN_PAGE_LOCAL_STATUS}`) добавить:

```html
<!-- IF {PHP|cot_plugin_active('multicat')} -->
<div class="text-muted small">{ADMIN_PAGE_MULTICATS}</div>
<!-- ENDIF -->
```

---

<a name="developers"></a>
## 15. Для разработчиков

### 15.1. Архитектура

Плагин использует стандартные хуки Cotonti и не требует изменений в ядре. Все данные хранятся в отдельной таблице `cot_page_multicats`, что обеспечивает полную совместимость с существующими расширениями.

### 15.2. Логика сохранения

- При сохранении страницы хук `page.edit.update.import` берёт первую отмеченную категорию и записывает её в `page_cat`.
- Хук `page.edit.update.done` вызывает `multicat_save_cats()`, которая полностью перезаписывает связи.
- При удалении страницы хук `page.delete.first` удаляет все связи.

### 15.3. Расширение функциональности

Для добавления собственной логики можно использовать дополнительные хуки Cotonti или переопределить функции плагина в своём расширении.

---

<a name="troubleshooting"></a>
## 16. Возможные проблемы и решения

| Проблема | Решение |
|----------|---------|
| Ошибка «необходимо выбрать хотя бы одну категорию» | Убедитесь, что отмечена хотя бы одна категория. Для существующей страницы достаточно, чтобы в БД уже была основная категория. |
| Категории не отображаются | Проверьте права доступа (`cot_auth`) и наличие `structure_id` в БД. |
| Конфликты с шаблонами | Убедитесь, что добавлены теги `{PAGEFORM_CAT}` и `{PAGEFORM_CAT_HINT}`. |
| Миграция не сработала | Проверьте SQL в `setup/multicat.install.sql` и выполните его вручную. |
| Фильтр по категории не подмешивает мультикатегории | Убедитесь, что хук `page.list.query` зарегистрирован и файл `multicat.page.list.query.php` присутствует в корне плагина. |

---

<a name="meta"></a>
## 17. Метаданные

- **Название:** Multicat
- **Пакет:** multicat
- **Версия:** 1.7.9
- **Дата выпуска:** 2026-09-12
- **Автор:** webitproff
- **Лицензия:** BSD
- **Репозиторий:** [https://github.com/webitproff/cot-multicat](https://github.com/webitproff/cot-multicat)
- **Совместимость:** Cotonti v.1.0.0+, модуль Page, PHP 8.4+, MySQL 8.0+

---

<a name="license"></a>
## 18. Лицензия

BSD License. Подробности — в файле [LICENSE](https://github.com/webitproff/cot-multicat/blob/main/LICENSE).

---

<p align="center">
  <strong>Multicat</strong> — делайте свой сайт удобнее для посетителей и полезнее для поисковых систем!
</p>

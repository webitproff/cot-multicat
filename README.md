# Multicat Plugin for Cotonti Siena

![Cotonti Siena](https://img.shields.io/badge/Cotonti-Siena%20v0.9.26-blue) ![PHP](https://img.shields.io/badge/PHP-8.4%2B-green) ![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-yellow) ![License](https://img.shields.io/badge/License-BSD-red) ![Version](https://img.shields.io/badge/Version-1.1.0-orange)

## Plugin Description

The **Multicat** plugin is designed for the **Page** module in CMF Cotonti Siena.  
It allows assigning a page to multiple categories, extending the default Cotonti system, where a page can belong only to a single category.  
This is especially useful for sites with complex content structures, where an article can be relevant to several topics.

- **Key Features**:
  - Category relations are stored in a separate table `cot_page_multicats` using `structure_id` for identification.
  - Automatic migration of existing categories during installation.
  - Integration with add/edit page forms via checkboxes.
  - Display of multiple categories in the admin panel and page lists.
  - Category-based filtering in `page.list` queries that takes multicategories into account.

This plugin is built for Cotonti Siena v0.9.26+, requires PHP 8.4+ and MySQL 8.0+.  
No external libraries are needed — it uses Cotonti's native hooks, structure, and SQL system.

- **Author**: webitproff  
- **Release Date**: 2025-09-14  
- **Version**: 1.1.0  
- **License**: BSD  
- **Repository**: [https://github.com/webitproff/cot-multicat](https://github.com/webitproff/cot-multicat)  
- **Dependencies**: Requires the `page` module. No other modules are required.

## How It Works

The plugin uses Cotonti hooks to extend the Page module functionality without changing the core.  

1. **Data Storage**:
   - Uses the table `cot_page_multicats` to store relations between pages (`pcat_page_id`) and categories (`pcat_cat_id`), where `pcat_cat_id` is the `structure_id` from `cot_structure`.
   - Prevents data duplication and stays compatible with the default Page module.
   - Existing categories from `cot_pages.page_cat` are automatically migrated to `structure_id` values during installation.

2. **Category Handling**:
   - Functions in `inc/multicat.functions.php` manage retrieving, saving, and displaying categories.
   - Categories are selected via checkboxes in add/edit forms.
   - The first selected category becomes the main one (`page_cat` in `cot_pages`), others are stored as multicategories.
   - Page list filtering (`page.list`) considers both main and additional categories using SQL subqueries.

3. **UI Integration**:
   - New template tags: `{PAGEFORM_CAT}` (checkboxes) and `{PAGEFORM_CAT_HINT}` (hint text).
   - In the admin panel, a list of all categories is shown per page row.
   - Language file supports English, with possibility to add more.

4. **Security & Performance**:
   - All queries use safe parameters to prevent SQL injections.
   - Category filtering is optimized with indexes.
   - `cot_auth` permissions are checked when displaying category options.

For large sites, indexing `cot_page_multicats` is recommended for performance.

## Integration

The plugin integrates entirely through Cotonti hooks. No core file modifications needed.

- **Hooks used**:
  - `global`: define table, load language.
  - `page.add.add.done`: save categories after adding a page.
  - `page.admin.loop`: show categories in admin list.
  - `page.delete.first`: delete relations before deleting a page.
  - `page.edit.update.import`: import & validate categories on edit.
  - `page.edit.tags`: generate checkbox form in editor.
  - `page.edit.update.done`: save categories after editing.
  - `page.list.query`: extend SQL filter by category.

- **Template changes**:
  - Add `{PAGEFORM_CAT}` and `{PAGEFORM_CAT_HINT}` to `page.edit.tpl` and `page.add.tpl` (after standard category field).
  - In `page.admin.tpl`, add `{ADMIN_PAGE_MULTICATS}` inside the `PAGE_ROW` block to display extra categories.

- **Compatibility**:
  - Works with the Page module directly.
  - If you use other SEO/category plugins, test for compatibility.

## Installation & Usage

### Installation

1. **Download plugin**:
   - Clone repository: `git clone https://github.com/webitproff/cot-multicat.git`  
   - Or download ZIP and unpack into `plugins/`

2. **Install via Cotonti Admin Panel**:
   - Go to Admin → Extensions → Install → choose `multicat`
   - Installer will create `cot_page_multicats` table and migrate categories

3. **Configure**:
   - In plugin settings, enable `enabled` option (default is enabled).

### Usage

1. **Add/Edit a page**:
   - Choose multiple categories using checkboxes.
   - At least one category is required.
   - Save — categories are stored in DB.

2. **View in admin**:
   - The admin page list shows all categories in a row.
   - On the frontend, category filters include multicategories.

3. **Delete a page**:
   - Relations in `cot_page_multicats` are automatically removed.

4. **Update/Remove plugin**:
   - Backup DB before removal.
   - Removing plugin does not delete `cot_page_multicats`. Drop manually if needed.

### Common Issues

- **Error: "must select at least one category"** → check that at least one checkbox is ticked.  
- **Categories not showing** → check `cot_auth` rights and `structure_id` values.  
- **Template conflicts** → ensure `{PAGEFORM_CAT}` and `{PAGEFORM_CAT_HINT}` tags are added.  
- **Migration failed** → manually check SQL in `setup/multicat.install.sql`.

___

# Плагин Multicat для Cotonti Siena

![Cotonti Siena](https://img.shields.io/badge/Cotonti-Siena%20v0.9.26-blue) ![PHP](https://img.shields.io/badge/PHP-8.4%2B-green) ![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-yellow) ![License](https://img.shields.io/badge/License-BSD-red) ![Version](https://img.shields.io/badge/Version-1.1.0-orange)

## Описание плагина

Плагин **Multicat** предназначен для модуля **Page** в CMF Cotonti Siena. Он позволяет назначать страницы сразу в несколько категорий, расширяя стандартные возможности системы, где страница может принадлежать только одной категории. Это полезно для сайтов с сложной структурой контента, где материалы могут относиться к нескольким темам одновременно.

- **Ключевые особенности**:
  - Хранение связей категорий в отдельной таблице `cot_page_multicats` с использованием `structure_id` для идентификации категорий.
  - Автоматическая миграция существующих категорий при установке.
  - Интеграция с формами добавления/редактирования страниц через чекбоксы.
  - Отображение множественных категорий в админ-панели и списках страниц.
  - Поддержка фильтрации страниц по категориям в списках, учитывая мультикатегории.

Плагин разработан для Cotonti Siena версии 0.9.26 и выше, с поддержкой PHP 8.4+ и MySQL 8.0+. Он не требует дополнительных библиотек и использует встроенные механизмы Cotonti (хуки, структура, SQL-запросы).

- **Автор**: webitproff
- **Дата выпуска**: 2025-09-14
- **Версия**: 1.1.0
- **Лицензия**: BSD
- **Репозиторий**: [https://github.com/webitproff/cot-multicat](https://github.com/webitproff/cot-multicat)
- **Зависимости**: Модуль `page` (требуется), другие модули не обязательны.

## Принципы работы

Плагин работает на основе хуков Cotonti, расширяя функциональность модуля Page без изменения кода системы. Основные принципы:

1. **Хранение данных**:
   - Используется таблица `cot_page_multicats` для хранения связей между страницами (`pcat_page_id`) и категориями (`pcat_cat_id`), где `pcat_cat_id` — это `structure_id` из таблицы `cot_structure`.
   - Это позволяет избежать дублирования данных и сохранять совместимость со стандартным модулем Page.
   - При установке автоматически мигрируются существующие категории из `cot_pages` (поле `page_cat` преобразуется в `structure_id`).

2. **Обработка категорий**:
   - Функции в `inc/multicat.functions.php` позволяют получать, сохранять и отображать категории.
   - Категории выбираются через чекбоксы в формах добавления/редактирования страниц.
   - Первая выбранная категория устанавливается как основная (`page_cat` в `cot_pages`), остальные — в мультикатегориях.
   - В списках страниц (page.list) фильтр по категории учитывает как основную, так и дополнительные категории через подзапросы SQL.

3. **Интеграция с UI**:
   - Добавляются теги в шаблоны: `{PAGEFORM_CAT}` для чекбоксов и `{PAGEFORM_CAT_HINT}` для подсказки.
   - В админ-панели отображается список категорий для каждой страницы.
   - Языковой файл поддерживает русский язык, с возможностью добавления других.

4. **Безопасность и производительность**:
   - Все запросы используют подготовленные параметры для защиты от SQL-инъекций.
   - Фильтрация по категориям оптимизирована с использованием индексов таблицы.
   - Проверка прав доступа (`cot_auth`) при отображении категорий в формах.

Плагин не влияет на производительность для сайтов с небольшим количеством страниц, но для крупных сайтов рекомендуется индексация таблицы `cot_page_multicats`.

## Интеграция

Плагин интегрируется через стандартные хуки Cotonti. Нет необходимости модифицировать核心 файлы — все изменения происходят через плагинные файлы.

- **Хуки, используемые в плагине**:
  - `global`: Определение таблицы и загрузка языка.
  - `page.add.add.done`: Сохранение категорий после добавления страницы.
  - `page.admin.loop`: Отображение категорий в админ-списке.
  - `page.delete.first`: Удаление связей перед удалением страницы.
  - `page.edit.update.import`: Импорт и валидация категорий при редактировании.
  - `page.edit.tags`: Генерация формы чекбоксов в редакторе.
  - `page.edit.update.done`: Сохранение категорий после обновления.
  - `page.list.query`: Фильтрация списка страниц по категории.

- **Изменения в шаблонах**:
  - В файлы `page.edit.tpl` и `page.add.tpl` добавьте `{PAGEFORM_CAT}` и `{PAGEFORM_CAT_HINT}` сразу после стандартного поля категории.
  - чтобы в page.admin.tpl в каждой строке (блок PAGE_ROW) выводился список мультикатегорий для текущей страницы, нужно добавить {ADMIN_PAGE_MULTICATS}, например после {ADMIN_PAGE_LOCAL_STATUS}.

- **Совместимость**:
- Работает с модулем Page без конфликтов.
- Если используются другие плагины на категории (например, для SEO), протестируйте на совместимость.

## Инструкции по установке и использованию

### Установка

1. **Скачайте плагин**:
- Клонируйте репозиторий: `git clone https://github.com/webitproff/cot-multicat.git` или скачайте ZIP.

2. **Установка через админ-панель Cotonti**:
- Скопируйте папку `multicat` в `plugins/` вашего сайта.
- В админ-панели перейдите в "Расширения" > "Установка" и установите плагин `multicat`.
- При установке автоматически создастся таблица `cot_page_multicats` и мигрируются существующие категории.

3. **Настройка**:
- В конфигурации плагина (`Админ > Расширения > Multicat > Конфигурация`) включите опцию `enabled` (по умолчанию включена).

### Использование

1. **Добавление/редактирование страницы**:
- В форме страницы выберите несколько категорий через чекбоксы.
- Обязательно выберите хотя бы одну — иначе ошибка.
- Сохраните: категории сохранятся в БД.

2. **Просмотр**:
- В админ-списке страниц увидите все категории через запятую.
- В списках страниц (на сайте) фильтр по категории покажет страницы из мультикатегорий.

3. **Удаление**:
- При удалении страницы связи автоматически удаляются.

4. **Обновление/удаление плагина**:
- Перед удалением: Сделайте бэкап БД.
- Удалите через админ-панель — таблица не удаляется автоматически (удалите вручную, если нужно).

### Возможные проблемы и решения

- **Ошибка "необходимо выбрать хотя бы одну категорию"**: Убедитесь, что выбрана категория в форме.
- **Категории не отображаются**: Проверьте права доступа (`cot_auth`) и наличие `structure_id` в БД.
- **Конфликты с шаблонами**: Убедитесь, что добавлены теги `{PAGEFORM_CAT}` и `{PAGEFORM_CAT_HINT}`.
- **Миграция не сработала**: Проверьте SQL-запрос в `setup/multicat.install.sql` и выполните вручную.



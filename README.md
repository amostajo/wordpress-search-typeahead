# Wordpress Search Typeahead ADD-ON

[![Latest Stable Version](https://poser.pugx.org/amostajo/wordpress-search-typeahead/v/stable)](https://packagist.org/packages/amostajo/wordpress-search-typeahead)
[![Total Downloads](https://poser.pugx.org/amostajo/wordpress-search-typeahead/downloads)](https://packagist.org/packages/amostajo/wordpress-search-typeahead)
[![License](https://poser.pugx.org/amostajo/wordpress-search-typeahead/license)](https://packagist.org/packages/amostajo/wordpress-search-typeahead)

Add-on package for [Wordpress Plugin Template](https://github.com/amostajo/wordpress-plugin) and [Wordpress Theme Template](https://github.com/amostajo/wordpress-theme) exclusively.

**Typeahead** integrates Twitter's typeahead with wordpress, providing a flexible way to add customization and functionality for post searches.

- [Installation](#installation)
    - [Configure in Template](#configure-in-template)
- [Usage](#usage)
    - [On Templates](#on-templates)
        - [Changing filters](#changing-filters)
    - [Hooks](#hooks)
    - [Customization](#customization)
- [Coding Guidelines](#coding-guidelines)
- [Copyright](#copyright)

## Installation

This package requires [Composer](https://getcomposer.org/).

Add it in your `composer.json` file located on your template's root folder:

```json
"amostajo/wordpress-search-typeahead": "1.0.*"
```

Then run

```bash
composer install
```

or

```bash
composer update
```

to download package and dependencies.

### Configure in Template

Add the following string line in your `addons` array option located at your template's config file.

```php
    'Amostajo\Wordpress\TypeaheadAddon\Typeahead',
```

This should be added to:
* `config\plugin.php` on Wordpress Plugin Template
* `config\theme.php` on Wordpress Theme Template

## Usage

### On Templates

Call `typeahead_search()` function within your template, like this:

```html
<div class"search">
    <?php typeahead_search() ?>
</div>
```

#### Changing filters

Typeahead uses *WP_Query* to retrieve posts, you can modify the results of the search like this:

```html
<div class"search">
    <?php typeahead_search( [ 'post_type' => 'product' ] ) ?>
</div>
```

### Hooks

**FILTER**: `addon_typeahead_query`
Filters the arguments passed to *WP_Query* by default.

```php
add_filter( 'addon_typeahead_query', 'filter_query' );

function filter_query ($args) {
    // Array modifications
    $args['post_type'] = 'product';
    $args['posts_per_page'] = 10;

    // Array is expected in return
    return $args;
}
```

**FILTER**: `addon_typeahead_post`
Filters each *WP_Post* object returned by *WP_Query*.
If this is not modified, the add-on will convert the post result into a Post model (located in this package).

```php
add_filter( 'addon_typeahead_post', 'filter_post' );

function filter_post ($post) {

    // Transformation of post into custom model
    $model = new MyModels\CustomPost();

    // Array is expected in return
    return $model->from_post( $post )->to_array();
}

**FILTER**: `addon_typeahead_data`
Filters final results. Receives the data as array and the search arguments (those used for WP_Query).

```php
add_filter( 'addon_typeahead_data', 'filter_data' );

function filter_data ($data, $args) {

    // Adding custom records in results
    if ( $args['post_type'] != 'product' ) {
        // Adding extra product
        $data[] = MyProduct::find(999)->to_array();
    }

    // Array is expected in return
    return $data;
}

### Customization
All views located at the `views` folder can be customized in your theme. Copy and paste them in your theme (same as you would do for plugin views) and modify them.

Things to consider:

```html
<input type="text"
    id="search-typeahead"
    name="s"
    placeholder="Search..."
    class="default"
    data-hint="1"
    data-highlight="1"
    data-source="<?php echo $source_url ?>"
    data-template-empty="<?php echo $template_empty ?>"
    data-template-suggestion="<?php echo $template_suggestion ?>"
    data-onselect="1"
/>
```

`data-onselect` will make results to redirect to post permalink once selected.

Try to keep the other `data` attributes, modify them if needed.

## Coding Guidelines

The coding is a mix between PSR-2 and Wordpress PHP guidelines.

## License

**Search Typeahead** is free software distributed under the terms of the MIT license.

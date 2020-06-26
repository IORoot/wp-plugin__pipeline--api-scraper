# Wordpress Generic API Scraper.

[![Build Status](https://travis-ci.org/IORoot/wp-plugin__api-scraper.svg)](https://travis-ci.org/IORoot/wp-plugin__api-scraper)

The API Scraper Wordpress plugin will allow you to get data from various API endpoints and output the results as posts into your custom post types / taxonomies. 

Badge not working correctly?

## Dependencies
 
- ACF (Advanced Custom Fields)

## Wiki

For more details, please read the wiki.

## Test Data

Located in the `/files/acf_export_dashboard` directory there is a JSON export of the ACF options page. Use this to change the options page as you see fit.

Located in the `/files/demo_dashboard` directory is an `options.json` file that can be used to import a load of demo data for all tabs (except the auth tab).

## Composer

All classes are autoloaded through composer. Classes are namespaced at `\yt`. 

If you add new classes within the `\src` directory, make sure you do a `composer dumnpautoload` to include it.

## Interfaces

There are five manual interfaces that allow you to create new items.

### 1. Filters

The `\src\interfaces\filterInterface.php` file describes how new filters are created. These are located in the `\src\filter\filters`.

### 2. Housekeeps

The `\src\interfaces\housekeepInterface.php` file describes how new housekeep actions are created. These are located in `\src\housekeeping\action`. 

### 3. Requests

The `\src\interfaces\requestInterface.php` file describes how new API requests are created. These are located in `\src\api\requests`. 

### 4. Tokens

The `\src\interfaces\tokenInterface.php` file describes how new API search request tokens are created. These are located in `\src\api\tokens`. 

### 5. Transforms

The `\src\interfaces\transformInterface.php` file describes how new transforms are created. These are located in `\src\transform\transforms`. 

## Error Classes

There are two error / reporting classes.

1. `\yt\e` class. This will output to the debug window.
2. `\yt\r` class. This will output to the individual reporting windows.


# ACF

This plugin relys on Advanced Custom Forms Pro. 

Everything to do with the ACF integration is held in this directory.

## Breakdown

`acf_init.php` will load all of the ACF components.

`dashboard.php` is the interface of the scraper.

`get_option` is a class to get a single repeater from the options page.

`get_options` is the main class for getting all options from the interface.

`import_export_page.php` is the importer/exporter.

`include_acf.php` will include the ACF plugin if you do not already have it. ACF is located in `/vendor/advanced-custom-fields` directory.

`on_update.php` This is the trigger for the scrapper. It will run the `scraper` class when the update button is pressed on the interface.

`options_page.php` will create an options page for the interface.

`populate_yt_???.php` All of the populate scripts are used to auto-populate `select` dropdowns in the interface.

`style_admin.php` Will customise the admin interface with CSS.
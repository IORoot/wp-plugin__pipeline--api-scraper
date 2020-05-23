# Filters

Filters will allow you to 'filter' the results of the scrape to your specifications.

## Filter Groups

Each filter group contains a number of filters that will cycle through. This means you can add as many filters to the group as you like to make the filter more precise.

## Filter layer

These will do the actual filtering. Each filter is a separate instance of the `filterInterface` and is located in the `/filters` directory. 

## Filters

The `/filters` directory holds each filter type. These each take an input and configuration data and return an output. 

You can use the filters to reformat the data however you see fit. For instance, the `ig_account_convert.php` file will alter the input instagram JSON format and make it usable for the mapper and importer.
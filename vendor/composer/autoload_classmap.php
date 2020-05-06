<?php

// autoload_classmap.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'yt\\api' => $baseDir . '/src/api/api.php',
    'yt\\e' => $baseDir . '/src/error/e.php',
    'yt\\filter' => $baseDir . '/src/filter/filter.php',
    'yt\\filter\\none' => $baseDir . '/src/filter/filters/none.php',
    'yt\\filter\\remove_item_if_regex' => $baseDir . '/src/filter/filters/remove_item_if_regex.php',
    'yt\\filter\\yt_array_front' => $baseDir . '/src/filter/filters/yt_array_front.php',
    'yt\\filter_group' => $baseDir . '/src/filter/filter_group.php',
    'yt\\filter_layer' => $baseDir . '/src/filter/filter_layer.php',
    'yt\\filter_list' => $baseDir . '/src/filter/filter_list.php',
    'yt\\housekeep' => $baseDir . '/src/housekeeping/housekeep.php',
    'yt\\housekeep\\bin_all' => $baseDir . '/src/housekeeping/action/bin_all.php',
    'yt\\housekeep\\bin_posts' => $baseDir . '/src/housekeeping/action/bin_posts.php',
    'yt\\housekeep\\delete_all' => $baseDir . '/src/housekeeping/action/delete_all.php',
    'yt\\housekeep\\delete_posts' => $baseDir . '/src/housekeeping/action/delete_posts.php',
    'yt\\housekeep\\none' => $baseDir . '/src/housekeeping/action/none.php',
    'yt\\import' => $baseDir . '/src/import/import.php',
    'yt\\import\\attach' => $baseDir . '/src/import/attach.php',
    'yt\\import\\downloader' => $baseDir . '/src/import/downloader.php',
    'yt\\import\\exists' => $baseDir . '/src/import/exists.php',
    'yt\\import\\image' => $baseDir . '/src/import/create_image.php',
    'yt\\import\\meta' => $baseDir . '/src/import/create_meta.php',
    'yt\\import\\post' => $baseDir . '/src/import/create_post.php',
    'yt\\import\\taxonomy' => $baseDir . '/src/import/create_taxonomy.php',
    'yt\\interfaces\\filterInterface' => $baseDir . '/src/interfaces/filterInterface.php',
    'yt\\interfaces\\housekeepInterface' => $baseDir . '/src/interfaces/housekeepInterface.php',
    'yt\\interfaces\\requestInterface' => $baseDir . '/src/interfaces/requestInterface.php',
    'yt\\interfaces\\tokenInterface' => $baseDir . '/src/interfaces/tokenInterface.php',
    'yt\\interfaces\\transformInterface' => $baseDir . '/src/interfaces/transformInterface.php',
    'yt\\mapper_collection' => $baseDir . '/src/mapper/mapper_collection.php',
    'yt\\mapper_item' => $baseDir . '/src/mapper/mapper_item.php',
    'yt\\option' => $baseDir . '/src/acf/get_option.php',
    'yt\\options' => $baseDir . '/src/acf/get_options.php',
    'yt\\quota' => $baseDir . '/src/quota/quota.php',
    'yt\\r' => $baseDir . '/src/error/r.php',
    'yt\\request\\multichannel' => $baseDir . '/src/api/requests/multichannel.php',
    'yt\\request\\playlistitems' => $baseDir . '/src/api/requests/playlistitems.php',
    'yt\\request\\playlists' => $baseDir . '/src/api/requests/playlists.php',
    'yt\\request\\search' => $baseDir . '/src/api/requests/search.php',
    'yt\\response' => $baseDir . '/src/api/responses/responses.php',
    'yt\\scheduler' => $baseDir . '/src/scheduler/scheduler.php',
    'yt\\scraper' => $baseDir . '/src/scraper.php',
    'yt\\token\\date' => $baseDir . '/src/api/tokens/date.php',
    'yt\\transform\\best_image' => $baseDir . '/src/transform/transforms/best_image.php',
    'yt\\transform\\field_as_string' => $baseDir . '/src/transform/transforms/field_as_string.php',
    'yt\\transform\\none' => $baseDir . '/src/transform/transforms/none.php',
    'yt\\transform\\regex_remove' => $baseDir . '/src/transform/transforms/regex_remove.php',
    'yt\\transform\\string_remove' => $baseDir . '/src/transform/transforms/string_remove.php',
    'yt\\transform\\string_trim' => $baseDir . '/src/transform/transforms/string_trim.php',
    'yt\\transform_group' => $baseDir . '/src/transform/transform_group.php',
    'yt\\transform_layer' => $baseDir . '/src/transform/transform_layer.php',
    'yt\\transform_list' => $baseDir . '/src/transform/transform_list.php',
    'yt_import_export' => $baseDir . '/src/acf/import_export_page.php',
);

<?php

// autoload_classmap.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'yt\\api' => $baseDir . '/src/api/youtube.php',
    'yt\\filter\\none' => $baseDir . '/src/filter/filters/none.php',
    'yt\\filter\\remove_item_if_regex' => $baseDir . '/src/filter/filters/remove_item_if_regex.php',
    'yt\\filter\\string_remove' => $baseDir . '/src/filter/filters/string_remove.php',
    'yt\\filter\\yt_array_front' => $baseDir . '/src/filter/filters/yt_array_front.php',
    'yt\\filter_group' => $baseDir . '/src/filter/filter_group.php',
    'yt\\filter_layer' => $baseDir . '/src/filter/filter_layer.php',
    'yt\\filter_list' => $baseDir . '/src/filter/filter_list.php',
    'yt\\import' => $baseDir . '/src/import/import.php',
    'yt\\import\\category' => $baseDir . '/src/import/create_category.php',
    'yt\\import\\post' => $baseDir . '/src/import/create_post.php',
    'yt\\interfaces\\filterInterface' => $baseDir . '/src/interfaces/filterInterface.php',
    'yt\\mapper' => $baseDir . '/src/mapper/mapper.php',
    'yt\\options' => $baseDir . '/src/acf/get_options.php',
    'yt\\scraper' => $baseDir . '/src/scraper.php',
);

<?php

function my_acf_admin_head() {
    ?>
    <style type="text/css">
        <?php include 'css/admin.css'; ?>
    </style>
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.8.55/css/materialdesignicons.min.css" rel="stylesheet">
    <?php
}

add_action('acf/input/admin_head', 'my_acf_admin_head');
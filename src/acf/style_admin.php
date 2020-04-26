<?php

function my_acf_admin_head() {
    ?>
    <style type="text/css">
        [data-key="field_5ea487e0ea984"] { background-color: #D0C8B3 !important; color: #242424 !important;}
        [data-key="field_5e9fff797b74b"] { background-color: #D0C8B3 !important; color: #242424 !important;}
    </style>
    <?php
}

add_action('acf/input/admin_head', 'my_acf_admin_head');
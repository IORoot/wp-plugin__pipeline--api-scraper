<?php

function my_acf_admin_head() {
    ?>
    <style type="text/css">

        /* Transform tab button */
        [data-key="field_5ea487e0ea984"] { background-color: #D0C8B3 !important; color: #242424 !important;}
        /* filter tab button */
        [data-key="field_5e9fff797b74b"] { background-color: #D0C8B3 !important; color: #242424 !important;}
        /* debug tab button */
        [data-key="field_5ea6b0d0570f2"] { background-color: #E34F65 !important; color: #242424 !important;}

        /* debug textbox */
        #acf-field_5ea6b0e2570f3 {
            background-color: #424242;
            color: #38EF7D;
            white-space: nowrap;
            overflow: auto;
            font-family: monospace;
        }
    </style>
    <?php
}

add_action('acf/input/admin_head', 'my_acf_admin_head');
<?php

function my_acf_admin_head() {
    ?>
    <style type="text/css">

        /* scrape tab button */
        [data-key="field_5ea14187d975d"] { background-color: #27C9C1 !important; color: #242424 !important;}

        /* Transform tab button */
        [data-key="field_5ea487e0ea984"] { background-color: #D0C8B3 !important; color: #242424 !important;}
        
        /* debug tab button */
        [data-key="field_5ea6b0d0570f2"] { background-color: #E34F65 !important; color: #242424 !important;}

        /* debug textbox */
        /* Last Response Textareas */
        #acf-field_5eaa7abce8e7c-field_5ea6ef88f95e5,
        #acf-field_5eaa7976b69ac,
        #acf-field_5ea6ef88f95e5,
        #acf-field_5ea6effff95e6,
        #acf-field_5ea6f034f95e7,
        #acf-field_5ea6f062f95e8,
        #acf-field_5ea6b0e2570f3 {
            background-color: #424242;
            color: #38EF7D;
            white-space: nowrap;
            overflow: auto;
            font-family: monospace;
        }

        .acf-table>tbody>tr:nth-child(odd) td {background: #fafafa } 
        .acf-table>tbody>tr:nth-child(even) td {background: #F2F9FF } 
        
        .-collapsed .-collapsed-target {
            width: 100% !important;
        }
        .-collapsed .-collapsed-target input {
            text-align: center;
        }
    </style>
    <?php
}

add_action('acf/input/admin_head', 'my_acf_admin_head');
<?php

namespace yt\import;

class attach
{
    public function __construct()
    {
        return $this;
    }



    public function image_to_post($image_id = null, $post_id = null)
    {
        if ($image_id == null || $post_id == null) {
            return;
        }

        return set_post_thumbnail($post_id, $image_id);
    }




    public function meta_to_post($metadata, $post_id)
    {
        if ($metadata == null || $post_id == null) {
            return;
        }

        foreach($metadata as $meta_key => $meta_value)
        {
            update_post_meta($post_id, $meta_key, $meta_value);
        }

        return;
    }


    public function tax_to_post($tax_type, $tax_term, $post_id)
    {
        if (isset($tax_term) && isset($tax_type) && isset($post_id)) {
            return wp_set_object_terms($post_id, $tax_term, $tax_type, true);
        }
        
        return;
    }

}

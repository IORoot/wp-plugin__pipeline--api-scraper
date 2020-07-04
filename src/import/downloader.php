<?php

namespace yt\import;

class downloader 
{

    public $url;
    public $post_data;
    public $alttext;
    public $filename;

    public $tmp;
    public $url_type;
    public $url_filename;
    public $file_array;
    public $att_id;



    public function download($url = null, $post_data = array(), $alttext, $filename = null )
    {
        if ( !$url ) return new \WP_Error('missing', "Need a valid URL to download image.");

        $this->url = $url;
        $this->post_data = $post_data;
        $this->alttext = $alttext;
        $this->filename = $filename;

        // Does the image exist already?
        if ($image_id = $this->does_image_exist($filename)) { 
            return $image_id;  
        }

        // Check if this is a LOCAL file here. (regex for leading slash on url / )
        if (preg_match('/^\//', $url) == 1)
        {
            return $this->download_local();
        }

        // download a remote file.
        return $this->download_remote($url, $post_data, $alttext, $filename );
        
    }


    public function download_local()
    {
        //$tmp      = $this->download_url();
        $this->tmp        = $this->url;
        $this->check_filetype();
        $this->rename_output_filename();
        $this->build_file_array_for_sideload();
        $this->unique_image_title();
        $this->load_image_into_wp();
        $this->update_image_meta();
        $this->delete_image_if_error();

        return $this->att_id;
    }


    public function download_url()
    {
        // Download the file to temporary storage
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        $this->tmp = download_url( $this->url );
        return;
    }


    public function check_filetype()
    {
        preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $this->url, $matches);    // fix file filename for query strings
        $this->url_filename = basename($matches[0]);                                                  // extract filename from url for title
        $this->url_type = wp_check_filetype($this->url_filename);                                           // determine file type (ext and mime/type)
        return;
    }



    public function rename_output_filename()
    {
        // override filename if given, reconstruct server path
        if ( !empty( $this->filename ) ) {
            $this->filename = sanitize_file_name($this->filename);
            $tmppath = pathinfo( $this->tmp );                                                        // extract path parts
            $new = $tmppath['dirname'] . "/". $this->filename . "." . $tmppath['extension'];          // build new path
            rename($this->tmp, $new);                                                                 // renames temp file on server
            $this->tmp = $new;                                                                     
        }
        
        return;
    }


    public function build_file_array_for_sideload()
    {
        // assemble file data (should be built like $_FILES since wp_handle_sideload() will be using)
        // $filename: array(2)
        //      tmp_name: "/tmp/filename.tmp"
        //      name: "newname.jpg"
        $this->file_array['tmp_name'] = $this->tmp;                                                         // full server path to temp file

        if ( !empty( $this->filename ) ) {
            $this->file_array['name'] = $this->filename . "." . $this->url_type['ext'];                     // user given filename for title, add original URL extension
        } else {
            $this->file_array['name'] = $this->url_filename;                                                // just use original URL filename
        }

        return;
    }


    public function unique_image_title()
    {
        // set additional wp_posts columns
        if ( empty( $this->post_data['post_title'] ) ) {
            $this->post_data['post_title'] = basename($this->url_filename, "." . $this->url_type['ext']);         // just use the original filename (no extension)
        }

        // To distinguish between a post and it's image,
        // add on the word 'image' to the title at the end.
        // otherwise when we're checking for an existing
        // post, it'll see the image as a post with the same
        // title and say it exists!
        // By adding the word 'title' it'll be different
        // to the post title slightly.
        $this->post_data['post_title'] = $this->post_data['post_title'] . ' image';

        return;
    }


    public function load_image_into_wp()
    {
        // required libraries for media_handle_sideload
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        // do the validation and storage stuff
        // $post_data can override the items saved to wp_posts table, like post_mime_type, guid, post_parent, post_title, post_content, post_status
        //
        // Note - $post_id is NULL. This is because we'll attach the image to the post with the
        // 'attach' class instead.
        $this->att_id = media_handle_sideload( $this->file_array, null, null, $this->post_data ); 

        return;
    }


    public function update_image_meta()
    {
        // Set the image Alt-Text
        update_post_meta( $this->att_id, '_wp_attachment_image_alt', $this->alttext );

        return;
    }


    public function delete_image_if_error()
    {
        // If error storing permanently, unlink
        if ( is_wp_error($this->att_id) ) {
            @unlink($this->file_array['tmp_name']);   // clean up
            (new \yt\e)->line('There was an error with the image file. : ' . $this->att_id, 2 );
        }

        return;
    }






    public function download_remote($url = null, $post_data = array(), $alttext, $filename = null )
    {
        // Download the file to temporary storage
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        $tmp = download_url( $url );

        // If error storing temporarily, unlink
        if ( is_wp_error( $tmp ) ) {
            @unlink($file_array['tmp_name']);   // clean up
            $file_array['tmp_name'] = '';
            return $tmp; // output wp_error
        }

        preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $url, $matches);    // fix file filename for query strings
        $url_filename = basename($matches[0]);                                                  // extract filename from url for title
        $url_type = wp_check_filetype($url_filename);                                           // determine file type (ext and mime/type)

        // override filename if given, reconstruct server path
        if ( !empty( $filename ) ) {
            $filename = sanitize_file_name($filename);
            $tmppath = pathinfo( $tmp );                                                        // extract path parts
            $new = $tmppath['dirname'] . "/". $filename . "." . $tmppath['extension'];          // build new path
            rename($tmp, $new);                                                                 // renames temp file on server
            $tmp = $new;                                                                        // push new filename (in path) to be used in file array later
        }

        // assemble file data (should be built like $_FILES since wp_handle_sideload() will be using)
        // $filename: array(2)
        //      tmp_name: "/tmp/filename.tmp"
        //      name: "newname.jpg"
        $file_array['tmp_name'] = $tmp;                                                         // full server path to temp file
        if ( !empty( $filename ) ) {
            $file_array['name'] = $filename . "." . $url_type['ext'];                           // user given filename for title, add original URL extension
        } else {
            $file_array['name'] = $url_filename;                                                // just use original URL filename
        }

        // set additional wp_posts columns
        if ( empty( $post_data['post_title'] ) ) {
            $post_data['post_title'] = basename($url_filename, "." . $url_type['ext']);         // just use the original filename (no extension)
        }
        // To distinguish between a post and it's image,
        // add on the word 'image' to the title at the end.
        // otherwise when we're checking for an existing
        // post, it'll see the image as a post with the same
        // title and say it exists!
        // By adding the word 'title' it'll be different
        // to the post title slightly.
        $post_data['post_title'] = $post_data['post_title'] . ' image';


        // required libraries for media_handle_sideload
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');


        // do the validation and storage stuff
        // $post_data can override the items saved to wp_posts table, like post_mime_type, guid, post_parent, post_title, post_content, post_status
        //
        // Note - $post_id is NULL. This is because we'll attach the image to the post with the
        // 'attach' class instead.
        $att_id = media_handle_sideload( $file_array, null, null, $post_data );             

        // Set the image Alt-Text
        update_post_meta( $att_id, '_wp_attachment_image_alt', $alttext );

        // If error storing permanently, unlink
        if ( is_wp_error($att_id) ) {
            @unlink($file_array['tmp_name']);   // clean up
            return $att_id; // output wp_error
        }

        return $att_id;
    }



    public function does_image_exist($filename)
    {
        global $wpdb;
        return intval( 
            $wpdb->get_var( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_value LIKE '%/$filename.%'" ) 
        );
    }

}
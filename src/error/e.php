<?php

namespace yt;

// errors
class e
{

    public $target_textarea = 'yt_debug';

    public $current_message;

    public $new_message;
    
    public function __construct()
    {
        return $this;
    }

    public function set_textarea($target_textarea){
        $this->target_textarea = $target_textarea;
    }


    public function clear()
    {
        update_field( $this->target_textarea, '', 'options' );
    }

    public function clearline($message)
    {
        update_field( $this->target_textarea, $message, 'options' );
    }



    public function line($new_message,$tabs=0)
    {
        $this->new_message = $new_message;

        $this->read_current_message();
        $this->nl();
        $this->t($tabs);
        $this->clearline($this->current_message . $this->new_message);
    }


    public function read_current_message()
    {
        $this->current_message = get_field( $this->target_textarea, 'options' );
    }



    /**
     * nl
     * 
     * Adds a newline onto the end of the message.
     *
     * @return void
     */
    public function nl()
    {
        $this->current_message = $this->current_message . '
';
    }
    

    /**
     * t
     * 
     * Adds tabs.
     *
     * @return void
     */
    public function t($tabcount)
    {
        $top_level = '';
        if ($tabcount==0){
            $top_level = 
'------------------------------------------------------------------------------------------------------------------------
';
        }
        $tabs = str_repeat('    ',$tabcount);
        $this->new_message =  $top_level . $tabs . $this->new_message;
        return;
    }


}
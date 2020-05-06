<?php 

namespace yt;

use yt\option;

class housekeep {

    public $options;

    public $_key;

    public $result;

    public function __construct()
    {
        $this->get_options();
        $this->run();

        return $this;
    }

    public function get_options()
    {
        $op = new option;
        $op->get_all('yt_housekeep_instance');
        $this->options = $op->returned;
    }


    public function run()
    {
        foreach ($this->options as $this->_key => $hk_instance)
        {
            $this->instantiate_instance($hk_instance);
        }
    }

    public function instantiate_instance($hk_instance)
    {
        $instance_type = '\\yt\\housekeep\\'.$hk_instance['yt_housekeep_action'];
        $housekeep = new $instance_type;
        $housekeep->wp_query($hk_instance['yt_housekeep_query']);

        if ($hk_instance['yt_housekeep_enabled']) {
            $housekeep->run();
        }

        $this->result[$instance_type] = $housekeep->result();
    }

    
}
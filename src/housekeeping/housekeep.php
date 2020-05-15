<?php 

namespace yt;

use yt\option;

class housekeep {

    public $options;

    public $_key;

    public $result;

    public $when;

    public function __construct($when = 'after')
    {
        $this->when = $when;
        (new \yt\r)->clear('housekeep');
        $this->get_options();
        $this->run();

        return $this;
    }

    public function get_options()
    {
        $op = new option;
        $op->get_all('yt_housekeep_group_yt_housekeep_instance');
        $this->options = $op->returned;
    }


    public function run()
    {
        foreach ($this->options as $this->_key => $hk_instance)
        {
            if ($hk_instance['yt_housekeep_when'] != $this->when)
            {
                continue;
            }
            if (!$hk_instance['yt_housekeep_enabled']) {
                continue;
            }
            $this->instantiate_instance($hk_instance);
        }
    }

    public function instantiate_instance($hk_instance)
    {
        $instance_type = '\\yt\\housekeep\\'.$hk_instance['yt_housekeep_action'];
        $housekeep = new $instance_type;
        $housekeep->wp_query($hk_instance['yt_housekeep_query']);
        $housekeep->run();

        $this->result[$instance_type] = $housekeep->result();
    }

    
}
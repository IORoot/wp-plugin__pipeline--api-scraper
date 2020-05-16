<?php

namespace yt;

use yt\option;

class housekeep
{
    public $options;

    public $_key;

    public $result;

    public $when;

    public function __construct()
    {
        (new \yt\r)->clear('housekeep');
        return $this;
    }


    public function set_options($options)
    {
        $this->options = $options;
    }

    public function set_when($when)
    {
        $this->when = $when;
    }

    public function run()
    {
        if ($this->options == 'none') {
            return;
        }
        if (!isset($this->options)) {
            return;
        }
        if ($this->options['yt_housekeep_enabled'] == false) {
            return;
        }
        if (!isset($this->when)) {
            return;
        }
        if ($this->when != $this->options['yt_housekeep_when']) {
            return;
        }

        $this->instantiate_instance();
    }

    public function instantiate_instance()
    {
        $instance_type = '\\yt\\housekeep\\'.$this->options['yt_housekeep_action'];
        $housekeep = new $instance_type;
        $housekeep->wp_query($this->options['yt_housekeep_query']);
        $housekeep->run();
        $housekeep->result();
    }
}

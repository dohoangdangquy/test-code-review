<?php

namespace App\Model;

//Implement JsonSerializable for consistency with Task class
class Project
{
    /**
     * @var array
     */
    //Make it private and using getter, also change variable name from $_data -> $data
    public $_data;
    
    public function __construct($data)
    {
        $this->_data = $data;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return (int) $this->_data['id'];
    }

    /**
     * @return string
     */
    // change to jsonSerialize()
    public function toJson()
    {
        return json_encode($this->_data);
    }
}

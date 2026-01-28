<?php

namespace App\Model;

class Task implements \JsonSerializable
{
    /**
     * @var array
     */
    //Using getter, also change variable name from $_data -> $data
    private $_data;
    
    public function __construct($data)
    {
        $this->_data = $data;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->_data;
    }
}

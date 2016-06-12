<?php

class Api_countModel extends Model
{
    public function visit($key)
    {
        $result = $this->where(array('key' => $key))->find();
        if ($result) {
            $this->where(array('key' => $key))->setInc('count', 1);
        }
        else {
            $this->add(array('key' => $key, 'count' => 1));
        }
    }
}


<?php

class OrderPackageModel extends Model
{
    public function getPackages($where)
    {
        $packages = $this->where($where)->select();
        return $packages;
    }
}

?>

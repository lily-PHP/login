<?php
namespace App\Controllers;

class publicReturn{
    public function getReturn()
    {
        return [
            'code'      =>      'OK',
            'msg'       =>      'data is null',
            'item'      =>      null,
            'total'     =>      0,
            'success'   =>      false,
            'failed'    =>      false,
            'page'      =>      1
        ];
    }
}

?>
<?php
/*
 * Created by PhpStorm.
 * User: yezhangtao
 * Date: 2020/10/9
 * Time: 15:42
*/

namespace App\Http\Services;


use App\Model\Records;

class RecordsService
{
    public static function add($id, $data){
        return Records::addRecords($id, $data);
    }

    public static function find($id){
        return Records::findRecords($id);
    }
}

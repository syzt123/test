<?php
/*
 * Created by PhpStorm.
 * User: yezhangtao
 * Date: 2020/9/25
 * Time: 17:31
*/

namespace App\Http\Services;


use App\Model\Examination;

class ExaminationService
{

    public static function add($params)
    {
        return Examination::addExamination($params);
    }

    public static function find($params){
        return Examination::findRecord($params);
    }
}

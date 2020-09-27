<?php

namespace App\Imports;

use App\Http\Services\ExaminationService;
use App\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function model(array $row)
    {
        //var_dump($row);
        /*return new User([
            'name'     => $row[0],
            'email'    => $row[1],
            'password' => Hash::make($row[2]),
        ]);*/
        //dd($row);
        $type = '';
        if ($row[0] != '题号' && $row[1] != '题干'){
            /*if ($row[3] == '单选'){
                $type = 'radio';
            }
            if ($row[3] == '判断'){
                $type = 'jundment';
            }
            if ($row[3] == '多选'){
                $type = 'checkout';
            }*/



            $data = [
                'stem' => $row[0],
                'option' => $row[1],
                'answer' => $row[2],
                'type' => $row[3],
            ];
            //数据 处理 插入数据库
            ExaminationService::add($data);
        }else{
            //var_dump(3);
            return [];
        }

    }

    //批量导入1000条
    public function batchSize()
    {
        return 1000;
    }
    //以1000条数据基准切割数据
    public function chunkSize()
    {
        return 1000;
    }

}

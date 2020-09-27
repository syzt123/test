<?php
/*
 * Created by PhpStorm.
 * User: yezhangtao
 * Date: 2020/9/25
 * Time: 17:20
*/

namespace App\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Examination extends Model
{

    protected $table='examination';

    /**
     * 添加试题
     * Created by PhpStorm.
     * User: yezhangtao
     * Date: 2020/9/27
     * Time: 9:29
     * @param int
     * @param $params
     * @return int
     */
    public static function addExamination($params){
        $query = new self();
        $time = date('Y-m-d H:i:s');
        $data = [
            'stem' => $params['stem'],
            'option' => $params['stem'],
            'answer' => $params['answer'],
            'type' => $params['type'],
            'created_at' => $time,
            'updated_at' => $time,
        ];
        return $query->with([])->insertGetId($data);
    }

    /**
     * 随机获取10条数据
     * Created by PhpStorm.
     * User: yezhangtao
     * Date: 2020/9/27
     * Time: 9:52
     * @param Examination[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     * @param int $nums
     * @return Examination[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getRandomTenData($nums=10){
        $query = new self();
        $data = $query->with([])->inRandomOrder()->limit($nums)->get();
        if ($data->count()>0){
            return json_decode($data, true);
        }
        return [];
    }
}

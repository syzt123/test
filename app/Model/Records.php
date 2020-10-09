<?php
/*
 * Created by PhpStorm.
 * User: yezhangtao
 * Date: 2020/10/9
 * Time: 15:32
*/

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class Records extends Model
{

    protected $table = 'records';

    /**
     * 新增或更新
     * Created by PhpStorm.
     * User: yezhangtao
     * Date: 2020/10/9
     * Time: 15:41
     * @param bool
     * @param int $id
     * @param $data
     * @return bool
     */
    public static function addRecords($id=0, $data){
        $query = new self();
        if ($id){
            $model = $query->with([])->where(['test_id'=>$id])->first();
            if ($model){
                //更新
                return $query->where(['test_id'=>$id])->update($data);
            }else{
                //新增
                return $query->with([])->insert($data);
            }
        }
        return 0;
    }

    /**
     * 根据id查找信息
     * Created by PhpStorm.
     * User: yezhangtao
     * Date: 2020/10/9
     * Time: 15:40
     * @param mixed
     * @param int $id
     * @return mixed
     */
    public static function findRecords($id=0){
        $query = new self();
        return $query->where(['test_id'=>$id])->first();
    }
}

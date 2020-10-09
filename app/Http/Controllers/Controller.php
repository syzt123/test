<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 上传xlxs文件
     * Created by PhpStorm.
     * User: yezhangtao
     * Date: 2020/9/25
     * Time: 15:33
     * @param array
     * @return array
     */
    function uploadXls()
    {
        if (!empty ($_FILES['file']['name'])) {

            $tmp_file = $_FILES['file']['tmp_name'];//临时路径
            $file_types = explode(".", $_FILES ['file']['name']);
            $file_type = $file_types[count($file_types) - 1];
            //dd($file_type);
            /*判别是不是.xls文件，判别是不是excel文件*/
            if (strtolower($file_type) != "xls" && strtolower($file_type) != "xlsx") {
                //return false;
                return ['code'=>201, 'data'=>[], 'msg'=>'文件类型不是xls,xlsx'];
            }
            /*设置上传路径*/
            /**/
            /*$fpath=iconv('UTF-8','GBK',$name.".txt");
            $file=storage_path('param\\');
            if (!file_exists($file)){
                mkdir($file, 0777, true);
            }*/
            $savePath = storage_path('xlsx\\');
            if (!file_exists($savePath)){
                mkdir($savePath, 0777, true);
            }
            /*以时间来命名上传的文件*/
            //$str = date('Ymdhis');
            $file_name = $file_types[0].date('Ymdhis').'.'.$file_types[1];
            /*是否上传成功*/
            if (!copy($tmp_file, $savePath . $file_name)) {
                $this->error('上传失败');
            }
            return ['code'=>200, 'data'=>[
                'path'=>$savePath . $file_name
            ],'msg'=>'保存成功'];
        }
    }

    //返回json格式
    public function backjson ($msg  , $code = 0 , $data = []) {
        $arr = [
            'msg' => $msg,
            'code' => $code,
            'data' => $data,
        ];
        echo json_encode($arr , JSON_UNESCAPED_UNICODE);
    }
}

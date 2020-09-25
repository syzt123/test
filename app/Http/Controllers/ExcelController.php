<?php

namespace App\Http\Controllers;

use App\Exports\UserExport;
use App\Http\Requests;

use App\Imports\UsersImport;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use \Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller

{

    public function import(Request $request)
    {
        new \Maatwebsite\Excel\Excel();
        //dd(storage_path('excel\\'.'试题模板.xls'));
        Excel::import(new UsersImport(), storage_path('excel\\'.'试题模板.xlsx'));
        /*$filePath = storage_path('excel\\').iconv('UTF-8', 'GBK', '试题模板').'.xls';
        Excel::load($filePath, function($reader) {
            $data = $reader->all();
            dd($data);
        });*/
    }

    /**
     * 导出
     * Created by PhpStorm.
     * User: yezhangtao
     * Date: 2020/9/25
     * Time: 13:46
     * @param \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        //$path = storage_path('excel/'.'试题模板'.'.xlsx');//iconv('UTF-8', 'GBK', '试题模板')
        //dd($path);
        //return Excel::download(new UsersImport(), $path);

        //设置表头
        $row = [[
            "id"=>'题号',
            "nickname"=>'题干',
            "gender_text"=>'选项',
            "mobile"=>'答案',
            "addtime"=>'题型  '
        ]];

        //数据
        $list=[
        0=>[
        "id"=>'1',
        "nickname"=>'经过中国共产党第十九次全国代表大会部分修改后于    通过。',
        "gender_text"=>'A.2017年10月18日;B.2017年10月24日;C.2017年11月26日',
        "mobile"=>'B',
        "addtime"=>'单选'
    ],
            2=>[
        "id"=>'2',
        "nickname"=>'党章分为总纲和条文两部分。',
        "gender_text"=>'A.11,53;B.12,50;C.11,55',
        "mobile"=>'C',
        "addtime"=>'单选'
    ]
        ];

        //执行导出
        return Excel::download(new UserExport($row,$list), date('Y:m:d') . '用户列表.xlsx');

}

}

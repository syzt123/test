<?php

namespace App\Http\Controllers;

use App\Exports\UserExport;
use App\Http\Requests;

use App\Http\Services\ExaminationService;
use App\Http\Services\RecordsService;
use App\Imports\UsersImport;
use App\Model\Examination;
use App\Models\ExaminationRecord;
use App\Models\UseExamination;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use \Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller

{

    /*public function import(Request $request)
    {
        //new \Maatwebsite\Excel\Excel();
        //dd(storage_path('excel\\'.'试题模板.xls'));
        Excel::import(new UsersImport(), storage_path('excel\\'.'试题模板_BAK.xlsx'));
        $filePath = storage_path('excel\\').iconv('UTF-8', 'GBK', '试题模板').'.xls';
        Excel::load($filePath, function($reader) {
            $data = $reader->all();
            dd($data);
        });
    }*/

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

    /**
     * 文件上传
     * Created by PhpStorm.
     * User: yezhangtao
     * Date: 2020/9/25
     * Time: 15:32
     * @param void
     * @param Request $request
     */
    public function import(Request $request)
    {
        //new \Maatwebsite\Excel\Excel();
        //dd(storage_path('excel\\'.'试题模板.xls'));
        //dd(11);

        /*$back = $this->uploadXls();
        if ($back['code'] != 200){
            return $this->backjson($back['msg']);
        }*/
        $back['data']['path'] = storage_path('excel\\'.'试题模板.xlsx');
        //dd($back);
        //调用处理
        $rs = Excel::import(new UsersImport(), $back['data']['path']);//storage_path('excel\\'.'试题模板_BAK.xlsx')
        if ($rs){
            return $this->backjson('批量导入成功', 200);
        }
        return $this->backjson('批量导入失败');
        /*if ($rs == false){

        }*/

        /*$filePath = storage_path('excel\\').iconv('UTF-8', 'GBK', '试题模板').'.xls';
        Excel::load($filePath, function($reader) {
            $data = $reader->all();
            dd($data);
        });*/
    }

    /**
     * 题列表
     * Created by PhpStorm.
     * User: yezhangtao
     * Date: 2020/9/27
     * Time: 10:00
     * @param void
     */
    public function lists(){
        $back['details'] = Examination::getRandomTenData(10);

        $back['id'] = 1;
        $back['name'] = '一套题';
        $back['examination_type'] = 1;
        $back['status'] = 1;
        $back['status_text'] = '正常';
        $back['examination_type_text'] = '随机';
        return $this->backjson('ok', 1, $back);
    }

    /**
     * 提交试卷
     * Created by PhpStorm.
     * User: yezhangtao
     * Date: 2020/10/9
     * Time: 14:58
     * @param mixed
     * @param UseExamination $examination
     * @return mixed
     */
    public function submit(Request $request)
    {
        $details = request()->input('details', '[]');
        //dd(($details));
        if (empty($details)) {
            return $this->backjson('成绩参数不能为空', 0);
        }
        $examDetails = json_decode($details, true);
        if (!(isset($examDetails['details']) && is_array($examDetails['details']))) {
            return $this->backjson('格式错误', 0);
        }

        $userAnswers = [];
        $totalScore = 0;
        foreach ($examDetails['details'] as $key => $examDetail) {

            $rs = ExaminationService::find($examDetail['id']);
            if (!$rs){
                return $this->backjson('题目不存在', 0);
            }
            //提交答案
            $item = [
                'id' => $examDetail['id'],
                'content' => $rs->stem,
                //'options' => $examDetail->options,
                'answer' => $rs->answer,
                'score' => 1,
                'type' => 'radio',
                'user_answer' => strtoupper($examDetail['answer']),
                //'user_score' => $examDetail->answer ,
            ];

            if (strtoupper($examDetail['answer']) == strtoupper($rs->answer)) {
                //对
                $item['user_score'] = 1;
            } else {
                $item['user_score'] = 0;
            }

            //总成绩
            $totalScore += $item['user_score'];

            $options = explode(';', $rs->option);
            $rs->options = explode(';', $rs->option);
            $rs->type = 'radio';
            $rs->content = $rs->stem;
            unset($rs->option);
            unset($rs->stem);
            if (is_array($options)) {
                $temp = [];
                foreach ($options as $kk => $vv) {
                    $temp[] = [
                        'a' => trim((explode('.', $vv))[0]),
                        'b' => trim((explode('.', $vv))[1]),
                    ];
                }
                $rs->options = ($temp);
            }
            $item['options'] = $rs->options;
            $userAnswers[] = $item;
        }
        $userAnswers['score'] = $totalScore;

        try {
            $id = 1;
            $userAnswers['id'] = $id;
            $time = date('Y-m-d H:i:s');
            $data = [
                'test_id' => $id,
                'answers' => is_array($details) ? json_encode($details) : $details,
                'score' => $totalScore,
                'analysis' => json_encode($userAnswers),
                'created_at' => $time,
                'updated_at' => $time
            ];
            $rs = RecordsService::add($id, $data);
            if ($rs){
                $data['id'] = $id;
                unset($data['test_id']);
                unset($data['answers']);
                unset($data['analysis']);
                $data['examination_details'] = $userAnswers;
                $data['id'] = $id;
                return $this->backjson('ok', 1, $data);
            }
            return $this->backjson('提交试卷失败', 0);
        } catch (\Exception $e) {
            return $this->backjson('提交试卷失败2', 0);
        }
    }

    /**
     * 错题分析
     * Created by PhpStorm.
     * User: yezhangtao
     * Date: 2020/10/9
     * Time: 20:23
     * @param void
     * @param Request $request
     */
    public function recordAnalysis(Request $request){
        $id = $request->get('id', 0);
        if (!$id){
            return $this->backjson('试卷id必须', 0);
        }
        //查询
        $rs = RecordsService::find($id);

        //错题
        $error = json_decode($rs->analysis, true);
        $error_arr = [];
        foreach ($error as $v){
            if ($v['user_score'] == 0){
                $error_arr[] = $v;
            }
        }
        $data =[
            'id' => 1,
            'examination_details' => $error_arr,
            'score' => $rs->score,
            'type' => 1,
            'created_at' => \Carbon\Carbon::parse($rs->created_at)->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::parse($rs->updated_at)->toDateTimeString(),
        ];
        return $this->backjson('ok', 1, $data);
    }

    public function getScore(Request $request){
        $id = $request->get('id', 0);
        if (!$id){
            $id = 1;
        }
        //查询
        $rs = RecordsService::find($id);
        $data =[
            //'examination_details' => json_decode($rs->analysis, true),
            'id' => 1,
            'score' => $rs->score,
            'type' => 2,
            'created_at' => \Carbon\Carbon::parse($rs->created_at)->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::parse($rs->updated_at)->toDateTimeString(),
        ];
        return $this->backjson('ok', 1, $data);
    }
}

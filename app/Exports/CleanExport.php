<?php

namespace App\Exports;

use App\Model\User;
use App\Model\GetClean;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class CleanExport implements FromCollection,  ShouldAutoSize, WithHeadings
{
    use Exportable;
    public $startTime;
    public $stopTime;

    public function __construct($startTime, $stopTime)		// 导入外部查询参数
    {
        $this->startTime = $startTime;
        $this->stopTime = $stopTime;

    }
    public function collection()
    {

        return GetClean::query()
            ->whereDate('created_at','>=',$this->startTime)
            ->whereDate('created_at','<=',$this->stopTime)
            ->whereIn('permission',$this->userPermission())

            ->cursor(); // ← 重要的一点

    }

    public function headings(): array
    {
        return[
            'ID','楼盘名称','楼盘信息','房间号','租户名称','是否我司','物业类型','公司类型','租户联系人','开始时间','合同期限','结束时间','付款类型','付款日期','租户需求','备注','城市','商圈','经纪人姓名','经纪人手机号','定位地址','图片地址','权限','所属部门','经纪人id','提交时间','更新时间'
        ];
    }

    
    public function userPermission()//用户部门权限
    {
        $id = session('id');//用户id
        $user = User::find($id);
        $arr = array_filter(explode(',',$user->branch));
        return $arr;
    }

}

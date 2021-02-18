<?php

namespace App\Exports;

use App\Model\User;
use App\Model\GetTenant;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class TenantExport implements FromCollection, ShouldAutoSize, WithHeadings
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

        return GetTenant::query()
            ->whereDate('created_at','>=',$this->startTime)
            ->whereDate('created_at','<=',$this->stopTime)
            ->whereIn('permission',$this->userPermission())

            ->cursor(); // ← 重要的一点

    }

/*     public function query()
    {
        return GetTenant::query()->whereIn('permission',$this->userPermission());                
    } */

    public function headings(): array
    {
        return[
            'ID','租户名称','是否我司','联系人','公司类型','开始时间','结束时间','楼盘信息','','楼盘名称','房间号','物业类型','','付款类型','付款时间','租户需求','商圈','备注','经纪人','经纪人手机号'
        ];
    }

    
    public function userPermission()//用户部门权限
    {
        $id = session('id');//用户id
        $user = User::find($id);
        $arr = explode(',',$user->branch);
        return $arr;
    }
}

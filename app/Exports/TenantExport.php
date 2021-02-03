<?php

namespace App\Exports;

use App\Model\User;
use App\Model\GetTenant;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class TenantExport implements ShouldAutoSize, WithHeadings, FromQuery
{

    public function query()
    {
        return GetTenant::query()->whereIn('permission',$this->userPermission());                
    }

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

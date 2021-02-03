<?php

namespace App\Exports;

use App\Model\User;
use App\Model\GetClean;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class CleanExport implements  ShouldAutoSize, WithHeadings, FromQuery
{


    public function query()
    {
        return GetClean::query()->whereIn('permission',$this->userPermission());                
    }

    public function headings(): array
    {
        return[
            'ID','楼盘名称','楼盘信息','房间号','租户名称','是否我司','物业类型','','租户联系人','开始时间','','结束时间'
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

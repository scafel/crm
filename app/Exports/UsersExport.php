<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;    //设置标题
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;   //为空时零填充
use Maatwebsite\Excel\Concerns\ShouldAutoSize;      //自动单元格尺寸
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;      //设置单元格数据格式
use Maatwebsite\Excel\Concerns\WithColumnFormatting;       //设置列格式

class UsersExport implements FromCollection,WithHeadings,WithStrictNullComparison,WithColumnFormatting,ShouldAutoSize
{
    private $pluck = "";
    private $where  =   "";
    private $old    =   [];
    private $header = [];


    public function __construct($data)
    {
        unset($data['_token']);
        $this->old  =   $data;
        $this->makeWhere();
        $this->makePluck();
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $sql    =   "SELECT ".$this->pluck." FROM scafel_user WHERE 1 ".$this->where;
        $data   =   DB::select($sql);
        foreach ($data as $key => $value){

        }
        return $data;
    }

    public function headings(): array
    {
        return [
            //这里填写首行标题
            $this->header
        ];
    }
//设置列格式
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
        ];
    }
    public function makeWhere(){
        $where = "";
        $data   =   $this->old;
        if (isset($data['timecode']) && $data['timecode']){
            $time   =   explode("-",$data['timecode']);
            $time1  =   $this->timeString($time[0],$time[1],$time[2]);
            $time2  =   $this->timeString($time[3],$time[4],$time[5]);
            $where  .=   " and addtime BETWEEN $time1 and $time2 ";
        }
        if (isset($data['department']) && $data['department']){
            if (mb_strlen($where)){ $where .= " and ";}
            if (is_array($data['department'])){
                $department =   "(";
                foreach ($data['department'] as $datum) {
                    $department .= $datum;
                }
                $department .= ")";
                $where .= " department_id in $department ";
            }else{
                $where .=  " department_id = {$data['department']}";
            }
        }
        if (isset($data['channel']) && $data['channel']){
            if (mb_strlen($where)){ $where .= " and ";}
            if (is_array($data['channel'])){
                $department =   "(";
                foreach ($data['channel'] as $datum) {
                    $department .= $datum;
                }
                $department .= ")";
                $where .= " channel_id in $department ";
            }else{
                $where .=  " channel_id = {$data['channel']}";
            }
        }
        $this->where = $where;
    }
    public function makePluck(){
        $data   =   $this->old;
        unset($data['department']);
        unset($data['timecode']);
        unset($data['channel']);
        $pluck = "";$pluckName = "";
        foreach ($data as $key=>$value){
            $pluck .= "$key,";
            $pluckName .= $this->pluckToChinese($key).",";
        }
        $pluck = rtrim($pluck, ",");
        $this->pluck = $pluck;
        $pluckName = rtrim($pluckName, ",");
        $this->header = $pluckName;
    }
    public function pluckToChinese($key){
        $array  =   array(
            'username'=>'用户名',
            'tel'   =>'联系电话',
            'addr'=>'住址',
            'age'=>'年龄',
            'gander'=>'性别',
            'department_id'=>'就诊科室',
            'channel_id'=>'来院渠道',
            'addtime'=>'添加日期',
            'remarks'=>'备注',
        );
        return $array[$key]??$key;
    }
    public function getDepartmentName($id){
        $value = Cache::remember('department', function () {
            $department =   DB::table('department')->pluck('id,name');
            $departmentName = [];
            foreach ($department as $k=>$v){
                $departmentName[$v->id] = $v->name;
            }
            return $departmentName;
        });
        return $value[$id];

    }
    public function getChannelName($id){
        $value = Cache::remember('channel', function () {
            $channel    =   DB::table('channel')->pluck("id,name");
            $channelName = [];
            foreach ($channel as $k=>$v){
                $channelName[$v->id] = $v->name;
            }
            return $channelName;
        });
        return $value[$id];
    }
}

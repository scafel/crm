<?php

namespace App\Http\Controllers\Home;

use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/**
 * 默认使用用户导出为模板  案例样式
 * Class ExportController
 * @package App\Http\Controllers\Home
 */
class ExportController extends Controller
{
    private $pluck = "";
    private $where  =   "";
    private $old    =   [];
    private $header = [];
    private $head = [];

    public function __construct($data = "")
    {
        if ($data){
            unset($data['_token']);
            $data   =   array_filter($data);
            $this->old  =   $data;
            $this->makeWhere();
            $this->makePluck();
        }
    }

    /**
     * @return array
     */
    public function dataReturn() :array
    {
        $where = "";$pluck = "*";
        if($this->where){$where = "where ".$this->where;};
        if ($this->pluck){$pluck = $this->pluck;};
        $sql    =   "SELECT ".$pluck." FROM scafel_user ".$where;
        $data   =   DB::select($sql);
        $result = [];$n = 0;
        foreach ($data as $key => $value){
            $value  =   object_to_array($value);
            foreach ($value as $k=>$v){
                if ($k == "department_id"){$value['department_id'] = $this->getDepartmentName($v);};
                if ($k == "channel_id"){$value['channel_id'] = $this->getChannelName($v);};
                if ($k == "addtime"){$value['addtime'] =   date("Y-m-d H:i:s",(int) $v);};
                if ($k == "gander"){$value['gander']    =   $value['gander']?"男":"女";};
            }
            $result[$n] =   $value;
            $n ++;
        }
        return $result;
    }
    public function getHeader():array {
        return $this->header;
    }
    private function makeWhere(){
        $where = "";
        $data   =   $this->old;
        if (isset($data['timecode']) && $data['timecode']){
            $time   =   explode("-",$data['timecode']);
            $time1  =   $this->timeString($time[0],$time[1],$time[2]);
            $time2  =   $this->timeString($time[3],$time[4],$time[5]);
            $where  .=   " addtime BETWEEN $time1 and $time2 ";
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
    private function makePluck(){
        $data   =   $this->old;
        unset($data['department']);
        unset($data['timecode']);
        unset($data['channel']);
        $pluck = "";$pluckName = [];
        foreach ($data as $key=>$value){
            $pluck .= "$key,";
            $pluckName[] = $this->pluckToChinese($key);
            $this->head[] = $key;
        }
        $pluck = rtrim($pluck, ",");
        $this->pluck = $pluck;
        $this->header = $pluckName;
    }
    private function pluckToChinese($key):string {
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
    private function getDepartmentName($id):string {
        $value = Cache::remember('department',20, function () {
            $department =   DB::table('department')->get(['id','name']);
            $departmentName = [];
            foreach ($department as $k=>$v){
                $departmentName[$v->id] = $v->name;
            }
            return $departmentName;
        });
        return $value[$id];
    }
    private function getChannelName($id):string {
        $value = Cache::remember('channel',20, function () {
            $channel    =   DB::table('channel')->get(['id','name']);
            $channelName = [];
            foreach ($channel as $k=>$v){
                $channelName[$v->id] = $v->name;
            }
            return $channelName;
        });
        return $value[$id];
    }

    /**
     * 导出成excel
     * @param array $header 表单第一行标题
     * @param array $result 表单遍历结果
     * @param string $filename 文件名
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function export(array $header,array $result,string $filename){
        require_once(base_path() . '/app/lib/PHPExce/Classes/PHPExcel.php');
        require_once(base_path() . '/app/lib/PHPExce/Classes/PHPExcel/Writer/Excel2007.php');
        $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getActiveSheet(0)->mergeCells('A1'.':'.$cellName[count($header)-1]."1");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '数据导出：'.date('Y-m-d H:i:s'));  //设置合并后的单元格内容
        foreach ($header as $key=>$value){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$key].'2', $value);
        }
        foreach ($result as $k=>$data) {
            $j = 0;
            foreach($data as $_cell){
                $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j] . ($k+3), $_cell);
                $j++;
            }
        }
        $filename = $filename.date("Y-m-d");
        ob_end_clean();//清除缓冲区,避免乱码
        header('Content-Type: application/vnd.ms-excel');
        $excel_name = str_replace('+', '%20', urlencode($filename));
        header('Content-Disposition: attachment;filename="'.$excel_name.'.xls"');
        header('Cache-Control: max-age=0');
        // 如果是在ie9浏览器下，需要用到这个
        header('Cache-Control: max-age=1');
        // 如果你是在ie浏览器或者https下，需要用到这个
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
        $objWriter->save('php://output');
    }
}
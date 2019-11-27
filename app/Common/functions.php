<?php
/**
 * Created by PhpStorm.
 * User: Ly
 * Date: 2019/1/4
 * Time: 8:51
 */


/**
 * Ajax方式返回数据到客户端
 * @access protected
 * @param mixed $data 要返回的数据
 * @param String $type AJAX返回数据格式
 * @param int $json_option 传递给json_encode的option参数
 * @return void
 */
function ajaxReturn(array $array,string $msg,int $code,string $type='') {
    $data   =   array(
        'data'=>$array,
        'msg'=>$msg,
        'errorcode'=>$code
    );
    //header("Access-Control-Allow-Origin: http://a.com"); // 允许a.com发起的跨域请求
    //如果需要设置允许所有域名发起的跨域请求，可以使用通配符 *
    header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
    header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With');
    if(empty($type)) $type  =   'json';
    switch (strtoupper($type)){
        case 'JSON' :
            // 返回JSON数据格式到客户端 包含状态信息
            header('Content-Type:application/json; charset=utf-8');
            return $data;
        case 'XML'  :
            // 返回xml格式数据
            header('Content-Type:text/xml; charset=utf-8');
            return xml_encode($data);
        case 'EVAL' :
            // 返回可执行的js脚本
            header('Content-Type:text/html; charset=utf-8');
            return $data;
        default     :
            return $data;
    }
}

/**
 * 判断是否SSL协议
 * @return boolean
 */
function is_ssl() {
    if(isset($_SERVER['HTTPS']) && ('1' == $_SERVER['HTTPS'] || 'on' == strtolower($_SERVER['HTTPS']))){
        return true;
    }elseif(isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'] )) {
        return true;
    }
    return false;
}

/**
 * XML编码
 * @param mixed $data 数据
 * @param string $root 根节点名
 * @param string $item 数字索引的子节点名
 * @param string $attr 根节点属性
 * @param string $id   数字索引子节点key转换的属性名
 * @param string $encoding 数据编码
 * @return string
 */
function xml_encode($data, $root='think', $item='item', $attr='', $id='id', $encoding='utf-8') {
    if(is_array($attr)){
        $_attr = array();
        foreach ($attr as $key => $value) {
            $_attr[] = "{$key}=\"{$value}\"";
        }
        $attr = implode(' ', $_attr);
    }
    $attr   = trim($attr);
    $attr   = empty($attr) ? '' : " {$attr}";
    $xml    = "<?xml version=\"1.0\" encoding=\"{$encoding}\"?>";
    $xml   .= "<{$root}{$attr}>";
    $xml   .= data_to_xml($data, $item, $id);
    $xml   .= "</{$root}>";
    return $xml;
}

/**
 * 数据XML编码
 * @param mixed  $data 数据
 * @param string $item 数字索引时的节点名称
 * @param string $id   数字索引key转换为的属性名
 * @return string
 */
function data_to_xml($data, $item='item', $id='id') {
    $xml = $attr = '';
    foreach ($data as $key => $val) {
        if(is_numeric($key)){
            $id && $attr = " {$id}=\"{$key}\"";
            $key  = $item;
        }
        $xml    .=  "<{$key}{$attr}>";
        $xml    .=  (is_array($val) || is_object($val)) ? data_to_xml($val, $item, $id) : $val;
        $xml    .=  "</{$key}>";
    }
    return $xml;
}

/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
 * @return mixed
 */
function get_client_ip($type = 0,$adv=false) {
    $type       =  $type ? 1 : 0;
    static $ip  =   NULL;
    if ($ip !== NULL) return $ip[$type];
    if($adv){
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos    =   array_search('unknown',$arr);
            if(false !== $pos) unset($arr[$pos]);
            $ip     =   trim($arr[0]);
        }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip     =   $_SERVER['HTTP_CLIENT_IP'];
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip     =   $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u",ip2long($ip));
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}

/**
 * 不区分大小写的in_array实现
 * @param $value
 * @param $array
 * @return bool
 */
function in_array_case($value,$array){
    return in_array(strtolower($value),array_map('strtolower',$array));
}

/**
 * CURL请求
 * @param $url 请求url地址
 * @param $method 请求方法 get post
 * @param null $postfields post数据数组
 * @param array $headers 请求header信息
 * @param bool|false $debug  调试开启 默认false
 * @return mixed
 */
function httpRequest(string $url,string $method="GET",array $postfields = null,array $headers = array(),bool $debug = false) {
    $method = strtoupper($method);
    $ci = curl_init();
    /* Curl settings */
    curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
    curl_setopt($ci, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0");
    curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 60); /* 在发起连接前等待的时间，如果设置为0，则无限等待 */
    curl_setopt($ci, CURLOPT_TIMEOUT, 7); /* 设置cURL允许执行的最长秒数 */
    curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
    switch ($method) {
        case "POST":
            curl_setopt($ci, CURLOPT_POST, true);
            if (!empty($postfields)) {
                $tmpdatastr = is_array($postfields) ? http_build_query($postfields) : $postfields;
                curl_setopt($ci, CURLOPT_POSTFIELDS, $tmpdatastr);
            }
            break;
        default:
            curl_setopt($ci, CURLOPT_CUSTOMREQUEST, $method); /* //设置请求方式 */
            break;
    }
    $ssl = preg_match('/^https:\/\//i',$url) ? TRUE : FALSE;
    curl_setopt($ci, CURLOPT_URL, $url);
    if($ssl){
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
        curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, FALSE); // 不从证书中检查SSL加密算法是否存在
    }
    //curl_setopt($ci, CURLOPT_HEADER, true); /*启用时会将头文件的信息作为数据流输出*/
    curl_setopt($ci, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ci, CURLOPT_MAXREDIRS, 2);/*指定最多的HTTP重定向的数量，这个选项是和CURLOPT_FOLLOWLOCATION一起使用的*/
    curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ci, CURLINFO_HEADER_OUT, true);
    /*curl_setopt($ci, CURLOPT_COOKIE, $Cookiestr); * *COOKIE带过去** */
    $response = curl_exec($ci);
    $requestinfo = curl_getinfo($ci);
    $http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
    if ($debug) {
        echo "=====post data======\r\n";
        var_dump($postfields);
        echo "=====info===== \r\n";
        print_r($requestinfo);
        echo "=====response=====\r\n";
        print_r($response);
    }
    curl_close($ci);
    return $response;
}

/**
 * 验证表字段，并返回表拥有的字段
 * @param string $table 表名
 * @param array $input 输入的内容
 * @return array
 */
function createtabledata(string $table = '',array  $input)
{
    //表字段
    $TableFields = getTableFields(config('database.connections.mysql.prefix').$table);
    //过滤前数据
    $filter_data = $input;
    $data = [];
    foreach ($TableFields as $v) {
        $key    =   $v->COLUMN_NAME;
        if (!empty($filter_data[$key])) {
            $data[$key] = $filter_data[$key];
        }
    }
    return empty($data)?false:$data;
}

/**
 * 查询表字段
 * @param $table
 * @return array
 */
function getTableFields(string $table){
    $sql    =   "select COLUMN_NAME from information_schema.COLUMNS where table_name = '{$table}'";
    $message    =   \Illuminate\Support\Facades\DB::select($sql);
    return $message;
}

/**
 * 将数组转换成where条件需要的结构
 * @param array $data
 * @return array
 */
function data2where(array $data){
    foreach ($data as $key=>$value){
        $where[]    =   [$key,'=',$value];
    }
    return $where;
}

/**
 * id的md5加密和检测
 * @param int $id
 * @param string $token
 * @return bool|string
 */
function idMd5Token($id,string $token = ''){
    $md5Token    =   (string)md5($id.'scafel');
    $idToken    =   encrypt($md5Token);
    if ($token){
        try{
            $md5TokenR = decrypt($token);
            return $md5Token === $md5TokenR ? true:false;
        }catch (\Illuminate\Contracts\Encryption\DecryptException $e){
            return false;
        }
    }else{
        return $idToken;
    }
}

/**
 * 获取某个目录下所有的文件
 * @param $path  路径
 * @param bool $child  是否有子路径
 * @return array|null
 */
function getFiles($path,$child=false){
    $files=array();
    if(!$child){
        if(is_dir($path)){
            $dp = dir($path);
        }else{
            return null;
        }
        while ($file = $dp ->read()){
            if($file !="." && $file !=".." && is_file($path.$file)){
                $files[] = $file;
            }
        }
        $dp->close();
    }else{
        scanfiles($files,$path);
    }
    return $files;
}

/**
 * @param $files 结果
 * @param $path 路径
 * @param bool $childDir
 */
function scanfiles(&$files,$path,$childDir=false){
    $dp = dir($path);
    while ($file = $dp ->read()){
        if($file !="." && $file !=".."){
            if(is_file($path.$file)){//当前为文件
                $files[]= $file;
            }else{//当前为目录
                scanfiles($files[$file],$path.$file.DIRECTORY_SEPARATOR,$file);
            }
        }
    }
    $dp->close();
}

/**
 * 检测当前用户是否有模块权限
 * @param int $id 模块id
 * @return bool|void
 */
function hasModel(int $id){
    $model  =   session('admin');
    if ($model){
        $model_id   =   $model->model_id;
        $model_array    =   explode(',',$model_id);
        if (in_array($id,$model_array)){
            return exit(ajaxReturn([],'没有权限执行此项操作',1));
        }else{
            return true;
        }
    }else{
        return true;
    }
}

/**
 * 模块对应菜单
 * @return array
 */
function modelMenu(){
    $model  =   session('admin');
    if ($model){
        $model_id   =   $model->model_id;
        $model_array    =   explode(',',$model_id);
        $result =   array_filter($model_array);
        if (!empty($result)){
            $modelMenu  =   \Illuminate\Support\Facades\DB::table('model')->whereIn('id',$result)->first();
            return $modelMenu;
        }else{
            return [];
        }
    }else{
        return [];
    }
}

/**
 * 生成静态页面
 * @param $path 路径
 * @param $file_name 文件名
 * @param $content 文件内容
 * @return mixed
 */
function create_static_page($path,$file_name,$content){
    if(is_dir($path)){
        $file_name = handle_file_name($path,$file_name);
        file_put_contents($file_name, $content);
        chmod($file_name,0777);
    }else{
        mkdir($path);
        create_static_page($path,$file_name,$content);
    }
    return $content;
}

/**
 * 删除静态页面
 * @param $file 文件
 */
function delete_static_page($file){
    if(is_file($file)){
        unlink($file);
    }
}

/**
 * 清空目录下的所有静态页面
 * @param $path
 */
function delete_all_static_page($path){
    $file_path=scandir($path);
    foreach ($file_path as  $value) {
        if($value!='.' &&$value!='..'){
            delete_static_page(handle_file_name($path,$value));
        }
    }
}

function data2menu($data){
    $data = array_filter($data);
    $menu = [];
    foreach ($data as $value){
        if($value->pid == 0){
            foreach ($data as $val){
                if ($val->pid == $value->id){
                    $value->child[]   =   $val;
                }
            }
            $menu[] = $value;
        }
    }
    return $menu;
}

/**
 * 文件路径处理函数
 * @param $path
 * @param $file_name
 * @return string
 */
function handle_file_name($path,$file_name){
    if(substr($path, -1,1)=='/'){
        return $path.$file_name;
    }else{
        return $path.'/'.$file_name;
    }
}

/**
 * 是否为手机端
 * @return boolean   返回true 为手机端  false 为PC 端
 */
function isMobile(){
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
        return true;

    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA']))
    {
        // 找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    }
    // 脑残法，判断手机发送的客户端标志,兼容性有待提高
    if (isset ($_SERVER['HTTP_USER_AGENT']))
    {
        $clientkeywords = array ('nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile');
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
            return true;
    }
    // 协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT']))
    {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
        {
            return true;
        }
    }
    return false;
}

/**
 * 将汉字转换为拼音
 * @param $_String
 * @param string $_Code
 * @return null|string|string[]
 */
function pinyin($_String, $_Code='utf_8'){
    $_DataKey = "a|ai|an|ang|ao|ba|bai|ban|bang|bao|bei|ben|beng|bi|bian|biao|bie|bin|bing|bo|bu|ca|cai|can|cang|cao|ce|ceng|cha".
        "|chai|chan|chang|chao|che|chen|cheng|chi|chong|chou|chu|chuai|chuan|chuang|chui|chun|chuo|ci|cong|cou|cu|".
        "cuan|cui|cun|cuo|da|dai|dan|dang|dao|de|deng|di|dian|diao|die|ding|diu|dong|dou|du|duan|dui|dun|duo|e|en|er".
        "|fa|fan|fang|fei|fen|feng|fo|fou|fu|ga|gai|gan|gang|gao|ge|gei|gen|geng|gong|gou|gu|gua|guai|guan|guang|gui".
        "|gun|guo|ha|hai|han|hang|hao|he|hei|hen|heng|hong|hou|hu|hua|huai|huan|huang|hui|hun|huo|ji|jia|jian|jiang".
        "|jiao|jie|jin|jing|jiong|jiu|ju|juan|jue|jun|ka|kai|kan|kang|kao|ke|ken|keng|kong|kou|ku|kua|kuai|kuan|kuang".
        "|kui|kun|kuo|la|lai|lan|lang|lao|le|lei|leng|li|lia|lian|liang|liao|lie|lin|ling|liu|long|lou|lu|lv|luan|lue".
        "|lun|luo|ma|mai|man|mang|mao|me|mei|men|meng|mi|mian|miao|mie|min|ming|miu|mo|mou|mu|na|nai|nan|nang|nao|ne".
        "|nei|nen|neng|ni|nian|niang|niao|nie|nin|ning|niu|nong|nu|nv|nuan|nue|nuo|o|ou|pa|pai|pan|pang|pao|pei|pen".
        "|peng|pi|pian|piao|pie|pin|ping|po|pu|qi|qia|qian|qiang|qiao|qie|qin|qing|qiong|qiu|qu|quan|que|qun|ran|rang".
        "|rao|re|ren|reng|ri|rong|rou|ru|ruan|rui|run|ruo|sa|sai|san|sang|sao|se|sen|seng|sha|shai|shan|shang|shao|".
        "she|shen|sheng|shi|shou|shu|shua|shuai|shuan|shuang|shui|shun|shuo|si|song|sou|su|suan|sui|sun|suo|ta|tai|".
        "tan|tang|tao|te|teng|ti|tian|tiao|tie|ting|tong|tou|tu|tuan|tui|tun|tuo|wa|wai|wan|wang|wei|wen|weng|wo|wu".
        "|xi|xia|xian|xiang|xiao|xie|xin|xing|xiong|xiu|xu|xuan|xue|xun|ya|yan|yang|yao|ye|yi|yin|ying|yo|yong|you".
        "|yu|yuan|yue|yun|za|zai|zan|zang|zao|ze|zei|zen|zeng|zha|zhai|zhan|zhang|zhao|zhe|zhen|zheng|zhi|zhong|".
        "zhou|zhu|zhua|zhuai|zhuan|zhuang|zhui|zhun|zhuo|zi|zong|zou|zu|zuan|zui|zun|zuo";
    $_DataValue = "-20319|-20317|-20304|-20295|-20292|-20283|-20265|-20257|-20242|-20230|-20051|-20036|-20032|-20026|-20002|-19990".
        "|-19986|-19982|-19976|-19805|-19784|-19775|-19774|-19763|-19756|-19751|-19746|-19741|-19739|-19728|-19725".
        "|-19715|-19540|-19531|-19525|-19515|-19500|-19484|-19479|-19467|-19289|-19288|-19281|-19275|-19270|-19263".
        "|-19261|-19249|-19243|-19242|-19238|-19235|-19227|-19224|-19218|-19212|-19038|-19023|-19018|-19006|-19003".
        "|-18996|-18977|-18961|-18952|-18783|-18774|-18773|-18763|-18756|-18741|-18735|-18731|-18722|-18710|-18697".
        "|-18696|-18526|-18518|-18501|-18490|-18478|-18463|-18448|-18447|-18446|-18239|-18237|-18231|-18220|-18211".
        "|-18201|-18184|-18183|-18181|-18012|-17997|-17988|-17970|-17964|-17961|-17950|-17947|-17931|-17928|-17922".
        "|-17759|-17752|-17733|-17730|-17721|-17703|-17701|-17697|-17692|-17683|-17676|-17496|-17487|-17482|-17468".
        "|-17454|-17433|-17427|-17417|-17202|-17185|-16983|-16970|-16942|-16915|-16733|-16708|-16706|-16689|-16664".
        "|-16657|-16647|-16474|-16470|-16465|-16459|-16452|-16448|-16433|-16429|-16427|-16423|-16419|-16412|-16407".
        "|-16403|-16401|-16393|-16220|-16216|-16212|-16205|-16202|-16187|-16180|-16171|-16169|-16158|-16155|-15959".
        "|-15958|-15944|-15933|-15920|-15915|-15903|-15889|-15878|-15707|-15701|-15681|-15667|-15661|-15659|-15652".
        "|-15640|-15631|-15625|-15454|-15448|-15436|-15435|-15419|-15416|-15408|-15394|-15385|-15377|-15375|-15369".
        "|-15363|-15362|-15183|-15180|-15165|-15158|-15153|-15150|-15149|-15144|-15143|-15141|-15140|-15139|-15128".
        "|-15121|-15119|-15117|-15110|-15109|-14941|-14937|-14933|-14930|-14929|-14928|-14926|-14922|-14921|-14914".
        "|-14908|-14902|-14894|-14889|-14882|-14873|-14871|-14857|-14678|-14674|-14670|-14668|-14663|-14654|-14645".
        "|-14630|-14594|-14429|-14407|-14399|-14384|-14379|-14368|-14355|-14353|-14345|-14170|-14159|-14151|-14149".
        "|-14145|-14140|-14137|-14135|-14125|-14123|-14122|-14112|-14109|-14099|-14097|-14094|-14092|-14090|-14087".
        "|-14083|-13917|-13914|-13910|-13907|-13906|-13905|-13896|-13894|-13878|-13870|-13859|-13847|-13831|-13658".
        "|-13611|-13601|-13406|-13404|-13400|-13398|-13395|-13391|-13387|-13383|-13367|-13359|-13356|-13343|-13340".
        "|-13329|-13326|-13318|-13147|-13138|-13120|-13107|-13096|-13095|-13091|-13076|-13068|-13063|-13060|-12888".
        "|-12875|-12871|-12860|-12858|-12852|-12849|-12838|-12831|-12829|-12812|-12802|-12607|-12597|-12594|-12585".
        "|-12556|-12359|-12346|-12320|-12300|-12120|-12099|-12089|-12074|-12067|-12058|-12039|-11867|-11861|-11847".
        "|-11831|-11798|-11781|-11604|-11589|-11536|-11358|-11340|-11339|-11324|-11303|-11097|-11077|-11067|-11055".
        "|-11052|-11045|-11041|-11038|-11024|-11020|-11019|-11018|-11014|-10838|-10832|-10815|-10800|-10790|-10780".
        "|-10764|-10587|-10544|-10533|-10519|-10331|-10329|-10328|-10322|-10315|-10309|-10307|-10296|-10281|-10274".
        "|-10270|-10262|-10260|-10256|-10254";
    $_TDataKey = explode('|', $_DataKey);
    $_TDataValue = explode('|', $_DataValue);
    $_Data = (PHP_VERSION>='5.0') ? array_combine($_TDataKey, $_TDataValue) : _Array_Combine($_TDataKey, $_TDataValue);
    arsort($_Data);
    reset($_Data);
    if($_Code != 'gb2312') $_String = _U2_Utf8_Gb($_String);
    $_Res = '';
    for($i=0; $i<strlen($_String); $i++)
    {
        $_P = ord(substr($_String, $i, 1));
        if($_P>160) { $_Q = ord(substr($_String, ++$i, 1)); $_P = $_P*256 + $_Q - 65536; }
        $_Res .= _Pinyin($_P, $_Data);
    }
    return preg_replace("/[^a-z0-9]*/", '', $_Res);
}
function _Pinyin($_Num, $_Data)
{
    if ($_Num>0 && $_Num<160 ) return chr($_Num);
    elseif($_Num<-20319 || $_Num>-10247) return '';
    else {
        foreach($_Data as $k=>$v){ if($v<=$_Num) break; }
        return $k;
    }
}
function _U2_Utf8_Gb($_C)
{
    $_String = '';
    if($_C < 0x80) $_String .= $_C;
    elseif($_C < 0x800)
    {
        $_String .= chr(0xC0 | $_C>>6);
        $_String .= chr(0x80 | $_C & 0x3F);
    }elseif($_C < 0x10000){
        $_String .= chr(0xE0 | $_C>>12);
        $_String .= chr(0x80 | $_C>>6 & 0x3F);
        $_String .= chr(0x80 | $_C & 0x3F);
    } elseif($_C < 0x200000) {
        $_String .= chr(0xF0 | $_C>>18);
        $_String .= chr(0x80 | $_C>>12 & 0x3F);
        $_String .= chr(0x80 | $_C>>6 & 0x3F);
        $_String .= chr(0x80 | $_C & 0x3F);
    }
    return iconv('UTF-8', 'GB2312', $_String);
}

/**
 * 数组 转 对象
 *
 * @param array $arr 数组
 * @return object
 */
function array_to_object($arr) {
    if (gettype($arr) != 'array') {
        return;
    }
    foreach ($arr as $k => $v) {
        if (gettype($v) == 'array' || getType($v) == 'object') {
            $arr[$k] = (object)array_to_object($v);
        }
    }

    return (object)$arr;
}
/**
 * 对象 转 数组
 *
 * @param object $obj 对象
 * @return array
 */
function object_to_array($obj) {
    $obj = (array)$obj;
    foreach ($obj as $k => $v) {
        if (gettype($v) == 'resource') {
            return;
        }
        if (gettype($v) == 'object' || gettype($v) == 'array') {
            $obj[$k] = (array)object_to_array($v);
        }
    }

    return $obj;
}
/**
 * 获取随机字符串
 * @param int $randLength  字符串长度
 * @param int $addtime     是否使用时间戳
 * @param int $includenumber    特定字符
 * @return string
 */
function get_rand_str($randLength=6,$addtime=1,$includenumber=0){
    if ($includenumber){
        $chars='abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNPQEST123456789';
    }else {
        $chars='abcdefghijklmnopqrstuvwxyz';
    }
    $len=strlen($chars);
    $randStr='';
    for ($i=0;$i<$randLength;$i++){
        $randStr.=$chars[rand(0,$len-1)];
    }
    $tokenvalue=$randStr;
    if ($addtime){
        $tokenvalue=$randStr.time();
    }
    return $tokenvalue;
}

function userIsLogin(){
    \Illuminate\Support\Facades\Cache::remember("user_id_login_is_token",15,function (){

    });
}

/**
 * 微信日志记录
 * @param $data
 */
function wechatLog($data){
    if (is_object($data)){
        $data = object_to_array($data);
    }
    $add['log'] =   serialize($data);
    \Illuminate\Support\Facades\DB::table('wechat_log')->insert($add);
}

/**
 * 根据微信公众号的id获取微信内容
 * @param int $id
 * @return object
 */
function getWechatMessage(int $id) {
    return \Illuminate\Support\Facades\Cache::rememberForever("wechat_id__{$id}",function () use($id){
        return \Illuminate\Support\Facades\DB::table('wechat')->where('id','=',$id)->first();
    });
}
/**
 * 获取全部预约条目
 * @return mixed
 */
function getToUserMessage(){
    $toid   =   session('user_id_login');
    $sql    =   "SELECT COUNT(id) FROM scafel_notepad WHERE ((isread = 0 and isrun = 0) or (isread = 1 and isrun = 0) or (isread = 0 and isrun = 1)) and toid = {$toid}";
    $message    =   \Illuminate\Support\Facades\DB::select($sql);
    $return =   object_to_array($message);
    return $return[0]['COUNT(id)'];
}
/**
 * 获取预约明日服务条目
 * @return int
 */
function getToUserMessageTomorrow(){
    $toid   =   session('user_id_login');
    $gettime    =   getTime();
    $start  =   mktime(0,0,0,$gettime['m'],$gettime['d']+1,$gettime['y']);
    $end  =   mktime(23,59,59,$gettime['m'],$gettime['d']+1,$gettime['y']);
    $sql    =   "SELECT COUNT(id) FROM scafel_notepad WHERE ((isread = 0 and isrun = 0) or (isread = 1 and isrun = 0) or (isread = 0 and isrun = 1)) and toid = {$toid} and runtime BETWEEN {$start} and {$end}";
    $message    =   \Illuminate\Support\Facades\DB::select($sql);
    $return =   object_to_array($message);
    return $return[0]['COUNT(id)'];
}
/**
 * 获取预约今日服务条目
 * @return int
 */
function getToUserMessageToday(){
    $toid   =   session('user_id_login');
    $gettime    =   getTime();
    $start  =   mktime(0,0,0,$gettime['m'],$gettime['d'],$gettime['y']);
    $end  =   mktime(23,59,59,$gettime['m'],$gettime['d'],$gettime['y']);
    $sql    =   "SELECT COUNT(id) FROM scafel_notepad WHERE ((isread = 0 and isrun = 0) or (isread = 1 and isrun = 0) or (isread = 0 and isrun = 1)) and toid = {$toid} and runtime BETWEEN {$start} and {$end}";
    $message    =   \Illuminate\Support\Facades\DB::select($sql);
    $return =   object_to_array($message);
    return $return[0]['COUNT(id)'];
}
function getFromUserMessage(){
    $fromid   =   session('user_id_login');
    $message    =   \Illuminate\Support\Facades\DB::table("notepad")->where("fromid",'=',$fromid)->count("id");
    return $message;
}
/**
 * 自定义加密解密函数
 * scafelEncrypt($string,$operation,$key)
 * @param $string  需要加密的字符串或数字
 * @param $operation 判断是加密还是解密   E加密 D解密
 * @param string $key 密钥
 * @return string
 */
function scafelEncrypt($string,$operation = 'E',$key='scafel11032618'){
    $key=md5($key);
    $key_length=strlen($key);
    $string =   $operation=='D'?base64_decode($string):substr(md5($string.$key),0,8).$string;
    $string_length=strlen($string);
    $rndkey=$box=array();
    $result='';
    for($i=0;$i<=255;$i++){
        $rndkey[$i]=ord($key[$i%$key_length]);
        $box[$i]=$i;
    }
    for($j=$i=0;$i<256;$i++){
        $j=($j+$box[$i]+$rndkey[$i])%256;
        $tmp=$box[$i];
        $box[$i]=$box[$j];
        $box[$j]=$tmp;
    }
    for($a=$j=$i=0;$i<$string_length;$i++){$a=($a+1)%256;$j=($j+$box[$a])%256;$tmp=$box[$a];$box[$a]=$box[$j];
        $box[$j]=$tmp;
        $result.=chr(ord($string[$i])^($box[($box[$a]+$box[$j])%256]));
    }
    if($operation=='D'){
        if(substr($result,0,8)==substr(md5(substr($result,8).$key),0,8)){
            return substr($result,8);
        }else{
            return'';
        }
    }else{
        return str_replace('=','',base64_encode($result));
    }
}
function sendMessage(){

}
function getTime(){
    $time['y']   =   date('Y',time());
    $time['m']  =   date('m',time());
    $time['d']    =   date('d',time());
    $time['h']   =   date('H',time());
    $time['i']    =   date('i',time());
    $time['s']    =   date('s',time());
    return $time;
}

/**
 * 根据id获取来院渠道名称
 * @param $id
 * @return mixed
 */
function getChannelNameById($id){
        $channel    =   \Illuminate\Support\Facades\Cache::rememberForever('getChannelNameById_'.$id,function () use ($id){
           return \Illuminate\Support\Facades\DB::table('channel')->select('name')->find($id);
        });
        return $channel->name;
}

/**
 * 根据id获取就诊科室名称
 * @param $id
 * @return string
 */
function getDepartmentNameById($id){
    $department    =   \Illuminate\Support\Facades\Cache::rememberForever('getDepartmentNameById_'.$id,function () use ($id){
        return \Illuminate\Support\Facades\DB::table('department')->select('name')->find($id);
    });
    return $department->name;
}
/**
 * 根据id获取管理员名字
 * @param $id
 * @return string
 */
function getAdminNameById($id){
    $admin    =   \Illuminate\Support\Facades\Cache::rememberForever('getAdminNameById_'.$id,function () use ($id){
        return \Illuminate\Support\Facades\DB::table('admin')->select('name','username')->find($id);
    });
    return $admin->username;
}
/**
 * 根据id获取用户信息
 * @param $id
 * @return object
 */
function getUserInfoNameById($id){
    return  \Illuminate\Support\Facades\Cache::rememberForever('getUserInfoNameById_'.$id,function () use ($id){
        return \Illuminate\Support\Facades\DB::table('user')->find($id);
    });
}
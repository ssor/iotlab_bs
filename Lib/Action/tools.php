<?php
class Tools extends Action
{
    static public function get_system_name_by_id_directly($system_id)
    {
        $system_info = self::get_system_info_by_id_directly($system_id);
        return $system_info['text'];
    }
    static public function all_system_list_array()
    {
        $result = self::system_list_array();
        $result[] = array('id' => 'document', 'text' => '原理及案例分析'
                        , 'description' => ''
                        , 'image' => '6_large.jpg');  
        $result[] = array('id' => 'company', 'text' => '公司总系统'
                        , 'description' => '针对生产企业、物流企业等各类企业内物流、生产、采购等业务模块进行ABC分析'
                        , 'image' => '1_large.jpg');           
        return $result;        
    }
    //只含有需要计算的系统
    static public function system_list_array()
    {
        $result = array();
        $result[] = array('id' => 'inventory'
                        , 'text' => '物流系统'
                        , 'description' => '针对物流业务相关环节进行设定并进行ABC分析'
                        , 'image' => '2_large.jpg');

        $result[] = array('id' => 'purchase', 'text' => '采购系统'
                        , 'description' => '针对采购环节采购订单量、供应商供货能力等影响采购业务的环节进行ABC分析'
                        , 'image' => '5_large.jpg');

        $result[] = array('id' => 'market','text' => '销售系统'
                        , 'description' => '面向销售环节的业务拆分并进行ABC分析'
                        , 'image' => '3_large.jpg');

        $result[] = array('id' => 'production', 'text' => '生产系统'
                        , 'description' => '针对生产中准备、各加工工序、检验、包装等业务环节进行设定并进行ABC分析'
                        , 'image' => '4_large.jpg');

        // $result[] = array('id' => 'company', 'text' => '公司总系统'
        //                 , 'description' => '针对生产企业、物流企业等各类企业内物流、生产、采购等业务模块进行ABC分析'
        //                 , 'image' => '1_large.jpg');       

        // $result[] = array('id' => 'document', 'text' => '文档演示'
        //                 , 'description' => ''
        //                 , 'image' => '6_large.jpg');        

        return $result;
    }
    static public function get_system_info_by_id($system_list, $system_id)
    {
        if (!empty($system_id)) {
            foreach ($system_list as $key => $value) {
                if($value['id'] == $system_id)
                {
                    return $value;
                }
            }
        }
        else
            return NULL;           
    }
    static public function get_system_info_by_id_directly($system_id)
    {
        return self::get_system_info_by_id(self::all_system_list_array(), $system_id);
    }    
    static public function set_result_json($status = 'ok', $text = '', $url = '')
    {
        return json_encode(array('status' => $status, 'text' => $text, 'url' => $url));
    }
	static public function get_user_data_set(){
        $user_data_set = $_SESSION['user_data_set'];
        // $user_data_set = 'user1';//测试用
        return $user_data_set;
    }
    static public function format_number_to_fload($str_number)
    {
        return doubleval(strtr($str_number, array(',' => '')));
    } 
    static public function get_system_id(){
        $system_id = $_SESSION['system_id'];
        // $system_id = 'inventory';//测试用  
        return $system_id;      
    }
    static function all_array_member_is_NULL($array)
    {
        if(count($array) <= 0) return true;
        foreach ($array as $key => $value) {
            if($value != NULL){
                return false;
            }
        }
        return true;
    }	
    static function get_query_result($sql)
    {
        if(empty($sql))
        {
            return NULL;
        }
        else
        {
            $MSelect = new Model();
            return $MSelect->query($sql);
        }
    }
    static function get_deault_secret()
    {
        return md5('111');
    }
    static function json_is_array($json)
    {
        $is_array = json_decode($json);
        if(is_array($is_array)){
            return true;
        }   
        return false;
    }    
    static function request_json($name){
        return json_decode(self::request($name), true);
    }
	static function request($name){
		$value = $_GET[$name];
		if($value == null){
			$value = $_POST[$name];
		}
		return $value;
	}
	static function writeJSON($obj){	
		
		if(is_string($obj)) {
			ob_end_clean();
			print_r($obj);
		}else {
			$json = json_encode($obj);		
			ob_end_clean();
			print_r($json);
		}
	}
	static function gbk2utf8($data){
		if(is_array($data)){
			return array_map('gbk2utf8', $data);
		}
		return iconv('gbk','utf-8',$data);
	}
	static function php_json_decode($str){
		$stripStr = stripslashes($str);
		$json = json_decode($stripStr,true);
		return $json;
	}
    static public function trans_sql_array($sql_array = array())
    {
        return self::trans_sql(implode('',$sql_array));

    }
    static public function trans_sql($sql = '')
    {
        $state = true;
        $M = new Model();
        $sqlArray = explode(';',$sql);
        if(count($sqlArray) > 1)
        {
            $M->startTrans();
            for($i = 0; $i < count($sqlArray) - 1; $i++)
            {
                if (!$M->execute($sqlArray[$i])) {
                    $state = false;
                    break;
                }
            }
            if($state)
            {
                $M->commit();
                return true;
            }
            else
            {
                $M->rollback();
                return false;
            }            
        }
        else
        {
            return $M->execute($sql);
        }

    }
    static public function randomColors($len = 2)
    {
        $colors = array();
        while(true)
        {
            $new_color = self::randomColor();
            if(array_search($new_color, $colors) == false)
            {
                $colors[] = $new_color;
            }
            if(count($colors) >= $len)            
            {
                break;
            }
        }
        // var_dump($colors);
        // echo json_encode($colors);
        return $colors;
    }
    static public function randomColor() {
        $str = '#';
        for($i = 0 ; $i < 6 ; $i++) {
            $randNum = rand(0 , 15);
            switch ($randNum) {
                case 10: $randNum = 'A'; break;
                case 11: $randNum = 'B'; break;
                case 12: $randNum = 'C'; break;
                case 13: $randNum = 'D'; break;
                case 14: $randNum = 'E'; break;
                case 15: $randNum = 'F'; break;
            }
            $str .= $randNum;
        }
        return $str;
    }
    //数组和字母表的映射 0->A 1->B  2->C ...
    static public function number_to_ABC_map($start_abc = 'z', $length = 3)
    {
        $abc_map = array();
        if(strlen($start_abc) > 2)
        {
            return $abc_map;
        }
        $start_abc = strtoupper($start_abc);
        $abc_map[0] = $start_abc;
        
        $a_temp = array();
        if(strlen($start_abc) > 1)
        {
            $first_ascii_value = ord(substr($star, 0, 1));
            $a_temp[0] = array('first' => $first_ascii_value, 
                                'second' => ord(substr($start_abc, 1, 1)));
        }
        else
        {
            $a_temp[0] = array('first' => ord('A') - 1, 
                    'second' => ord(substr($start_abc, 0, 1)));
        }

        if($length > 1)
        {
            for($i = 1; $i < $length; $i++)
            {
                if($a_temp[$i -1]['first'] == ord('Z') && $a_temp[$i -1]['second'] == ord('Z'))
                {
                    break;
                }
                $a_temp[$i]['first'] = $a_temp[$i - 1]['first'];
                $a_temp[$i]['second'] = $a_temp[$i - 1]['second'] + 1;
                if($a_temp[$i]['second'] > ord('Z'))
                {
                    $a_temp[$i]['first'] = $a_temp[$i]['first'] + 1;
                    $a_temp[$i]['second'] = ord('A');
                }
            }            
        }
        for($i = 1; $i < count($a_temp); $i++)
        {
            if($a_temp[$i]['first'] >= ord('A'))
            {
                $F = chr($a_temp[$i]['first']);
            }
            else
                $F = '';
            $abc_map[$i] = $F.chr($a_temp[$i]['second']);
        }
        return $abc_map;
    }
    static public function checkClientIE() {
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if (eregi("MSIE",$agent))
        {
          return true;
        }
        return false;
	}
	static public function checkUTF8($str) {
		$cod = mb_check_encoding($str,"UTF-8");
		if("UTF-8" != $cod ||  empty($cod))
		{
		  $str = mb_convert_encoding( $str,'utf-8','gb2312'); 
		}
		return $str;
	}
	static public function checkWindows() {
		if(eregi('WIN',PHP_OS))
		{
		  return true;
		}
		return false;
	}
	static public function checkGB2312($str) 
	{
		$cod = mb_check_encoding($str,"GB2312");
		if("GB2312" != $cod ||  empty($cod))
		{
		  $str = mb_convert_encoding( $str,'GB2312','UTF-8'); 
		}
		return $str;
	}

}

?>
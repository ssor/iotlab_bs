<?php
require_once('tools.php');
// 本文档自动生成，仅供测试运行
class IndexAction extends Action
{
    /**
    +----------------------------------------------------------
    * 默认操作
    +----------------------------------------------------------
    */
    public function index()
    {
        $_SESSION['user_data_set'] = '';
        // $this->display(THINK_PATH.'/Tpl/Autoindex/hello.html');

        $this->display();
    }
    public function menu()
    {
        $this->assign('user', Tools::get_user_data_set());
        $this->display();
    }
    public function UHFindex()
    {
        $this->assign('user', Tools::get_user_data_set());
        $this->assign('ws_host', C('WS_LOCATION'));
        $this->display();
    }
    public function light1_index()
    {
        $this->assign('ws_host', C('WS_LOCATION'));
        $this->assign('user', Tools::get_user_data_set());
        $this->display();

    }
    public function gps_index()
    {
        $this->assign('ws_host', C('WS_LOCATION'));
        $this->assign('user', Tools::get_user_data_set());
        $this->display();
    }
    public function checkLogin()
    {
        // {"user_name":"go","pwd":"111"}
        $json_return = Tools::set_result_json('ok', '', '/Index/menu');
        $json_str = Tools::request("data");
        $json = json_decode($json_str,true);
        $user_name = $json['user_name'];
        $password = $json['pwd'];
        $_SESSION['user_data_set']= $user_name;//默认使用以用户名为标记的数据
        $pwdMd5 = md5($password);
        $sql = "SELECT ACCOUNT, PASSWORD, REMARK,status 
                FROM THINK_USER  where 
                ACCOUNT = '$user_name';";
                // " AND PASSWORD = '$pwdMd5'";       
        $list = Tools::get_query_result($sql);
        if(count($list) <= 0){
            //该用户名还没有被使用
            //将用户名和密码存储，并转到主页面
            $sql_insert = "insert into THINK_USER(ACCOUNT, PASSWORD) values('$user_name', '$pwdMd5');";
            if(!Tools::trans_sql($sql_insert)){
                $json_return = Tools::set_result_json('failed', '保存您的信息时出现异常，请重试！');
            }
        }
        else{
            //该用户已存在，但是不确定是否为同一用户，需要检查密码
            if($list[0]['PASSWORD'] != $pwdMd5){
                //不为同一用户
                $json_return = Tools::set_result_json('failed', '您输入的密码错误，或者您使用了别人的名字');
            }
        } 
        echo $json_return;return;
    }    
    public function test()
    {
        $this->display();
    }

    /**
    +----------------------------------------------------------
    * 探针模式
    +----------------------------------------------------------
    */
    public function checkEnv()
    {
        load('pointer',THINK_PATH.'/Tpl/Autoindex');//载入探针函数
        $env_table = check_env();//根据当前函数获取当前环境
        echo $env_table;
    }

}
?>
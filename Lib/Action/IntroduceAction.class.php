<?php
require_once('tools.php');
// 本文档自动生成，仅供测试运行
class IntroduceAction extends Action
{
    /**
    +----------------------------------------------------------
    * 默认操作
    +----------------------------------------------------------
    */
// CREATE TABLE introduce_info(
//        name varchar(128) primary key
//        ,title varchar(128)
//        ,image_path varchar(128)
//        ,audio_path varchar(128)
//        ,description text
// );
    public function introduce_index()
    {
        $name_request = Tools::request('name');
        if(empty($name_request)){
            return;
        }
        $sql = "select name, title, image_path, audio_path, description from introduce_info where name = '$name_request';";
        $list = Tools::get_query_result($sql);
        if(count($list) > 0){
            $item = $list[0];
            $this->assign('title', $item['title']);
            $this->assign('image_path', $item['image_path']);
            $this->assign('audio_path', $item['audio_path']);
            $desces = explode('&&&',$item['description']);
            $this->assign('desces', $desces);
        }
        $this->display();
    }

}
?>
<?php 
	
    require_once '../DAL/stuDAL.php';
    require_once '../DAL/adminDAL.php';
    require_once '../jsonHelper.php';//引用中文字符转义
	
    //接收客户端账号 密码 身份
    $body = file_get_contents('php://input');
    $json = json_decode($body );
    $account = $json->{'account'};
    $pwd = $json->{'pwd'};
    $type = $json->{'type'};
    
    // $arr = array(
    //     'account' => $account,
    //     'pwd' => $pwd,
    //     'type' => $type
    // );

    //向DAL层传参
    if($type == "学生"){
        $stuDAL = new stuDAL();
        $result = $stuDAL->checkStuAccount($account,$pwd);
        //向微信小程序返回参数
	    echo toJson($result);
    }
    else if($type == "管理员"){
        $adminDAL = new adminDAL();
        $result = $adminDAL->checkAdminAccount($account,$pwd);
        //向微信小程序返回参数
	    echo toJson($result);
    }
	

	
	
?>
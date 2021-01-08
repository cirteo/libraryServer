<?php

require_once '../DAL/seatDAL.php';
require_once '../jsonHelper.php';
require_once '../seatHelper.php';
//引用中文字符转义

//接收数据
$body = file_get_contents( 'php://input' );
$json = json_decode( $body );
$stuNum = $json-> {
    'stuNum'}
    ;
    $seatID = $json-> {
        'seatID'}
        ;

        //向DAL层传参
        $seatDAL = new seatDAL();
        $seatHelper = new seatHelper();
        //查询座位是否可用
        $state = $seatDAL->querySeatAvailable( $seatID );
        if ( $state == 'true' ) {
            //座位可用
            //改变座位状态
            $seatDAL->reserveSeat( $seatID );
            //插入历史记录
            $seatDAL->addReserve( $stuNum, $seatID );
            $result = array(
                'state' => '0',
                'msg' => '选座成功，该座位已被锁定',
                'seatLoc' => $seatHelper->querySeatLoc($seatID)
            );
            echo toJson( $result );
        } else if ( $state == 'false' ) {
            //座位不可用
            $result = array(
                'state' => '1',
                'msg' => '选座失败，该座位已被占用'
            );
            echo toJson( $result );
        }

        ?>
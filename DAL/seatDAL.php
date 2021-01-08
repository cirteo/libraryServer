<?php
include ( '../conn.php' );

//连接数据库
require_once '../seatHelper.php';
//引用座位查询

class seatDAL {
    //向后台查询座位是否可用

    function querySeatAvailable( $seatID ) {

        $sql = "select seatState From seat where seatID = '$seatID'";
        $cnMysqli = mysqli_connect("localhost","root","root","library");
        $query = mysqli_query($cnMysqli, $sql );

        $row = mysqli_fetch_array( $query );
        if ( $row['seatState'] == '空闲' ) {
            return $state = 'true';
        } else {
            return $state = 'false';
        }

    }

    //更新座位状态为占用

    function reserveSeat( $seatID ) {

        $sql = "update seat set seatState = '已选' where seatID = '$seatID'";
        $cnMysqli = mysqli_connect("localhost","root","root","library");
        mysqli_query( $cnMysqli,$sql );

    }

    function reserveSeatRand() {

        $sql = "select seatID from seat where seatState = '空闲' order by rand() Limit 1";
        $cnMysqli = mysqli_connect("localhost","root","root","library");
        $query = mysqli_query($cnMysqli, $sql );

        $row = mysqli_fetch_array( $query );
        
        $seatID = $row['seatID'];
        return $seatID;
    }

    //更新座位状态为空闲

    function releaseSeat( $seatID ) {

        $sql = "update seat set seatState = '空闲' where seatID = '$seatID'";
        $sql2 = "delete from queue where seatID = '$seatID';";
        $cnMysqli = mysqli_connect("localhost","root","root","library");
        mysqli_query($cnMysqli, $sql );
        mysqli_query($cnMysqli, $sql2 );

    }

    //插入预约历史记录

    function addReserve( $stuNum, $seatID ) {
        $timeStamp = time();

        $sql1 = "insert into queue(stuNum,seatID,timeStamp,state) values('$stuNum','$seatID',$timeStamp,'预约') ;";
        $sql = "insert into history(stuNum,seatID,historyTime,record,isBreakRule) values('$stuNum','$seatID',now(),'预约',0);";
        $sql2 = "update student set stuSeated = '$seatID',stuState = '预约中' where stuNum = '$stuNum';";
        $cnMysqli = mysqli_connect("localhost","root","root","library");
        mysqli_query($cnMysqli, $sql1 );
        mysqli_query($cnMysqli, $sql2 );
        mysqli_query($cnMysqli, $sql );

    }

    //插入签到历史记录

    function addSignIn( $stuNum, $seatID ) {
        $timeStamp = time();

        $sql = "insert into history(stuNum,seatID,historyTime,record,isBreakRule) values('$stuNum','$seatID',now(),'签到',0)";
        $sql1 = "delete from queue where stuNum = '$stuNum'";
        $sql2 = "insert into queue(stuNum,seatID,timeStamp,state) values('$stuNum','$seatID',$timeStamp,'使用中') ;";
        $sql3 = "update student set stuState = '使用中' where stuNum = '$stuNum';";
        $cnMysqli = mysqli_connect("localhost","root","root","library");
        mysqli_query( $cnMysqli,$sql );
        mysqli_query( $cnMysqli,$sql1 );
        mysqli_query( $cnMysqli,$sql2 );
        mysqli_query( $cnMysqli,$sql3 );

    }

    //插入续座历史记录

    function addExtendTime( $stuNum, $seatID ) {
        $timeStamp = time();

        $sql = "insert into history(stuNum,seatID,historyTime,record,isBreakRule) values('$stuNum','$seatID',now(),'续座',0)";
        $sql1 = "delete from queue where stuNum = '$stuNum'";
        $sql2 = "insert into queue(stuNum,seatID,timeStamp,state) values('$stuNum','$seatID',$timeStamp,'使用中') ;";
        $cnMysqli = mysqli_connect("localhost","root","root","library");
        mysqli_query( $cnMysqli,$sql );
        mysqli_query( $cnMysqli,$sql1 );
        mysqli_query( $cnMysqli,$sql2 );

    }

    //插入签退历史记录

    function addSignOut( $stuNum, $seatID ) {

        $sql2 = "update student set stuSeated = '0',stuState = '空' where stuNum = '$stuNum'";
        $sql = "insert into history(stuNum,seatID,historyTime,record,isBreakRule) values('$stuNum','$seatID',now(),'签退',0)";
        $cnMysqli = mysqli_connect("localhost","root","root","library");
        mysqli_query( $cnMysqli,$sql );
        mysqli_query( $cnMysqli,$sql2 );
    }

    //编辑座位状态

    function editSeat( $seatID, $seatState ) {

        $sql = "update seat set seatState = '$seatState' where seatID = '$seatID'";
        $cnMysqli = mysqli_connect("localhost","root","root","library");
        mysqli_query( $cnMysqli,$sql );

        $result = array(
            'state' => '0',
            'msg' => '修改成功'
        );

        return $result;
    }

    //关闭图书馆/楼层/分区

    function closeLibrary( $seatID ) {
        $sql = "update seat set seatState = '停用' where seatID like('$seatID')";
        $cnMysqli = mysqli_connect("localhost","root","root","library");
        mysqli_query( $cnMysqli,$sql );

        $result = array(
            'state' => '0',
            'msg' => '关闭成功'
        );

        return $result;
    }

    function seatList($seatID){
        $cnMysqli = mysqli_connect("localhost:3306","root","root","library");
        $seatID = $seatID.'%';
        $sql = "select * from seat where seatID like('$seatID') order by id Desc";
        $query = mysqli_query($cnMysqli,$sql);

        $n = 0;
		$arr = array();
		while ($row = mysqli_fetch_assoc($query)) {	
            if($row['seatState'] == '空闲') {$row['seatState'] = "0";}
            else if($row['seatState'] == '停用') {$row['seatState'] = "0-3";}
            else if($row['seatState'] == '已选') {$row['seatState'] = "0-2";}
			$newarr = array(
                'type' => $row['seatState'],
				'id' => $row['id'],
				'row' => $row['row'],
			    'col' => $row['col'],
 			    'gRow' => (int)$row['gRow'],
			    'gCol' => (int)$row['gCol'],
				);
			array_push($arr,$newarr);
		}
		return $arr;
    }

    //插入学生取消订座记录
    function cancelReserve( $stuNum, $seatID ){
        $sql = "insert into history(stuNum,seatID,historyTime,record,isBreakRule) values('$stuNum','$seatID',now(),'取消',0)";
        $sql2 = "update student set stuSeated = '0',stuState = '空' where stuNum = '$stuNum'";
        $cnMysqli = mysqli_connect("localhost","root","root","library");
        mysqli_query( $cnMysqli,$sql );
        mysqli_query( $cnMysqli,$sql2 );
    }

}
?>
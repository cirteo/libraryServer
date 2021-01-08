<?php 
	include ("../conn.php");//连接数据库
	require_once '../seatHelper.php';//引用座位查询
	require_once '../jsonHelper.php';//引用中文字符转义
    
	class stuDAL {
 
		//查询某学生历史预约记录
		function queryHistory($stuNum){

			$seatHelper = new seatHelper();

			$sql = "select student.stuNum,stuName,stuScore,seatID,historyTime,record from student join history on student.stuNum = history.stuNum where student.stuNum = '$stuNum' and isBreakRule = 1";
			$cnMysqli = mysqli_connect("localhost","root","root","library");
			$query = mysqli_query($cnMysqli,$sql);
			
			$n = 0;
			$arr = array();
			while ($row = mysqli_fetch_array($query)) {
		
				$newarr = array('stuNum' => $row['stuNum'],
								   'stuName' => $row['stuName'],
								   'seatLoc' => $seatHelper->querySeatLoc($row['seatID']),
								   'historyTime' => $row['historyTime'],
								   'record' => $row['record'],
								   'stuScore' => $row['stuScore'],
									);
				array_push($arr,$newarr);
			}
			return $arr;
		
		}

		//检查学生账号
		function checkStuAccount($account,$pwd){

			$seatHelper = new seatHelper();

			$sql = "select stuNum,stuPwd,stuName,stuScore,stuSeated,stuState From student where stuNum = '$account'";
			$cnMysqli = mysqli_connect("localhost","root","root","library");
			$query = mysqli_query($cnMysqli,$sql);

			//抓取结果集中第一行
			$rs = mysqli_fetch_array($query);
			//如果有数据，则存在账号
			if(is_array($rs)){

				if($pwd== $rs['stuPwd']){
					$arr = array(
						'msg' => "学生账号密码均匹配",
						'state' => "0",
						'stuNum'  => $rs['stuNum'],
						'stuName'  => $rs['stuName'],
						'stuScore' => $rs['stuScore'],
						'stuSeated' => $rs['stuSeated'],
						'stuState' => $rs['stuState'],
						'stuSeatLoc' => $rs['stuSeated'] == "0" ? "" : $seatHelper->querySeatLoc($rs['stuSeated'])
					);
		
				}else{
					$arr = array(
						'msg' => "学生账号匹配，但密码不正确",
						'state' => "1"
					);
				}
		
			}else{//无数据，学号不存在
				$arr = array(
					'msg' => "学生账号密码均不匹配",
					'state' => "2"
				);//用户名不存在
			}

			return $arr;
		}


	}
?>
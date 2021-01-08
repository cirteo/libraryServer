<?php 
	include ("../conn.php");//连接数据库
	require_once '../seatHelper.php';//引用座位查询
	
    
	class adminDAL {
 
		//查询某学生历史预约记录
		function queryHistory($stuNum){
			
			$seatHelper = new seatHelper();
			
			$sql = "select student.stuNum,stuName,seatID,historyTime,record,isBreakRule from student join history on student.stuNum = history.stuNum where student.stuNum = '$stuNum'";
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
								   'isBreakRule' => $row['record'] == 1 ? "违规" : "正常"
									);
				array_push($arr,$newarr);
			}

			return $arr;
		}

		//检查管理员账号
		function checkAdminAccount($account,$pwd){

			$sql = "select adminNum,adminPwd,adminName From admin where adminNum = '$account'";
			$cnMysqli = mysqli_connect("localhost","root","root","library");
			$query = mysqli_query($cnMysqli,$sql);

			//抓取结果集中第一行
			$rs = mysqli_fetch_array($query);
			//如果有数据，则存在账号
			if(is_array($rs)){

				if($pwd== $rs['adminPwd']){ //管理员账号密码均匹配
					$arr = array(
						'msg' => "管理员账号密码均匹配",
						'state' => "0",
						'adminNum'  => $rs['adminNum'],
						'adminName'  => $rs['adminName']
					);
		
				}else{
					$arr = array(
						'msg' => "管理员账号匹配，但密码不正确",
						'state' => "1"
					);
				}
		
			}else{//无数据，管理员账号不存在
				$arr = array(
					'msg' => "管理员账号密码均不正确",
					'state' => "2"
				);//用户名不存在
			}

			return $arr;

		}

		//查询所有被关进小黑屋的学生
		function queryAllViolations(){

			$sql = "select stuName,stuNum,stuScore from student where stuScore = 0";
			$cnMysqli = mysqli_connect("localhost","root","root","library");
			$query = mysqli_query($cnMysqli,$sql);
			$n = 0;
			$arr = array();
			while ($row = mysqli_fetch_array($query)) {
		
				$newarr = array('stuNum' => $row['stuNum'],
								   'stuName' => $row['stuName'],
								   'stuScore' => $row['stuScore'],
									);
				array_push($arr,$newarr);
			}
			return $arr;
		}

		//放单个学生出小黑屋
		function setOut($stuNum){

			$sql = "update student set stuScore = 1 where stuNum = '$stuNum'";
			$cnMysqli = mysqli_connect("localhost","root","root","library");
			mysqli_query($cnMysqli,$sql);
			$result = array(
				'state' => '0',
				'msg' => '成功放出小黑屋，学生分数修改为1！'
			);
			return $result;
		}

		//查询单个违规学生
		function querySingleViolations($stuNum){
			$sql = "select stuNum,stuScore,stuName From student where stuNum = '$stuNum' and stuScore = 0";
			$cnMysqli = mysqli_connect("localhost","root","root","library");
			$query = mysqli_query($cnMysqli,$sql);

			//抓取结果集中第一行
			$rs = mysqli_fetch_array($query);
			//如果有数据，则学生分数为0
			if(is_array($rs)){
				$arr = array(
					'stuNum' => $rs['stuNum'],
					'stuScore' => $rs['stuScore'],
					'stuName' => $rs['stuName'],
					'state' => "0",
					'msg' => "查询到违规学生"
				);
			}else{
				$arr = array(
					'state' => "1",
					'msg' => "学生积分尚未归0"
				);
			}

			return $arr;
		}

		//重置所有学生分数为3
		function resetAllScore(){
			
			$sql = "update student set stuScore = 3";
			$cnMysqli = mysqli_connect("localhost","root","root","library");
			mysqli_query($cnMysqli,$sql);

			$arr = array(
				'state' => "0",
				'msg' => "已重置所有学生分数"
			);

			return $arr;
		}
	}
?>
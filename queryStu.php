<?php 
    include ("conn.php");//连接数据库

    //接收数据
    $body = file_get_contents('php://input');
    $json = json_decode($body );
	$cnMysqli = mysqli_connect("localhost","root","root","library");
    $stuNum = $json->{'stuNum'};

	$sql = "select * from  student where stuNum = '$stuNum'";
    $query = mysqli_query($cnMysqli,$sql);
    
	
	$n = 0;
	while ($row = mysqli_fetch_array($query)) {

		$arr[$n++] = array('stuNum' => $row['stuNum'],
			               'stuName' => $row['stuName'],
			               'stuScore' => $row['stuScore'],
							);
	}
		//输出字符串数组
	echo json_encode($arr);
?>
<?php

    require_once '../jsonHelper.php';
    $body = file_get_contents('php://input');
    $json = json_decode($body );
    $stuNum = $json->{'stuNum'};

    $cnMysqli = mysqli_connect("localhost:3306","root","root","library");
    $sql = "select * from queue where stuNum = '$stuNum'";
    $query = mysqli_query($cnMysqli,$sql);

		$arr = array();
		while ($row = mysqli_fetch_assoc($query)) {	
            $currentTime = time();
            $targetTime = 0;

            if($row['state'] == "预约"){
                $targetTime = $row['timeStamp'] + (60 * 25);
            }else if($row['state'] == "使用中"){
                $targetTime = $row['timeStamp'] + (60 * 120);
            }


            $min = intval(($targetTime - $currentTime)/60);
            $sec = ($targetTime - $currentTime)%60;

			$newarr = array(
                'min' => $min,
                'sec' => $sec    
            );
			array_push($arr,$newarr);
        }
        
        if($arr == null){
            $newarr = array(
                'min' => 0,
                'sec' => 0    
            );
            array_push($arr,$newarr);
        } 

        echo json_encode($arr);



?>
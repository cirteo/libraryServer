<?php

    
    require_once 'jsonHelper.php';

    echo "queueManage is started.";
    set_time_limit(0);

    while(true){

        $cnMysqli = mysqli_connect("localhost:3306","root","root","library");
        $sqlList = "select * from queue_manage";
        $query = mysqli_query($cnMysqli,$sqlList);

	    while ($row = mysqli_fetch_assoc($query)) {	

            $currentTime = time();

            //在关闭时间内
            if($currentTime > $row['startTime'] && $currentTime < $row['endTime']){

                $seatID = $row['seatID']."%";

                $sql = "update seat set seatState = '停用' where seatID like('$seatID')";
                mysqli_query($cnMysqli,$sql);

            }
            //超出预约时间
            if($currentTime > $row['endTime']){
                $id = $row['id'];
                $seatID = $row['seatID']."%";

                $sql = "update seat set seatState = '空闲' where seatID like('$seatID')";
                $sql1 = "delete from queue_manage where id = '$id'";
                mysqli_query($cnMysqli,$sql);
                mysqli_query($cnMysqli,$sql1);
            }
        }
        sleep(60 * 60 * 6);
    }

    

?>
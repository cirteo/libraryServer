<?php

    
    require_once 'jsonHelper.php';

    echo "queue is started.";

    set_time_limit(0);


    while(true){

        $cnMysqli = mysqli_connect("localhost:3306","root","root","library");
        $sqlList = "select * from queue";
        $query = mysqli_query($cnMysqli,$sqlList);

	    while ($row = mysqli_fetch_assoc($query)) {	

            $currentTime = time();
            $historyTime = $row['timeStamp'];

            $stuNum = $row['stuNum'];

            $sqlList1 = "select stuScore from student where stuNum = $stuNum";
            $query1 = mysqli_query($cnMysqli,$sqlList1);
            $row1 = mysqli_fetch_assoc($query1);
            $stuScore = (int)$row1['stuScore'];

            //超出预约时间
            if($row['state'] == "预约"){
                //超出预约时间
                if($currentTime - $historyTime > (60 * 25)){

                    $seatID = $row['seatID'];

                    if($stuScore == 3) {$stuScore = 2;}
                    else if($stuScore == 2) {$stuScore = 1;}
                    else if($stuScore == 1) {$stuScore = 0;}
                    else {$stuScore = 0;}
                    $id = $row['id'];
                    echo $stuScore;
                    $sql = "update seat set seatState = '空闲' where seatID = '$seatID';";
                    $sql1 = "insert into history(stuNum,seatID,historyTime,record,isBreakRule) values('$stuNum','$seatID',now(),'未签到',1);";
                    $sql2 = "delete from queue where id = $id;";
                    $sql3 = "update student set stuSeated = '0',stuScore = $stuScore,stuState = '空' where stuNum = '$stuNum';";

                    mysqli_query($cnMysqli,$sql);
                    mysqli_query($cnMysqli,$sql1);
                    mysqli_query($cnMysqli,$sql2);
                    mysqli_query($cnMysqli,$sql3);

                    $time1 = $currentTime - $historyTime;

                    $arr = array('msg'=>"学生".$stuNum."未签到，已违规");

                    //echo $arr;
                }
            }
            //超出预约时间
            if($row['state'] == "使用中"){
                //超出使用时间
                if($currentTime - $historyTime > (120 * 60)){
                    $seatID = $row['seatID'];
                    $stuNum = $row['stuNum'];
                    $stuScore = (int)$row['stuScore'];
                    if($stuScore == 3) $stuScore = 2;
                    else if($stuScore == 2) $stuScore = 1;
                    else if($stuScore == 1) $stuScore = 0;
                    $id = $row['id'];
                    $sql = "update seat set seatState = '空闲' where seatID = '$seatID';";
                    $sql1 = "insert into history(stuNum,seatID,historyTime,record,isBreakRule) values('$stuNum','$seatID',now(),'未续座/签退',1);";
                    $sql2 = "delete from queue where id = $id;";
                    $sql3 = "update student set stuSeated = '0',stuScore = $stuScore,stuState = '空' where stuNum = '$stuNum';";

                    mysqli_query($cnMysqli,$sql);
                    mysqli_query($cnMysqli,$sql1);
                    mysqli_query($cnMysqli,$sql2);
                    mysqli_query($cnMysqli,$sql3);

                    $time1 = $currentTime - $historyTime;

                    echo $time1;
                }
            }
        }
        sleep(60);
    }

?>
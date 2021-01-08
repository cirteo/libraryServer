<?php 
    
    include ("conn.php");//连接数据库
    $cnMysqli = mysqli_connect("localhost:3306","root","root","library");
    $i = 1; //楼层
    $j = 1; //排
    $k = 1; //坐

    while($i < 5){

        while($j < 5){

            while($k < 9){

                $seatID = "1".$i.$j.$k;
                
                $sql = "insert into seat(seatID,seatState) values('$seatID','空闲')";

                mysqli_query($cnMysqli,$sql);
                echo $seatID;
                $k += 1;

            }
            $k = 1;
            $j += 1;
        }

        $j = 1;
        $i += 1;
    }
?>
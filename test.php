
<?php

    require_once 'jsonHelper.php';
    require_once 'seatHelper.php';//引用座位查询

    $seatHelper = new seatHelper();
    
    echo toJson($seatHelper->querySeatLoc('1234'));

?>

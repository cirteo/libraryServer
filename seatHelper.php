<?php

    class seatHelper{
        function querySeatLoc($seatID){
            $strSeatLoc = "";

            //确定几期图书馆
            if(substr($seatID, 0, 1) == '1'){
                $strSeatLoc .= "一期图书馆 ";
            }else if(substr($seatID, 0, 1) == '2'){
                $strSeatLoc .= "二期图书馆 ";
            }else{
                $strSeatLoc .= "false";
            }

            //确定几楼
            $strSeatLoc .= substr($seatID, 1, 1)."F ";

            //确定几区
            $strSeatLoc .= substr($seatID, 2, 1)."区 ";

            //确定几排
            $strSeatLoc .= substr($seatID, 3, 1)."排";

            //确定几座
            $strSeatLoc .= substr($seatID, 4, 1)."坐";

            return $strSeatLoc;
        }

    }

?>
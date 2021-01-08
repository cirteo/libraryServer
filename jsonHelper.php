<?php 
function toJson($arr) {
    $ajax = ToUrlencode($arr);    
    $str_json = json_encode($ajax);
    return urldecode($str_json);     
}
function ToUrlencode($arr) {
    $temp = array();
    if (is_array($arr)) {
        foreach ($arr AS $key => $row) {
            //若key为中文，也需要进行urlencode处理
            $key = urlencode($key);
            if (is_array($row)) {
                $temp[$key] = ToUrlencode($row);
            } else {
                $temp[$key] = urlencode($row);
            }
        }
    } else {
        $temp = $arr;
    }
 
    return $temp;
}

?>
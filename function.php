<?php

function P($msg = ''){
    $time = date('y-m-d H:i:s',time());;
    echo "[$time]Log : $msg <br>";
}
function E($msg = ''){
    $time = date('y-m-d H:i:s',time());;
    echo "<p style='color:red'>[$time]Error : $msg </p>";
}
function Array2String($array){
    $result = 'Array( ';
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $tmp[] = "[$key] => ".Array2String($value);
        }else
        $tmp[] = "[$key] => $value";
    }
    return $result.implode(', ', $tmp).")";
}

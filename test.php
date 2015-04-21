<?php
include('src/Sign.php');
//use Entere\Sign;
$params = array('z'=>14,'d'=>12,'b'=>"",'a'=>1,'o'=>null);
$a = Sign::getSign($params,'entere');
print_r($a);
echo md5('a=1&d=12&z=14entere');
?>
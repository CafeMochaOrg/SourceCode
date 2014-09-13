<?php
/*
* To change this template, choose Tools | Templates
* and open the template in the editor.
*/
//session_start();
//header('content-type:image/jpeg');
////$string=rand(10,1000);
//$num1=rand(1,9); //Generate First number between 1 and 9
//$num2=rand(1,9); //Generate Second number between 1 and 9
//$arr=array('+','-','*');
//shuffle($arr);
//foreach($arr as $value)
//{
////$captcha_total=$num1.$value.$num2;
//if($value == '+')
//$captcha_total=$num1+$num2;
//if($value == '-')
//$captcha_total=$num1-$num2;
//if($value == '*')
//$captcha_total=$num1*$num2;
//
//$math = "$num1".$value."$num2"."=";
//}
//
//
//$_SESSION['cap_code']=$captcha_total;
//$image=imagecreate($width=100, $height=50);
//$black=imagecolorallocate($image,0,0,0);//background color
//$white=imagecolorallocate($image,255,255,255);//forground color
//imagestring($image,$fontsize=5,$x=5,$y=5,$math,$white);
//imagejpeg($image, $filename=null, $quality=100);
session_start();
header('content-type:image/jpeg');
//$string=rand(10,1000);
$symbols = array('2', '3', '4', '5', '6', '7', '8', '9', 'A', 'C', 'E', 'G', 'H', 'K', 'M', 'N', 'P', 'R', 'S', 'U', 'V', 'W', 'Z', 'Y', 'Z');
$captcha_word = '';
for ($i = 0; $i <= 4; $i++) {
$captcha_word .= $symbols[rand(0, 24)];
$_SESSION['captcha']=$captcha_word;
}


$image=imagecreate($width=100, $height=40);
$black=imagecolorallocate($image,0,0,0);//background color
$white=imagecolorallocate($image,255,255,255);//forground color
imagestring($image,$fontsize=10000,$x=32,$y=10,$captcha_word,$white);
imagejpeg($image, $filename=null, $quality=100);
?>

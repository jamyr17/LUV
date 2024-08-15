<?php

$to="josuegonza1702@gmail.com";
$subject="Nueva solicitud";
$message="Esta es la prueba definitiva";
$headers='From: josuegonza1702@gmail.com'."\\r\\n". 
'Reply-To: dexmix3004@gmail.com';

if(mail($to,$subject,$message,$headers)){
    echo "SIUUU";
}else{
    echo "NAH";
}
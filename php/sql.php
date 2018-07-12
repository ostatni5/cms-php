<?php
$dsn = 'mysql:dbname=cms_kania;localhost';
$user = 'root';
$password="";

try {
    $dbh = new PDO($dsn, $user, $password);
    $dbh -> query ('SET NAMES utf8');
    $dbh -> query ('SET CHARACTER_SET utf8_unicode_ci');
} 
catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
?>
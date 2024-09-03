<!-- Kết nối với database -->
<?php
if(!defined('_CODE')){
    die();
}
require_once ('config.php');

try {
    if (class_exists('PDO')) {
        $dsn = 'mysql:host=' . _HOST . ';dbname=' . _DB; 

        $options = [
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        $conn = new PDO($dsn, _USER, _PASS, $options);
        
    }
} catch (Exception $e) {
    
    die();
}

<?php

session_start();
require_once('config.php');
require_once('./includes/connect.php');

//Thư viện PHP mailer
require_once('./includes/phpmailer/Exception.php');
require_once('./includes/phpmailer/PHPMailer.php');
require_once('./includes/phpmailer/SMTP.php');

require_once('./includes/functions.php');
require_once('./includes/database.php');
require_once('./includes/session.php');


// setFlashData('hihi', 'cài đặt thành công');
// echo getFlashData("hihi");
// $subject= 'Tran Binh An test mail';
// $content= 'test thoi ma !!!!!';
// sendMail('tmh.dai04@gmail.com', $subject, $content);

$module = _MODULE;
$action = _ACTION;



if(!empty($_GET['module'])){
    if(is_string($_GET['module'])){
        $module = trim($_GET['module']);
    }
}

if(!empty($_GET['action'])){
    if(is_string($_GET['action'])){
        $action = trim($_GET['action']);
    }
}


$path = 'modules/'. $module . '/' . $action . '.php';

 
if(file_exists($path)){
    require_once($path);
}
else{
    require_once('modules/error/404.php');
}



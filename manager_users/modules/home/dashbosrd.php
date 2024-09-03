<?php
if(!defined('_CODE')){
    die();
}

$data=[
    'pageTitle' => 'Trang Dashbosrd'
];

layouts('header',$data);

//Kiểm tra trạng thái đăng nhập

if(!isLogin()){
    redirect('?module=auth&action=login');
}

?>

<h1>DSSSS</h1>

<?php
layouts('footer');

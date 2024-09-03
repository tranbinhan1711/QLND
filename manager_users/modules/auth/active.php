<!-- Kích hoạt tài khoản -->

<?php
if(!defined('_CODE')){
    die('Accsess disined...');
   
}

layouts('header_login');


$token = filter()['token'];

if(!empty($token)){
    //Truy vấn để kiểm tra token với database
    $tokenQuery = oneRow("SELECT id FROM users WHERE activeToken='$token'");

    if(!empty($tokenQuery)){
        $userId = $tokenQuery['id'];
        $dataUpdate = [
            'status' => 1,
            'activeToken' => null
        ];

        $updateStatus = update('users', $dataUpdate, "id=$userId");

        if($updateStatus){
            setFlashData('smg', 'Kích hoạt tài khoản thành công, Bạn có thế đăng nhập ngay bây giờ!!.');
            setFlashData('smg_type', 'success');
        }
        else{
            setFlashData('smg', 'Kích hoạt tài khoản không thành công, Vui lòng liên hệ quảng trị viên!!.');
            setFlashData('smg_type', 'danger');
        }
        redirect('?module=auth&action=login');
    }
    else{
        getSmg('Liên kết không tồn tại hoặc đã hết hạn!!', 'danger');
    }
}
?>

<?php
    layouts('footer_login');
?>

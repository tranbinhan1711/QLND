
<?php
if(!defined('_CODE')){
    die('Accsess disined...');
   
}

//Kiểm tra id trong database => tồn tại => tiền hành xóa
//Xóa dữ liệu bảng logintoken => xóa bảng user (vì khóa ngoại) 

$filterAll = filter();
if(!empty($filterAll['id'])){
    $userId = $filterAll['id'];

    $userDetail = getRows("SELECT * FROM users WHERE id = '$userId'");
    if($userId > 0){
        //Thực hiện xóa
        $deleteToken =delete('tokenlogin', "user_id = $userId");

        if($deleteToken){
            //Xóa user
            $deleteUser = delete('users', "id = $userId");
            if($deleteUser){
                setFlashData('msg', 'Xóa người dùng thành công');
                setFlashData('msg_type', 'success');
            }
            else{
                setFlashData('msg', 'Lỗi hệ thống!');
                setFlashData('msg_type', 'danger');
            }
        }

    }
    else{
        setFlashData('msg', 'Người dùng không tồn tại trong hệ thống');
        setFlashData('msg_type', 'danger');
    }
}
else{
    setFlashData('msg', 'Liên Kết Không Tồn Tại');
    setFlashData('msg_type', 'danger');

}

redirect('?module=users&action=list'); 

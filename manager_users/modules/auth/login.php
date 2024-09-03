<?php
if (!defined('_CODE')) {
    die('Accsess disined...');

}
$data = [
    'pageTitle' => 'Đăng Nhập tài khoản'
];

// $kq =filter();
// echo '<pre>';
// print_r($kq);
// echo'</pre>';

// $password = '12345';
// // $md5 = md5($password);
// // $sha1 = sha1($password);

// $passwordHash = password_hash($password, PASSWORD_DEFAULT);
// $checkPass = password_verify('12345', $passwordHash); 
// var_dump($checkPass);

layouts('header_login', $data);

//Kiểm tra trạng thái đăng nhập


if (isLogin()) {
    redirect('?module=home&action=dashbosrd');
}


if (isPost()) {
    $filterAll = filter();

    if (!empty(trim($filterAll['email'])) && !empty(trim($filterAll['password']))) {
        //Kiểm tra đăng nhập
        $email = $filterAll['email'];
        $password = $filterAll['password'];

        //Truy vấn lấy thông tin users theo email
        $userQuery = oneRow("SELECT password, id FROM users WHERE email = '$email'");

        if (!empty($userQuery)) {
            $passwordHash = $userQuery['password'];
            $userId = $userQuery['id'];
            if (password_verify($password, $passwordHash)) {

                //Kiểm tra xem tài khoản đã đăng nhập chưa

                $userLogin = getRows("SELECT * FROM tokenlogin WHERE user_id = '$userId'");
                if ($userLogin > 0) {
                    setFlashData('smg', 'Tài khoản đang đăng nhập ở 1 nơi khác!!.');
                    setFlashData('smg_type', 'danger');

                    redirect('?module=auth&action=login');
                } else {
                    //Tạo token login
                    $tokenLogin = sha1(uniqid() . time());

                    //Insert vào bảng login
                    $dataToken = [
                        'user_Id' => $userId,
                        'token' => $tokenLogin,
                        'create_at' => date('Y-m-d H:i:s')
                    ];

                    $insertStatus = insert('tokenlogin', $dataToken);
                    if ($insertStatus) {
                        //Insert thành công

                        //Lưu token vào session
                        setSession('tokenlogin', $tokenLogin);

                        redirect('?module=home&action=dashbosrd');
                    } else {
                        setFlashData('smg', 'Không thể đăng nhập, Vui lòng thử lại sau!!.');
                        setFlashData('smg_type', 'danger');
                    }
                }



            } else {
                setFlashData('smg', 'Mật khẩu bạn nhập không đúng!!.');
                setFlashData('smg_type', 'danger');
            }
        } else {
            setFlashData('smg', 'Email không tồn tại!!.');
            setFlashData('smg_type', 'danger');
        }

    } else {
        setFlashData('smg', 'Vui lòng nhập email và mật khẩu!!.');
        setFlashData('smg_type', 'danger');
    }
    redirect('?module=auth&action=login');

}

$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');

?>

<div class="row">
    <div class="col-4" style="margin: 50px auto; ">
        <h2 class="text-center text-uppercase">Đăng nhập quản lí Users</h2>
        <?php
        if (!empty($smg)) {
            getSmg($smg, $smg_type);
        }
        ?>
        <form action="" method="post">
            <div class="form-group mg-form">
                <label for="">Email</label>
                <input name="email" type="email" class="form-control" placeholder="Địa chỉ email">
            </div>
            <div class="form-group mg-form">
                <label for="">Password</label>
                <input name="password" type="password" class="form-control" placeholder="Mật khẩu">
            </div>
            <button type="submit" class="  mg-btn btn btn-primary btn-block">Đăng nhập</button>
            <hr>
            <p class="text-center"><a href="?module=auth&action=forgot">Quên mật khẩu</a></p>
            <p class="text-center"><a href="?module=auth&action=register">Đăng ký tài khoản</a></p>
        </form>
    </div>
</div>

<?php

layouts('footer_login')
    ?>
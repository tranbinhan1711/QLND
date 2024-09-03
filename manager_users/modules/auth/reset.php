<?php
if (!defined('_CODE')) {
    die('Accsess disined...');
}

layouts('header_login');

// Kiểm tra xem token có tồn tại trong mảng dữ liệu không
$token = isset(filter()['token']) ? filter()['token'] : '';

if (!empty($token)) {
    // Truy vấn database kiểm tra token
    $tokenQuery = oneRow("SELECT id, fullname, email FROM users WHERE forgotToken ='$token'");

    if (!empty($tokenQuery)) {
        $userId = $tokenQuery['id'];
        if (isPost()) {
            $filterAll = filter();
            $errors = [];   

            // Validate password: bắt buộc phải nhập, có >= 8 ký tự
            if (empty($filterAll['password'])) {
                $errors['password']['require'] = 'Mật Khẩu bắt buộc phải nhập.';
            } else {
                if (strlen($filterAll['password']) < 8) {
                    $errors['password']['min'] = 'Mật Khẩu phải có ít nhất 8 kí tự.';
                }
            }

            // Validate password_confirm: bắt buộc phải nhập, phải trùng khớp với password
            if (empty($filterAll['password_confirm'])) {
                $errors['password_confirm']['require'] = 'Bạn phải nhập lại mật khẩu.';
            } else {
                if ($filterAll['password_confirm'] != $filterAll['password']) {
                    $errors['password_confirm']['match'] = 'Bạn nhập không trùng mật khẩu.';
                }
            }

            if (empty($errors)) {
                //Xử lý việc updata
                $passwordHash = password_hash($filterAll['password'],PASSWORD_DEFAULT);
                $dataUpdate =[
                    'password' => $passwordHash,
                    'forgotToken' => null,
                    'update_at' => date('Y-m-d H:i:s')
                ];

                $updateStatus =update('users', $dataUpdate, "id=$userId");
                if($updateStatus){
                    setFlashData('smg', 'Đổi mật khẩu thành công!!');
                    setFlashData('smg_type', 'success');
                    redirect('?module=auth&action=login');
                }
                else{
                    setFlashData('smg', 'Lỗi hệ thống vui lòng thử lại sau!!');
                    setFlashData('smg_type', 'danger');
                }
            } else {
                setFlashData('smg', 'Vui lòng kiểm tra lại dữ liệu!!');
                setFlashData('smg_type', 'danger');
                setFlashData('errors', $errors);
                redirect('?module=auth&action=reset&token='.$forgotToken);
            }
        }
        
        $smg = getFlashData('smg'); 
        $smg_type = getFlashData('smg_type');
        $errors = getFlashData('errors');
?>
        <!-- Form đặt lại mật khẩu -->
        <div class="row">
            <div class="col-4" style="margin: 50px auto; ">
                <h2 class="text-center text-uppercase">Đặt Lại Mật Khẩu</h2>
                <?php
                if (!empty($smg)) {
                    getSmg($smg, $smg_type);
                }
                ?>
                <form action="" method="post">
                    <div class="form-group mg-form">
                        <label for="">Password</label>
                        <input name="password" type="password" class="form-control" placeholder="Mật khẩu">
                        <?php
                        echo form_errors('password', '<span class = " errors">', '</span>', $errors);
                        ?>
                    </div>
                    <div class="form-group mg-form">
                        <label for="">Nhập lại Password</label>
                        <input name="password_confirm" type="password" class="form-control" placeholder="Nhập lại mật khẩu">
                        <?php
                        echo form_errors('password_confirm', '<span class = " errors">', '</span>', $errors);
                        ?>
                    </div>
                    <input type="hidden" name="token" value="<?php echo $token ?>">
                    <button type="submit" class=" mg-btn btn btn-primary btn-block">Gửi</button>
                    <hr>
                    <p class="text-center"><a href="?module=auth&action=login">Đăng Nhập tài khoản</a></p>
                </form>
            </div>
        </div>
<?php
    } else {
        getSmg('Liên kết không tồn tại hoặc đã hết hạn!!!','danger');
    }
}
?>

<?php
layouts('footer_login');
?>

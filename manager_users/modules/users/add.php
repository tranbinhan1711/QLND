<?php
if (!defined('_CODE')) {
    die('Accsess disined...');

}
$data = [
    'pageTitle' => 'Đăng kí tài khoản'
];

if (isPost()) {
    $filterAll = filter();
    // echo '<pre>';
    // print_r($filterAll);
    // echo '</pre>';
    $errors = []; // mảng chứa các lỗi

    //Validate fullname:  họ tên bắt buộc nhập, phải nhiều hơn 5 kí tự
    if (empty($filterAll['fullname'])) {
        $errors['fullname']['require'] = 'Họ tên bắt buộc phải nhập.';
    } else {
        if (strlen($filterAll['fullname']) < 5) {
            $errors['fullname']['min'] = 'Họ tên phải có ít nhất 5 kí tự.';
        }
    }

    //Validate email:  Email bắt buộc nhập, phải đúng định dạng, đã tồn tại trong csdl chưa
    if (empty($filterAll['email'])) {
        $errors['email']['require'] = 'Email bắt buộc phải nhập';
    } else {
        $email = trim($filterAll['email']);
        $sql = "SELECT id FROM users WHERE email = '$email'";
        $result = getRow($sql);

        // Kiểm tra nếu kết quả không rỗng, nghĩa là email đã tồn tại
        if (!empty($result)) {
            $errors['email']['unique'] = 'Email đã tồn tại.';
        }
    }


    //Validate Số điện thoại : bắt buộc phải nhập, có đúng định dạng không

    if (empty($filterAll['phone'])) {
        $errors['phone']['require'] = 'Số điện thoại bắt buộc phải nhập';
    } else {
        if (!isPhone($filterAll['phone'])) {
            $errors['phone']['isPhone'] = 'Số điện thoại không hợp lệ';
        }
    }

    //Validate pasword: bắt buộc phải nhập, có >= 8 ký tự
    if (empty($filterAll['password'])) {
        $errors['password']['require'] = 'Mật Khẩu bắt buộc phải nhập.';
    } else {
        if (strlen($filterAll['password']) < 8) {
            $errors['password']['min'] = 'Mật Khẩu phải có ít nhất hoặc 8 kí tự.';
        }
    }

    //Validate password_confirm: bắt buộc phải nhập, bằng với passworb
    if (empty($filterAll['password_confirm'])) {
        $errors['password_confirm']['require'] = 'Bạn phải nhập lại mặt khẩu.';
    } else {
        if ($filterAll['password_confirm'] != $filterAll['password']) {
            $errors['password_confirm']['match'] = 'Bạn nhập không trùng mật khẩu.';
        }
    }

    if (empty($errors)) {
        //xử lý insert
        $dataInsert = [
            'fullname' => $filterAll['fullname'],
            'email' => $filterAll['email'],
            'phone' => $filterAll['phone'],
            'password' => password_hash($filterAll['password'], PASSWORD_DEFAULT),
            'status' => $filterAll['status'],
            'create_at' => date('Y-m-d H:i:s')
        ];

        $insertStatus = insert('users', $dataInsert);
        if ($insertStatus) {
            setFlashData('smg', ' Thêm người dùng mới thành công!!');
            setFlashData('smg_type', 'success');
            redirect('?module=users&action=list');

        } else {
            setFlashData('smg', 'Hệ thống đang lỗi!!');
            setFlashData('smg_type', 'danger');
            redirect('?module=users&action=add');

        }

    } else {
        setFlashData('smg', 'Vui lòng kiểm tra lại dữ liệu!!');
        setFlashData('smg_type', 'danger');
        setFlashData('errors', $errors);
        setFlashData('old', $filterAll);
        redirect('?module=users&action=add');
    }

    //     echo '<pre>';
//     print_r($errors);
//     echo '</pre>';
}


layouts('header_login', $data);

$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');

?>

<div class="container">
    <div class="row" style="margin: 50px auto; ">
        <h2 class="text-center text-uppercase">Thêm Người Dùng</h2>
        <?php
        if (!empty($smg)) {
            getSmg($smg, $smg_type);
        }
        ?>
        <form action="" method="post">
            <div class="row">
                <div class="col">
                    <div class="form-group mg-form">
                        <label for="">Họ Tên</label>
                        <input name="fullname" type="text" class="form-control" placeholder="Nhập họ tên" value="<?php

                        echo oldData('fullname', $old);
                        ?>">
                        <?php
                        echo form_errors('fullname', '<span class = " errors">', '</span>', $errors);
                        ?>
                    </div>
                    <div class="form-group mg-form">
                        <label for="">Email</label>
                        <input name="email" type="email" class="form-control" placeholder="Địa chỉ email" value="<?php
                        echo oldData('email', $old);
                        ?>">
                        <?php
                        echo form_errors('email', '<span class = " errors">', '</span>', $errors);
                        ?>
                    </div>
                    <div class="form-group mg-form">
                        <label for="">Số điện thoại</label>
                        <input name="phone" type="+tel" class="form-control" placeholder="Nhập số điện thoại" value="<?php
                        echo oldData('phone', $old);
                        ?>">
                        <?php
                        echo form_errors('phone', '<span class = " errors">', '</span>', $errors);
                        ?>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group mg-form">
                        <label for="">Password</label>
                        <input name="password" type="password" class="form-control" placeholder="Mật khẩu">
                        <?php
                        echo form_errors('password', '<span class = " errors">', '</span>', $errors);
                        ?>
                    </div>
                    <div class="form-group mg-form">
                        <label for="">Nhập lại Password</label>
                        <input name="password_confirm" type="password" class="form-control"
                            placeholder="Nhập lại mật khẩu">
                        <?php
                        echo form_errors('password_confirm', '<span class = " errors">', '</span>', $errors);
                        ?>
                    </div>
                    <div class="form-group">
                        <label for="">Trạng thái</label>
                        <select name="status" id="" class="form-control">
                            <option value="0" <?php echo (oldData('status', $old) == 0) ? 'selected' : false ?>>Chưa kích hoạt</option>
                            <option value="1" <?php echo (oldData('status', $old) == 1) ? 'selected' : false ?>>Đã kích hoạt</option>
                        </select>
                    </div>
                </div>
            </div>



            <button type="submit" class=" btn-user btn btn-primary btn-block">Thêm người dùng</button>
           
            <a href="?module=users&action=list" class=" mg-btn btn btn-success btn-block" >Quay lại</a>

            <hr>
        </form>
    </div>
</div>

<?php

layouts('footer_login')
    ?>
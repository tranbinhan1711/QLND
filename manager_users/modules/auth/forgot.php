<?php
if(!defined('_CODE')){
    die('Accsess disined...');
   
}

$data = [
    'pageTitle' => 'Quên mật khẩu '
];

layouts('header_login', $data);

//Kiểm tra trạng thái đăng nhập


if(isLogin()){
    redirect('?module=home&action=dashbosrd');
}


if(isPost()){
    $filterAll = filter();
    if(!empty($filterAll['email'])){
        $email = $filterAll['email'];

        $queryUser = oneRow("SELECT id FROM users WHERE email= '$email'");

        if(!empty($queryUser)){
            $userId = $queryUser['id'];

            //Tạo forgot token
            $forgotToken = sha1(uniqid().time());

            $dataUpdate = [
                'forgotToken' => $forgotToken
            ];

            $insertStatus = update('users', $dataUpdate, "id=$userId");
            if($insertStatus){
                //Tạo link khôi phục 
                $linkReset = _WEB_HOST.'?module=auth&action=reset&token='.$forgotToken;
                $subject = 'Khôi phục mật khẩu';
                $content = 'Chào bạn'.'<br>';
                $content .= 'Chúng tôi nhận được yêu cầu khôi phục mật khẩu từ bạn.
                Vui lòng Click vào link để thay đổi đổi mật khẩu của bạn: ';
                $content .= $linkReset.'<br>';
                $content .= 'Xin trân trọng cảm ơn';

                $sendMail = sendMail($email,$subject,$content);
                if($sendMail){
                    setFlashData('smg','Vui lòng kiểm tra Email để xem hướng dẫn đặt lại mật khẩu!!!');
                    setFlashData('smg_type','success');
                }
                else{
                    setFlashData('smg','Lỗi hệ thống, Vui lòng thử lại sau(email)!!!');
                    setFlashData('smg_type','danger');
                }
            }
            else{
                setFlashData('smg','Lỗi hệ thống, Vui lòng thử lại sau!!!');
                setFlashData('smg_type','danger');
            }



        }
        else{
            setFlashData('smg','Email không tồn tại trong hệ thống!!!');
            setFlashData('smg_type','danger');
        }
    }
    else{
        setFlashData('smg', 'Bắt buộc phải nhập Email!!!');
        setFlashData('smg_type', 'danger');
    }

}

$smg= getFlashData('smg');
$smg_type=getFlashData('smg_type');

?>

<div class="row">
    <div class="col-4" style="margin: 50px auto; ">
        <h2 class="text-center text-uppercase">Quên Mật Khẩu</h2>
        <?php
            if(!empty($smg)){
                getSmg($smg, $smg_type);
            }
        ?>
        <form action="" method="post">
            <div class="form-group mg-form">
                <label for="">Email</label>
                <input name="email" type="email" class="form-control" placeholder="Địa chỉ email">
            </div>
            
            <button type="submit" class=" mg-btn btn btn-primary btn-block">Gửi</button>
            <hr>
            <p class="text-center"><a href="?module=auth&action=login">Đăng nhập</a></p>
            <p class="text-center"><a href="?module=auth&action=register">Đăng ký tài khoản</a></p>
        </form>
    </div>
</div>

<?php
    
layouts('footer_login');
?>
<?php
if(!defined('_CODE')){
    die('Accsess disined...');
   
}

if(isLogin()){
    $token = getSession('tokenlogin');
    delete('tokenlogin', "token = :token", [':token' => $token]);
    removeSession('tokenlogin');
    redirect('?module=auth&action=login');
}    
<!-- Các hằng của project -->

<?php

const _MODULE= 'home';
const _ACTION = 'dashbosrd';

const _CODE= true;

//Thiết lập host
define('_WEB_HOST', 'http://'. $_SERVER['HTTP_HOST']. '/QLND_PHP/manager_users');
define('_WEB_HOST_TEMPLATES',_WEB_HOST .'/templates');


//Thiết lập path

define('_WEB_PATH', __DIR__);
define('_WEB_PATH_TEMPLATES', _WEB_PATH . '/templates' );

//Thông tin kết nối
const _HOST = 'localhost';
const _DB = 'binhan_123';
const _USER = 'root';
const _PASS = '';
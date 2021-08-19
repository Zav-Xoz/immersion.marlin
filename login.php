<?php
session_start();
require "function.php";

$email = $_POST['email'];
$password = $_POST['password'];

$user = get_user_by_email($email);

if(empty($user)){
    set_flash_message('danger', "Данного пользователя не существует! Зарегистрируйтесь!");
    redirect_to("page_login.php");
}

login($email, $password);


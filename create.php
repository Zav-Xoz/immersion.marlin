<?php
session_start();
require "function.php";

$email = $_POST['email'];
$password = $_POST['password'];
$username = $_POST['username'];
$position = $_POST['position'];
$tel = $_POST['tel'];
$address = $_POST['address'];
//status
$statusStr = $_POST['status'];
//avatar
$avatar = $_FILES['avatar'];
//social
$vk = $_POST['vk'];
$instagram = $_POST['instagram'];
$telegram = $_POST['telegram'];

$user = get_user_by_email($email);

if (!empty($user)) {
    set_flash_message('danger', "Этот эл. адрес уже занят другим пользователем.");
    redirect_to("create_user.php");
}

$lastInsertId = add_user($email, $password);
add_general_information($lastInsertId, $username, $position, $tel, $address);
set_status($lastInsertId, $statusStr);

if (avatar_security($avatar)) upload_avatar($lastInsertId, $avatar);

ad_social_linc($lastInsertId, $telegram, $instagram, $vk);

set_flash_message("success", "Пользователь успешно добавлен Администратором!");
redirect_to("users.php");






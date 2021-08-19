<?php
session_start();
require "function.php";
$user_session = $_SESSION["user"];
$edit_user_arr = get_user_by_email($user_session);
$lastInsertId =$edit_user_arr['id'];

$username = $_POST['username'];
$position = $_POST['position'];
$tel = $_POST['tel'];
$address = $_POST['address'];
//var_dump($edit_user_arr['id']); exit;
add_general_information($lastInsertId, $username, $position, $tel, $address);

set_flash_message('success', "Профиль Отредактирован!");
redirect_to("page_profile.php");

<?php
session_start();
require 'function.php';

$user_edit_id = $_SESSION['edit_id']; //    получаем id юзера которого редактируем
$user_edit_email = $_SESSION['edit_email']; //  старый емейл пользователя которого редактируем

$email = $_POST['email'];
$password = md5($_POST['password']);

$user_for_condition = get_user_by_email($email);
if ($user_for_condition && $user_for_condition['email'] !== $user_edit_email ) {      // если функ находит юзера по емайл  - то есть такой емаил в БД
    set_flash_message('danger', "Email НЕ Свободен!!!");
    redirect_to("security.php");
}

edit_credentials($user_edit_id, $email, $password);

set_flash_message('success', "Email Пароль успешно ОБНОВЛЕН!!!");
redirect_to("page_profile.php");
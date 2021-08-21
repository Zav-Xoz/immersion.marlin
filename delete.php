<?php
session_start();
require "function.php";

$user_session = $_SESSION["user"];              // получаем юзера авторизованного
$user_edit_id = $_GET['id'];                    //  получаем юзера от перехода по ссылке
$_SESSION['edit_id'] = $user_edit_id;           // юзера id в сессию
$output = get_email_by_id($user_edit_id);       // email редактируемого
$edit_user_arr = get_user_by_email($output);    //получаем все данные редактируемого


is_not_logged_in($user_session);                // Авторизирован ???

if (!is_admin($user_session)) {                // Не  АДМИН ???

    if ($edit_user_arr['id'] != $user_edit_id) {
        set_flash_message('danger', "Можно редактировать только свой профиль!");
        redirect_to("page_login.php");
    }
}

delete($user_edit_id);

if($user_session == $output){                                          // если удалил себя и не админ
    redirect_to("exit.php");
}elseif ($user_session !== $user_edit_id && is_admin($user_session)){  // если админ удалил не себя
        set_flash_message('success', "Пользователь удачно удален!");
    redirect_to("users.php");
}else{
    echo "ОШИБКА НЕ РАЗУ НЕ ПОЛУЧИЛАСЬ НА ТЕСТАХ";
}


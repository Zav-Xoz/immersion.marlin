<?php
session_start();
require "function.php";

$user_edit_id = $_SESSION['edit_id']; //    получаем id юзера которого редактируем

$avatar = $_FILES['avatar'];

if (avatar_security($avatar))  upload_avatar($user_edit_id, $avatar);

set_flash_message('success', "AVATAR успешно ОБНОВЛЕН!!!");
redirect_to("page_profile.php");
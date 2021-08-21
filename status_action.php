<?php
session_start();
require "function.php";

$user_edit_id = $_SESSION['edit_id']; //    получаем id юзера которого редактируем
get_email_by_id($user_edit_id);
$select = $_POST['select'];

set_new_status($user_edit_id, $select);

set_flash_message('success', "STATUS успешно ОБНОВЛЕН!!!");
redirect_to("page_profile.php");
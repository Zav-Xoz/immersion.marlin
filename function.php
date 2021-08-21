<?php

function get_user_by_email($email)
{
    $pdo = new PDO("mysql:host=localhost;dbname=my_project", "root", "root");
    $sql = "SELECT * FROM users WHERE email=:email";

    $statement = $pdo->prepare($sql);
    $statement->execute(["email" => $email]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);
    return $user;
}

function get_email_by_id($id)
{
    $pdo = new PDO("mysql:host=localhost;dbname=my_project", "root", "root");
    $sql = "SELECT * FROM users WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute(["id" => $id]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);
    return $user['email'];
}

function set_flash_message($name, $message)
{
    $_SESSION["$name"] = $message;
}

function redirect_to($path)
{
    header("Location: {$path}");
    exit;
}

function add_user($email, $password)
{
    $pdo = new PDO("mysql:host=localhost;dbname=my_project", "root", "root");
    $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
    $statement = $pdo->prepare($sql);

    $result = $statement->execute([
        "email" => $email,
        "password" => password_hash($password, PASSWORD_DEFAULT)
    ]);

    return $pdo->lastInsertId();
}

function display_flash_message($name)
{
    if (isset($_SESSION[$name])) {
        echo "<div class=\"alert alert-{$name} text-dark\" role=\"alert\">{$_SESSION[$name]}</div>";
        unset($_SESSION[$name]);
    }
}

function login($email, $password)
{

    $pdo = new PDO("mysql:host=localhost;dbname=my_project", "root", "root");
    $sql = "SELECT * FROM users WHERE email=:email";

    $statement = $pdo->prepare($sql);
    $statement->execute(["email" => $email]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);
    if ($user['email'] == $email && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $email;
        redirect_to("users.php");

    } else {
        set_flash_message('danger', "Пароль или Логин не совпадают!");
        $_SESSION['user'] = false;
        redirect_to("page_login.php");
    }
}

function is_not_logged_in($session)
{
    if (!isset($session) && empty($session)) {
        set_flash_message('danger', "Данный пользователь не Авторизирован!");
        redirect_to("page_login.php");
    }
}

function is_admin($email)
{
    $pdo = new PDO("mysql:host=localhost;dbname=my_project", "root", "root");
    $sql = "SELECT admin FROM users WHERE email=:email";

    $statement = $pdo->prepare($sql);

    $statement->execute(["email" => $email]);
    $admin = $statement->fetch(PDO::FETCH_ASSOC);
    if ($admin['admin'] == 'admin') {
        return true;
    } else {
        return false;
    }
}

function select_all_users()
{
    $pdo = new PDO("mysql:host=localhost;dbname=my_project", "root", "root");
    $sql = "SELECT * FROM users";
    $statement = $pdo->prepare($sql);
    $statement->execute();
    $users = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $users;
}

function this_is_my_id($userMail)
{
    $pdo = new PDO("mysql:host=localhost;dbname=my_project", "root", "root");
    $sql = "SELECT id FROM users WHERE email=:email";
    $statement = $pdo->prepare($sql);
    $statement->execute(["email" => $userMail]);
    $id = $statement->fetch(PDO::FETCH_ASSOC);
    return $id;
}

function add_general_information($lastInsertId, $username, $position, $tel, $address)
{
    $pdo = new PDO("mysql:host=localhost;dbname=my_project", "root", "root");
    $sql = "UPDATE `users` SET name =:username , position=:position, tel =:tel, address=:address   WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $result = $statement->execute([
        "username" => $username,
        "position" => $position,
        "tel" => $tel,
        "address" => $address,
        "id" => $lastInsertId
    ]);
}

function set_status($lastInsertId, $statusStr)
{
    $status = "danger";
    if ($statusStr == "Онлайн") {
        $status = "success";
    } elseif ($statusStr == "Отошел") {
        $status = 'warning';
    }
    $pdo = new PDO("mysql:host=localhost;dbname=my_project", "root", "root");
    $sql = "UPDATE `users` SET status =:status  WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $result = $statement->execute([
        "status" => $status,
        "id" => $lastInsertId
    ]);
}

function avatar_security($avatar)
{
    $avatarName = $avatar['name'];
    $avatarType = $avatar['type'];
    $avatarSize = $avatar['size'];

    $blackList = array('.php', '.js', '.html');
    foreach ($blackList as $row) {
        if (preg_match("/$row\$/i", $avatarName)) return false;
    }
    if (($avatarType != "image/jpeg") && ($avatarType != "image/jpg") && ($avatarType != "image/png")) return false;
    if ($avatarSize > 5 * 1024 * 1204) return false;
    return true;
}

function upload_avatar($lastInsertId, $avatar)
{

    $avatarName = $avatar['name'];
    $avatarType = $avatar['type'];
    $upload_dir = 'img/demo/avatars/';

    $mayAvatarName = md5(microtime()) . '.' . substr($avatarType, strlen("image/"));

    $uploadFile = $upload_dir . $mayAvatarName;
    if (move_uploaded_file($avatar['tmp_name'], $uploadFile)) {
        $pdo = new PDO("mysql:host=localhost;dbname=my_project", "root", "root");
        $sql = "UPDATE `users` SET avatar =:avatar  WHERE id=:id";
        $statement = $pdo->prepare($sql);
        $result = $statement->execute([
            "avatar" => $mayAvatarName,
            "id" => $lastInsertId
        ]);
    }
}

function ad_social_linc($lastInsertId, $telegram, $instagram, $vk)
{
    $pdo = new PDO("mysql:host=localhost;dbname=my_project", "root", "root");
    $sql = "UPDATE `users` SET vk =:vk , instagram=:instagram, telegram =:telegram  WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $result = $statement->execute([
        "vk" => $vk,
        "instagram" => $instagram,
        "telegram" => $telegram,
        "id" => $lastInsertId
    ]);
}

function edit_credentials($user_id, $email, $password)
{
    $pdo = new PDO("mysql:host=localhost;dbname=my_project", "root", "root");
    $sql = "UPDATE `users` SET  email =:email , password=:password  WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $result = $statement->execute([
        "email" => $email,
        "password" => $password,
        "id" => $user_id
    ]);
}

function set_new_status($user_id, $status)
{
    $pdo = new PDO("mysql:host=localhost;dbname=my_project", "root", "root");
    $sql = "UPDATE `users` SET  status =:status   WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $result = $statement->execute([
        "status" => $status,
        "id" => $user_id
    ]);
}

//function has_image($user_id, $image){
//   обошелся без данной простой проверкой !!!!!!!!
//}

function delete($user_id)
{
    // DELETE изображение
    $pdo = new PDO("mysql:host=localhost;dbname=my_project", "root", "root");
    $sql = "SELECT * FROM users WHERE id=:id";
    $statement = $pdo->prepare($sql);
    $statement->execute(["id" => $user_id]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);
    $dir = 'img/demo/avatars/';
    $img_name = $user['avatar'];
    unlink($dir.$img_name);

    // удаление из бд
    $pdo = new PDO("mysql:host=localhost;dbname=my_project", "root", "root");
    $sql = "DELETE FROM `users` WHERE id = :id";
    $statement = $pdo->prepare($sql);
    $result = $statement->execute([
        "id" => $user_id
    ]);
}
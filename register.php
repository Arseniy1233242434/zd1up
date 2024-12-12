<?php
$servername = "127.0.0.1"; 
$username = "root"; 
$password = ""; 
$dbname = "books_KuzminPr-31"; 


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получаем данные из формы
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $birthdate = $_POST['birthdate'];
    $email = $_POST['email'];
    $login = $_POST['login'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Хешируем пароль

    
    $checkLoginSql = "SELECT * FROM `Авторизация` WHERE `Логин` = '$login'";
    $result = $conn->query($checkLoginSql);

    if ($result->num_rows > 0) {
        
        echo "<script>alert('Логин занят. Пожалуйста, выберите другой.');window.location.href = 'register.html';</script>";
    } else {
        
        $sql = "INSERT INTO `Авторизация` (`Логин`, `Пароль`) VALUES ('$login', '$password')";
        
        if ($conn->query($sql) === TRUE) {
            
            $last_id = $conn->insert_id;

                        $sql = "INSERT INTO `Данные пользователей` (`Номер`, `Фамилия`, `Имя`, `Дата_рождения`, `Почта`, `Логин`, `Пароль`) 
                    VALUES (NULL, '$lastname', '$firstname', '$birthdate', '$email', '$login', '$password')";
            
            if ($conn->query($sql) === TRUE) {
                echo "<script> 
                alert('Регистрация прошла успешно!'); 
                window.location.href = 'register.html';
                </script>";
            } else {
                echo "<div style='color: red; font-weight: bold;'>Ошибка при вставке в таблицу Данные пользователя: " . $conn->error . "</div>";
            }
        } else {
            echo "<div style='color: red; font-weight: bold;'>Ошибка при вставке в таблицу Авторизация: " . $conn->error . "</div>";
        }
    }
}

$conn->close(); ?>
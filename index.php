<!-- 
Автор: Петрова Татьяна

Дата реализации: 01.08.2022 

Создание подключения к базе данных, создание объектов класса. -->

<?php
    include_once 'user.php';
    include_once 'array.php';

    $host = 'localhost';
    $dbname = 'testdb';
    $username = 'root';
    $password = '10091997';

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        echo "Connected to $dbname at $host successfully.";
    } catch (PDOException $pe) {
        die("Could not connect to the database $dbname :" . $pe->getMessage());
    }

    if (!class_exists('User')) {
        die("No class User");
    }

    $user = User::fromId($conn, 49);
    $user->toString();
    $array1 = ArrayId::withNumber($conn, 50, "<");
    print_r($array1);
    $array1->getArray();
    $array1->deleteArray();
?>
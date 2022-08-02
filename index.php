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
    // $user1 = User::fromFields($conn, "valeria", "lev", "1915-09-12", 1, "Gomel");
    // $user1->toString();
    // $user1->update("vasya", "alex", "2019-09-10", 0, "Moscow");
    // $user1->toString();
    $array1 = ArrayId::withNumber($conn, 50, "<");
    print_r($array1);
    $array1->getArray();
?>
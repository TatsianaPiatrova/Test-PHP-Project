<?php
    include_once 'user.php';

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

    $user = User::fromId($conn, 45);
    $user1 = User::fromFields($conn, 2, 2, "2015-09-12", 1, "Gomel");
    print_r($user);
    print_r($user1);
?>
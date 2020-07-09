<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "films_catalog";

$token = "p5ZsRpUMmgSW3iJ1dR4ODcsj5SUwGgQp";

$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// CREATE DB 'FILMS_CATALOG'
$sql = "CREATE DATABASE films_catalog";
if ($conn->query($sql) === TRUE) {
    echo "\nDatabase created successfully";
} else {
    echo "\nError creating database: " . $conn->error;
}

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("\nConnection failed: " . $conn->connect_error);
}

// CREATE TABLE 'FILMS'
$sql = "CREATE TABLE films (
    id INT(12) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ru_title VARCHAR(50) NOT NULL,
    orig_title VARCHAR(50) NOT NULL,
    imdb_id INT(12),
    kinopoisk_id INT(12),
    content_type VARCHAR(50)
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "\nTable films created successfully";
    } else {
        echo "\nError creating table: " . $conn->error;
    }

// $sql = "ALTER TABLE films MODIFY ru_title LONGTEXT CHARACTER SET utf8";

// GET API FROM VIDEOCDN.TV
$url = "https://videocdn.tv/api/movies?api_token=$token";
$html = file_get_contents($url);
$html_decode = json_decode($html);

 printf("\nИзначальная кодировка: %s\n", $conn->character_set_name());

/* изменение набора символов на utf8 */
if (!$conn->set_charset("latin1")) {
    printf("\nОшибка при загрузке набора символов utf8: %s\n", $conn->error);
    exit();
} else {
    printf("\nТекущий набор символов: %s\n", $conn->character_set_name());
}

// INSERT DATA FROM API TO TABLE 'FILMS'
foreach ($html_decode->data as $key) {
    $id = $key->id;
    $ru_title = $key->ru_title;
    $orig_title = $key->orig_title;
    $imdb_id = $key->imdb_id;
    $kinopoisk_id = $key->kinopoisk_id;
    $content_type = $key->content_type;

    $sql = "INSERT INTO films (id, ru_title, orig_title, imdb_id, kinopoisk_id, content_type) VALUES ('$id', '$ru_title', '$orig_title', '$imdb_id', '$kinopoisk_id', '$content_type')";

    if ($conn->query($sql) === TRUE) {
        echo "\nNew record created successfully";
    } else {
        echo "\nError: " . $sql . "<br>" . $conn->error;
    }
}
$conn->close();

?>
<?php
    set_time_limit(0);
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "films_catalog";
    
    $token = "p5ZsRpUMmgSW3iJ1dR4ODcsj5SUwGgQp";
    $omdb_token = "b2158625";
    $omdb_url = "http://www.omdbapi.com/";
    
    $conn = new mysqli($servername, $username, $password);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // CREATE DB 'FILMS_CATALOG'
    $sql = "CREATE DATABASE IF NOT EXISTS films_catalog";
    // if ($conn->query($sql) === TRUE) {
    //     echo "\nDatabase created successfully";
    // } else {
    //     echo "\nError creating database: " . $conn->error;
    // }
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("\nConnection failed: " . $conn->connect_error);
    }
    
    // CREATE TABLE 'FILMS'
    // $sql = "CREATE TABLE IF NOT EXISTS films (
    //     id INT(12) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    //     ru_title VARCHAR(50) NOT NULL,
    //     orig_title VARCHAR(50) NOT NULL,
    //     imdb_id INT(12),
    //     kinopoisk_id INT(12),
    //     content_type VARCHAR(50)
    //     )";

    $sql = "CREATE TABLE IF NOT EXISTS films_info (
        id INT(12) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        ru_title VARCHAR(100) NOT NULL,
        orig_title VARCHAR(100) NOT NULL,
        duration INT(12) NOT NULL,
        year_rel INT(12) NOT NULL,
        country VARCHAR(50) NOT NULL,
        genre VARCHAR(50) NOT NULL,
        director VARCHAR(50) NOT NULL,
        production VARCHAR(50) NOT NULL,
        premier INT(12) NOT NULL,
        box_office INT(20) NOT NULL,    
        imdb INT(12) NOT NULL,
        trailer_url VARCHAR(200) NOT NULL,
        video_url VARCHAR(200) NOT NULL
        )";
    if ($conn->query($sql) === TRUE) {
        echo "\nTable films created successfully";
    } else {
        echo "\nError creating table: " . $conn->error;
    }

    $sql = "CREATE TABLE IF NOT EXISTS films_all (
        id INT(12) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        ru_title VARCHAR(200) NOT NULL,
        orig_title VARCHAR(200) NOT NULL,
        duration VARCHAR(50) NOT NULL,
        year_rel INT(12) NOT NULL,
        country VARCHAR(50) NOT NULL,
        genre VARCHAR(100) NOT NULL,
        director VARCHAR(100) NOT NULL,
        production VARCHAR(50) NOT NULL,
        premier VARCHAR(50) NOT NULL,
        box_office VARCHAR(50) NOT NULL,    
        imdb VARCHAR(50) NOT NULL,
        trailer_url VARCHAR(200) NOT NULL,
        video_url VARCHAR(200) NOT NULL,
        poster VARCHAR(200) NOT NULL
        )";
    if ($conn->query($sql) === TRUE) {
        echo "\nTable films created successfully";
    } else {
        echo "\nError creating table: " . $conn->error;
    }    
    
    /* get 21 page of 2020-year films */
    // for($i=1; $i<21; $i++) {
    //     $url = "https://videocdn.tv/api/movies?api_token=$token&year=2020&page=$i";
    //     $html = file_get_contents($url);
    //     $html_decode = json_decode($html);
        
    //     foreach ($html_decode->data as $key) {
    //         $id = $key->id;
    //         $ru_title = $key->ru_title;
    //         $orig_title = $key->orig_title;
    //         $imdb_id = $key->imdb_id;            
    //         /* http://www.omdbapi.com/?i=tt2554274&apikey=b2158625 */
    //         $omdb_film_url = $omdb_url . "?i=" . $imdb_id . "&apikey=" . $omdb_token;
    //         $omdb_html = file_get_contents($omdb_film_url);
    //         $omdb_html_decode = json_decode($omdb_html);
    //         if($omdb_html_decode->Response == "True") {
    //              /* film-info */
    //             $duration = $omdb_html_decode->Runtime;
    //             $year_rel = $omdb_html_decode->Year;
    //             $country = $omdb_html_decode->Country;
    //             $genre = $omdb_html_decode->Genre;
    //             $director = $omdb_html_decode->Director;
    //             $production = $omdb_html_decode->Production;
    //             $premier = $omdb_html_decode->Released;
    //             $box_office = $omdb_html_decode->BoxOffice;
    //             $poster = $omdb_html_decode->Poster;
    //             if($poster != "N/A") {
    //                 /* trailer_url  */
    //                 $yt_query = $orig_title . "+trailer";
    //                 $enc_url = urlencode($yt_query);
    //                 $yt_url = "https://www.youtube.com/results?search_query=" . $enc_url;            
    //                 $yt_html = file_get_contents($yt_url);
    //                 $yt_html_source_code = htmlspecialchars($yt_html); 
    //                 $str_find = "href=&quot;/watch?v=";
    //                 $pos = strpos($yt_html_source_code, $str_find);
    //                 $trailer_url = 'https://www.youtube.com/embed/' . mb_strcut($yt_html_source_code, $pos+20, 11) . ' video_frameborder=0 allowfullscreen></ivideo_frame>';
    //                 /* video_url */
    //                 $video_url = 'http:' . $key->media[0]->path .  ' video_frameborder=0 allowfullscreen></ivideo_frame>';
    //                 /* query SQL */
    //                 $sql = "INSERT INTO films_info (id, ru_title, orig_title,
    //                                                 duration, year_rel, country, genre,
    //                                                 director, production, premier,
    //                                                 box_office, imdb, trailer_url, video_url, poster)
    //                                         VALUES ('$id', '$ru_title', '$orig_title',
    //                                                 '$duration', '$year_rel', '$country', '$genre',
    //                                                 '$director', '$production', '$premier',
    //                                                 '$box_office', '$imdb_id', '$trailer_url', '$video_url', '$poster')";
                
    //                 if ($conn->query($sql) === TRUE) {
    //                     echo "\nNew record created successfully";
    //                 } else {
    //                     echo "\nError: " . $sql . "<br>" . $conn->error;
    //                 }
    //             }    
    //             else {
    //                 echo "\nError: Poster -> N/A";
    //             }       
    //         }    
    //         else {
    //             echo "\nError: Response -> False";
    //         }       
    //     }
    // }

    /* get 200 films all */
    for($i=50; $i<200; $i++) {
        $url = "https://videocdn.tv/api/movies?api_token=$token&page=$i";
        $html = file_get_contents($url);
        $html_decode = json_decode($html);
        
        foreach ($html_decode->data as $key) {
            $id = $key->id;
            $ru_title = $key->ru_title;
            $orig_title = $key->orig_title;
            $last = "'";
            $pos = -1;
            $result = [];
            while(($pos = strpos($orig_title, $last, $pos+1))!==false) {
                $result[] = $pos;
            }
            foreach ($result as $value) {
                $orig_title = substr_replace($orig_title, '\'', $value, 0);
            }
            $imdb_id = $key->imdb_id;            
            /* http://www.omdbapi.com/?i=tt2554274&apikey=b2158625 */
            $omdb_film_url = $omdb_url . "?i=" . $imdb_id . "&apikey=" . $omdb_token;
            $omdb_html = file_get_contents($omdb_film_url);
            $omdb_html_decode = json_decode($omdb_html);
            if($omdb_html_decode->Response == "True") {
                 /* film-info */
                $duration = $omdb_html_decode->Runtime;
                $year_rel = $omdb_html_decode->Year;
                $country = $omdb_html_decode->Country;
                $genre = $omdb_html_decode->Genre;
                $director = $omdb_html_decode->Director;
                $production = $omdb_html_decode->Production;
                $premier = $omdb_html_decode->Released;
                $box_office = $omdb_html_decode->BoxOffice;
                $poster = $omdb_html_decode->Poster;
                $plot = $omdb_html_decode->Plot;
                $last = "'";
                $pos = -1;
                $res = [];
                while(($pos = strpos($plot, $last, $pos+1))!==false) {
                    $res[]=$pos;
                }
                foreach ($res as $value) {
                    $plot = substr_replace($plot, '\'', $value, 0);
                }
                if($poster != "N/A") {
                    /* trailer_url  */
                    $yt_query = $orig_title . "+trailer";
                    $enc_url = urlencode($yt_query);
                    $yt_url = "https://www.youtube.com/results?search_query=" . $enc_url;            
                    $yt_html = file_get_contents($yt_url);
                    $yt_html_source_code = htmlspecialchars($yt_html); 
                    $str_find = "href=&quot;/watch?v=";
                    $pos = strpos($yt_html_source_code, $str_find);
                    $trailer_url = 'https://www.youtube.com/embed/' . mb_strcut($yt_html_source_code, $pos+20, 11) . ' video_frameborder=0 allowfullscreen></ivideo_frame>';
                    /* video_url */
                    $video_url = 'http:' . $key->media[0]->path .  ' video_frameborder=0 allowfullscreen></ivideo_frame>';
                    /* query SQL */
                    $sql = "INSERT INTO films_all (id, ru_title, orig_title,
                                                    duration, year_rel, country, genre,
                                                    director, production, premier,
                                                    box_office, imdb, trailer_url, video_url, poster, plot)
                                            VALUES ('$id', '$ru_title', '$orig_title',
                                                    '$duration', '$year_rel', '$country', '$genre',
                                                    '$director', '$production', '$premier',
                                                    '$box_office', '$imdb_id', '$trailer_url', '$video_url', '$poster', '$plot')";
                
                    if ($conn->query($sql) === TRUE) {
                        echo "\nNew record created successfully";
                    } else {
                        echo "\nError: " . $sql . "<br>" . $conn->error;
                    }
                }    
                else {
                    echo "\nError: Poster -> N/A";
                }       
            }    
            else {
                echo "\nError: Response -> False";
            }       
        }
    }
?>
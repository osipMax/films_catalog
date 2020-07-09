<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "films_catalog";

$token = "p5ZsRpUMmgSW3iJ1dR4ODcsj5SUwGgQp";
$omdb_token = "b2158625";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("\nConnection failed: " . $conn->connect_error);
}

// GET API FROM OMDB.COM
$omdb_url = "http://www.omdbapi.com/";
// $imdb_id = $html_decode->data[0]->imdb_id;
// $omdb_film_url = $omdb_url . "?i=" . $imdb_id . "&apikey=" . $omdb_token;
// $omdb_html = file_get_contents($omdb_film_url);
// $omdb_html_decode = json_decode($omdb_html);

// $video_frame;
// $video_frame = "'http:" . $html_decode->data[0]->media[0]->path .  "' video_frameborder='0' allowfullscreen></ivideo_frame>";

/* GET FILMS INFO FROM OMDB.COM */
// foreach ($html_decode->data as $key) {
//     $imdb_id = $key->imdb_id;
//     $omdb_film_url = $omdb_url . "?i=" . $imdb_id . "&apikey=" . $omdb_token;
//     $omdb_html = file_get_contents($omdb_film_url);

//     $sql = "INSERT INTO films (id, ru_title, orig_title, imdb_id, kinopoisk_id, content_type) VALUES ('$id', '$ru_title', '$orig_title', '$imdb_id', '$kinopoisk_id', '$content_type')";
// }

// GET YOUTUBE TRAILER BY FILM`S NAME
// $film_name = "Hobbit+trailer";
// $yt_url = "https://www.youtube.com/results?search_query=" . $film_name;
// $yt_html = file_get_contents($yt_url);

// $yt_html_source_code = htmlspecialchars($yt_html); 
// $str_find = "href=&quot;/watch?v=";
// $pos = strpos($yt_html_source_code, $str_find);

// $trailer_url = "https://www.youtube.com/watch?v=" . mb_strcut($yt_html_source_code, $pos+20, 11);

// $trailer_video_frame = "'https://www.youtube.com/embed/JTSoD4BBCJc' video_frameborder='0' allowfullscreen></ivideo_frame>";

/* make json result for request */
function ajax_get($sql, $index_more = null) {   
    $count = 0;
    if($sql == "") {
        return "";
    }
    while($row = mysqli_fetch_assoc($sql)) {
        /* return full info about film */
        $id = $row['id'];
        $ru_title = urldecode($row['ru_title']);
        $orig_title = urldecode($row['orig_title']);
        $imdb = $row['imdb'];
        $duration = $row['duration'];
        $year_rel = $row['year_rel'];
        $country = $row['country'];
        $genre = $row['genre'];
        $director = $row['director'];
        $production = $row['production'];
        $premier = $row['premier'];
        $box_office = $row['box_office'];
        $trailer_url = $row['trailer_url'];
        $video_url = $row['video_url'];
        $poster = $row['poster'];
        /* add to return mas */
        $mas = array('ID' => $id, 'ru-title' => $ru_title,
                        'orig-title' => $orig_title, 'IMDB' => $imdb,
                        'Duration' => $duration, 'Year' => $year_rel,
                        'Country' => $country, 'Genre' => $genre,
                        'Director' => $director, 'Production' => $production,
                        'Premier' => $premier, 'BoxOffice' => $box_office,
                        'Trailer-url' => $trailer_url, 'Video-url' => $video_url,
                        'Poster' => $poster);
        if($index_more == null) {
            if($count == 30) {break;}
            $result[] = $mas;
            $count++;
        }
        else {
            if($count >= $index_more) {
                $result[] = $mas;
                $count++;
                if($count == $index_more + 30   ) {
                break;
            }
            }
            else {
                $count++;
            }
        }
    }
    return $result;    
}
  
// обработка AJAX GET-запросов
if($_SERVER['REMOTE_ADDR']) {
    if(!empty($_GET['top'])) {
        $sql = $conn->query("SELECT * FROM films_all");
        $mas = [];
        while($row = mysqli_fetch_assoc($sql)) {
            $imdb = $row['imdb'];
            $url = $omdb_url . "?i=" . $imdb . "&apikey=" . $omdb_token;
            $html = file_get_contents($url);
            $html_decode = json_decode($html);
            if($html_decode->Response == "True") {
                foreach($html_decode->Ratings as $key) {
                    if($key->Source === "Rotten Tomatoes") {
                        $reit = $key->Value;
                        $mas[] = array($imdb => $reit);
                    }
                }
            }
        }
        ksort($mas);
        echo $mas;
    }
    if(!empty($_GET['all'])) {
        if($_GET['all'] == 1) {
            $sql = $conn->query("SELECT id, ru_title, orig_title,
                                        duration, year_rel, country, genre,
                                        director, production, premier,
                                        box_office, imdb, trailer_url, video_url, poster
                                FROM films_all");
            echo json_encode(ajax_get($sql));    
            // $requestJson = [];
            // $grz = '';
            // $cars_id = '';
            // $client_id = '';
            // $sql = $conn->query("SELECT id, ru_title, orig_title, imdb_id FROM films ORDER BY id");
            // while($row = mysqli_fetch_assoc($sql)) {
            //     /* return only name and id of films */
            //     // $mas = array( $row['id'], $row['ru_title'], $row['orig_title'] );
            //     // $result[] = $mas;
            //     /* return full info about film by IMDB-id */
            //     $imdb_id = $row['imdb_id'];
            //     $omdb_film_url = $omdb_url . "?i=" . $imdb_id . "&apikey=" . $omdb_token;
            //     $omdb_html = file_get_contents($omdb_film_url);
            //     $omdb_html_decode = json_decode($omdb_html);
            //     $result[] = $omdb_html_decode;
            //     // echo $result;
            //     // $sql = "INSERT INTO films (id, ru_title, orig_title, imdb_id, kinopoisk_id, content_type) VALUES ('$id', '$ru_title', '$orig_title', '$imdb_id', '$kinopoisk_id', '$content_type')";
            // }
            // echo json_encode($result);        
        }
    }
    if(!empty($_GET['slider'])) {
        $sql = $conn->query("SELECT ru_title, poster FROM films_all WHERE year_rel >= 2016 LIMIT 10");
        if($sql == "") {
            return "";
        }
        while($row = mysqli_fetch_assoc($sql)) {
            /* return full info about film */
            $ru_title = urldecode($row['ru_title']);
            $poster = $row['poster'];
            $mas = array('ru-title' => $ru_title, 'Poster' => $poster);
            $result[] = $mas;
        }
        echo json_encode($result);
    }
    if(!empty($_GET['type'])) {
        if($_GET['type'] == 1)
        {
            $name = urlencode($_GET['name']);

            $yt_url = "https://www.youtube.com/results?search_query=" . $name . "+trailer";
            $enc_url = urlencode($yt_url);
            $yt_html = file_get_contents($yt_url);

            $yt_html_source_code = htmlspecialchars($yt_html); 
            $str_find = "href=&quot;/watch?v=";
            $pos = strpos($yt_html_source_code, $str_find);

            $trailer_url = "'https://www.youtube.com/embed/" . mb_strcut($yt_html_source_code, $pos+20, 11) . "' video_frameborder='0' allowfullscreen></ivideo_frame>";

            $trailer_video_frame = "'https://www.youtube.com/embed/JTSoD4BBCJc' frameborder='0' allowfullscreen></iframe>";
            // <iframe width="560" height="315" src="https://www.youtube.com/embed/-T7CM4di_0c" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            echo $trailer_url;
        }
        if($_GET['type'] == 2)
        {
            $name = $_GET['name'];
            $url = "https://videocdn.tv/api/movies?api_token=$token&query=$name";
            $html = file_get_contents($url);
            $html_decode = json_decode($html);
            $video_frame = "'http:" . $html_decode->data[0]->media[0]->path .  "' video_frameborder='0' allowfullscreen></ivideo_frame>";
            echo $video_frame;
        } 
    }
    /* get film-info by name */
    if(!empty($_GET["name"])) {
        if(!empty($_GET["contains"])) {
            $name = $_GET["name"];
            $sql = $conn->query("SELECT * FROM films_all WHERE orig_title LIKE '%$name%'");
            if($sql->num_rows == 0) {
                $sql = $conn->query("SELECT * FROM films_all WHERE ru_title LIKE '%$name%'");
            }
            echo json_encode(ajax_get($sql)); 
        }
        else if(!empty($_GET["descr"])) {
            $name = $_GET["name"];
            $sql = $conn->query("SELECT imdb FROM films_all WHERE orig_title='$name'");
            $row = mysqli_fetch_assoc($sql);
            $url = "http://www.omdbapi.com/?i=" . $row['imdb'] . "&apikey=b2158625";
            $html = file_get_contents($url);
            $res = json_decode($html);
            if($res->Response == "True") {                
                $plot = $res->Plot;
                echo json_encode($plot); 
            }
        }
        else {
            $name = $_GET["name"];
            $sql = $conn->query("SELECT * FROM films_all WHERE orig_title='$name'");
            echo json_encode(ajax_get($sql)); 
        }         
    }
    /* upload page films */
    if(!empty($_GET['click'])) {
        $query = "SELECT * FROM films_all WHERE ";
        $count = 0;

        if(!empty($_GET['more'])) {
            $index_more = $_GET['more'];
        }
        else {
            $index_more = 0;
        }
        
        if(!empty($_GET['new'])) {
             $query .= "year_rel >= 2016 AND ("; 
             $count++;
        }

        if($count == 0) {
            $query .= "(";
        }       

        /* SELECT * FROM `films_info` WHERE (genre LIKE '%drama%' OR genre LIKE '%thriller%') */
        /* genre */
        if(!empty($_GET['Animation'])) { $query .= "genre LIKE '%animation%' or "; }
        if(!empty($_GET['Adventure'])) { $query .= "genre LIKE '%adventure%' or "; }
        if(!empty($_GET['Action'])) { $query .= "genre LIKE '%action%' or ";}
        if(!empty($_GET['Biography'])) { $query .= "genre LIKE '%biography%' or ";}
        if(!empty($_GET['Comedy'])) { $query .= "genre LIKE '%comedy%' or ";}
        if(!empty($_GET['Crime'])) { $query .= "genre LIKE '%crime%' or ";}
        if(!empty($_GET['Drama'])) { $query .= "genre LIKE '%drama%' or ";}
        if(!empty($_GET['Fantasy'])) { $query .= "genre LIKE '%fantasy%' or ";}
        if(!empty($_GET['Family'])) { $query .= "genre LIKE '%family%' or ";}
        if(!empty($_GET['History'])) { $query .= "genre LIKE '%history%' or ";}
        if(!empty($_GET['Horror'])) { $query .= "genre LIKE '%horror%' or ";}
        if(!empty($_GET['Sci-Fi'])) { $query .= "genre LIKE '%sci-fi%' or ";}
        if(!empty($_GET['Thriller'])) { $query .= "genre LIKE '%thriller%' or ";}

        if(strpos($query, 'genre') === false) {
            $query = substr($query, 0, -2);
            $query .= ' (';
        }
        if(strpos($query, 'genre') !== false) {
            $query = substr($query, 0, -4);
            $query .= ') AND (';
        }
        /* country */
        if(!empty($_GET['usa'])) { $query .= "country LIKE '%usa%' or "; }
        if(!empty($_GET['canada'])) { $query .= "country LIKE '%canada%' or "; }
        if(!empty($_GET['germany'])) { $query .= "country LIKE '%germany%' or ";}
        if(!empty($_GET['india'])) { $query .= "country LIKE '%india%' or ";}
        if(!empty($_GET['russia'])) { $query .= "country LIKE '%russia%' or ";}
        if(!empty($_GET['uk'])) { $query .= "country LIKE '%uk%' or ";}
        if(!empty($_GET['japan'])) { $query .= "country LIKE '%japan%' or ";}
        if(!empty($_GET['china'])) { $query .= "country LIKE '%china%' or ";}
        if(!empty($_GET['france'])) { $query .= "country LIKE '%france%' or ";}
        if(!empty($_GET['switzerland'])) { $query .= "country LIKE '%switzerland%' or ";}
        if(!empty($_GET['argentina'])) { $query .= "country LIKE '%argentina%' or ";}
        if(!empty($_GET['south korea'])) { $query .= "country LIKE '%south korea%' or ";}
        if(!empty($_GET['austria'])) { $query .= "country LIKE '%austria%' or ";}
        if(!empty($_GET['mexico'])) { $query .= "country LIKE '%mexico%' or ";}
        if(!empty($_GET['hong kong'])) { $query .= "country LIKE '%hong kong%' or ";}
        if(!empty($_GET['spain'])) { $query .= "country LIKE '%spain%' or ";}
        if(!empty($_GET['australia'])) { $query .= "country LIKE '%australia%' or ";}
        if(!empty($_GET['south africa'])) { $query .= "country LIKE '%south africa%' or ";}
        if(!empty($_GET['netherlands'])) { $query .= "country LIKE '%netherlands%' or ";}

        if(strpos($query, 'country') === false) {
            $query = substr($query, 0, -6);
            $query .= '';
            if(strpos($query, 'genre') === false) {
                if(strpos($query, 'year') === false) {
                    $query = substr($query, 0, -2);
                }
            }
        }
        if(strpos($query, 'country') !== false) {
            $query = substr($query, 0, -4);
            $query .= ')';
        }
        $sql = $conn->query($query);
        echo json_encode(ajax_get($sql, $index_more)); 
    }
}


// INSERT DATA FROM API TO TABLE 'FILMS'
// foreach ($html_decode->data as $key) {
//     $id = $key->id;
//     $ru_title = $key->ru_title;
//     $orig_title = $key->orig_title;
//     $imdb_id = $key->imdb_id;
//     $kinopoisk_id = $key->kinopoisk_id;
//     $content_type = $key->content_type;

//     $sql = "INSERT INTO films (id, ru_title, orig_title, imdb_id, kinopoisk_id, content_type) VALUES ('$id', '$ru_title', '$orig_title', '$imdb_id', '$kinopoisk_id', '$content_type')";

//     // if ($conn->query($sql) === TRUE) {
//     //     echo "\nNew record created successfully";
//     // } else {
//     //     echo "\nError: " . $sql . "<br>" . $conn->error;
//     // }
// }
$conn->close();

?>
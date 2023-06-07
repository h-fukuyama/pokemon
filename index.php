<?php

function show(){
    session_start();

    if(empty($_SESSION['list'])||empty($_SESSION['now'])){
        $_SESSION['list'] = 10;
        $_SESSION['now'] = 0;
    }

    #listは件数
    if(isset($_GET['list'])){
        if($_GET['list']==10||empty($_SESSION['list'])){
            $_SESSION['list'] = 10;
            $_SESSION['now'] = $_GET['now'];
        }else if($_GET['list']==20){
            $_SESSION['list'] = 20;
            $_SESSION['now'] = $_GET['now'];
        }else if($_GET['list']==50){
            $_SESSION['list'] = 50;
            $_SESSION['now'] = $_GET['now'];
        }
    }

    #nowは最初の
    if(isset($_GET['page'])){
        if($_GET['page']=="next"){
            $_SESSION['now'] = $_SESSION['now'] + $_SESSION['list'];
            $_SESSION['list'] = $_GET['list'];
        }else if($_GET['page']=="back"){
            $_SESSION['now'] = $_SESSION['now'] - $_SESSION['list'];
            $_SESSION['list'] = $_GET['list'];
        }
    }

    $url = "https://pokeapi.co/api/v2/pokemon/?limit=" . $_SESSION['list'] . "&offset=" . $_SESSION['now'];
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    foreach($data['results'] as $key => $value){
        print("<div class='card' onclick='flipCard(this)'>");
        $pokemon_url = $value['url'];
        $pokemon_response = file_get_contents($pokemon_url);
        $pokemon_data = json_decode($pokemon_response, true);
        $uri = rtrim($pokemon_url, '/');
        $uri = substr($uri, strrpos($uri, '/') + 1);
        $url_species = "https://pokeapi.co/api/v2/pokemon-species/" . $uri;
        $response_species = file_get_contents($url_species);
        $species_data = json_decode($response_species, true);

        // var_dump($species_data['flavor_text_entries']['ja']['flavor_text']);
        print("<div class='front'>");
        echo "no. " . $pokemon_data['id'] . " <b>" . $species_data['names'][0]['name'] . "</b><br>";
        $picture = $pokemon_data['sprites']['front_default'];
        echo "<img src=$picture><br>";
        echo "ぞくせい: ";
        foreach($pokemon_data['types'] as $key => $val){
            $types_url = $val['type']['url'];
            $types_response = file_get_contents($types_url);
            $types_data = json_decode($types_response, true);
            echo $types_data['names'][0]['name'] . " ";
        }
        echo "<br>";
        echo "たかさ: " . $pokemon_data['height']*10 . "cm<br>";
        echo "おもさ: " . $pokemon_data['weight']/10 . "kg";
        print("</div>");

        print("<div class='back'>");
        #ここからカードの裏側情報
        $picture_official = $pokemon_data['sprites']['other']['official-artwork']['front_default'];
        $picture_back = $pokemon_data['sprites']['back_default'];
        echo "<div class='container'>";
        echo "<img src=$picture_official class='left'>
            <div id='right'>
            <img src=$picture>
            <img src=$picture_back><br>
            </div></div>";
        echo "no. " . $pokemon_data['id'] . " <b>" . $species_data['names'][0]['name'] . "</b>";
        echo "ぞくせい: ";
        foreach($pokemon_data['types'] as $key => $val){
            $types_url = $val['type']['url'];
            $types_response = file_get_contents($types_url);
            $types_data = json_decode($types_response, true);
            echo $types_data['names'][0]['name'] . " ";
        }
        echo "<br>";
        $flavorTexts = $species_data['flavor_text_entries'];
        foreach ($flavorTexts as $flavorText) {
            if($flavorText['language']['name'] === 'ja-Hrkt'){
                $japaneseDescription = $flavorText['flavor_text'];
                break;
            }
        }
        echo $japaneseDescription;
        print("</div>");
        print("</div>");
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='css.css'>
    <title>Document</title>
</head>
<body>
    <h1>ポケモン図鑑</h1>
    <?php $now = show() ?><br>
    <script>
        function flipCard(card) {
            card.classList.toggle("flipped");
        }
    </script>
    <footer>
    <form action="index.php" method="get">
        <div id="button">
        <button type="submit" name="list" id="button_2" value=10>10件表示</button>
        <button type="submit" name="list" id="button_2" value=20>20件表示</button>
        <button type="submit" name="list" id="button_2" value=50>50件表示</button>
        </div>
        <input type="hidden" name="now" value=<?= $_SESSION['now'] ?>>
    </form>
    <div id="button">
    <form action="index.php" method="get">
        <input type="submit" value="前へ" class="button_left">
        <input type="hidden" name="page" value="back">
        <input type="hidden" value=<?= $_SESSION['list'] ?> name="list">
        <input type="hidden" name="now" value=<?= $_SESSION['now'] ?>>
    </form>
    <form action="index.php" method="get">
        <input type="submit" value="次へ" class="button_right">
        <input type="hidden" name="page" value="next">
        <input type="hidden" value=<?= $_SESSION['list'] ?> name="list">
        <input type="hidden" name="now" value=<?= $_SESSION['now'] ?>>
    </form>
    </div>
    </body>
    <footer>
</html>
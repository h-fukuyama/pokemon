<?php

function show(){
    if(!isset($_GET['page'])){
        $now = 0;
    }else if($_GET['page']=="next"){
        $now = $_GET['now'] + 10;
    }else if($_GET['page']=="back"){
        $now = $_GET['now'] - 10;
    }
    $url = "https://pokeapi.co/api/v2/pokemon/?limit=10&offset=" . $now;
    $response = file_get_contents($url);
    $data = json_decode($response, true);

    print("<div class='parent'>");
    foreach($data['results'] as $key => $value){
        $pokemon_url = $value['url'];
        $pokemon_response = file_get_contents($pokemon_url);
        $pokemon_data = json_decode($pokemon_response, true);

        $url_species = "https://pokeapi.co/api/v2/pokemon-species/" . $now+$key+1;
        $response_species = file_get_contents($url_species);
        $species_data = json_decode($response_species, true);

        print("<div class='child'>");
        echo "<p id='name'>なまえ: " . $species_data['names'][0]['name'] . "</p><br>";
        $picture = $pokemon_data['sprites']['front_default'];
        echo "<img src=$picture><br>";
        echo "ぞくせい: ";
        foreach($pokemon_data['types'] as $key => $val){
            //var_dump($val['type']['url']);
            $types_url = $val['type']['url'];
            $types_response = file_get_contents($types_url);
            $types_data = json_decode($types_response, true);
            echo $types_data['names'][0]['name'] . " ";
        }
        echo "<br>";
        echo "たかさ: " . $pokemon_data['height']*10 . "cm<br>";
        echo "おもさ: " . $pokemon_data['weight']/10 . "kg";
        print("</div>");
    }
    print("</div>");
    return $now;
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
    <?php $now = show() ?>
    <div id="button">
    <form action="index.php" method="get">
        <input type="submit" value="前へ" class="button_left">
        <input type="hidden" name="page" value="back">
        <input type="hidden" value=<?= $now ?> name="now">
    </form>
    <form action="index.php" method="get">
        <input type="submit" value="次へ" class="button_right">
        <input type="hidden" name="page" value="next">
        <input type="hidden" value=<?= $now ?> name="now">
    </form>
    </div>
</body>
</html>
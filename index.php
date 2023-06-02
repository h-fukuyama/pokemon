<?php

function show(){
    if(!isset($_GET)){
        $now = 0;
    }else if(isset($_GET) && $_GET['next']=="次へ"){
        $now = $_GET['now'] + 10;
    }else if($_GET['back']=="前へ"){
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

        //var_dump($pokemon_data);
        print("<div class='child'>");
        echo "name: " . $pokemon_data['name'] . "<br>";
        $picture = $pokemon_data['sprites']['front_default'];
        echo "<img src=$picture><br>";
        echo "type: ";
        foreach($pokemon_data['types'] as $key => $val){
            echo $val['type']['name'] . " ";
        }
        echo "<br>";
        echo "height: " . $pokemon_data['height'] . "<br>";
        echo "weight: " . $pokemon_data['weight'];
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
    <form action="index.php" method="get">
        <input type="submit" name="back" value="前へ">
        <input type="hidden" value=<?= $now ?> name="now">
    </form>
    <form action="index.php" method="get">
        <input type="submit" name="next" value="次へ">
        <input type="hidden" value=<?= $now ?> name="now">
    </form>

</body>
</html>
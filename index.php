<?php
function show(){
    if(!isset($_GET['page_id'])){
        $now = 0;
    }else{
        $now = $_GET['page_id'];
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
}

if(!isset($_GET['page_id'])){
    $now = 0;
}else{
    $now = $_GET['page_id'];
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
    <?php show() ?>
    <form action="" method="get">
    <input type="button" id="back" value="前へ">
    </form>
    <form action="" method="next">
    <input type="button" id="back" value="次へ">
    </form>

</body>
</html>
<?php
$url = 'https://pokeapi.co/api/v2/pokemon/?limit=10&offset=0';
$response = file_get_contents($url);
$data = json_decode($response, true);

print("<div>");
foreach($data['results'] as $key => $value){
    print("<p>");
    $pokemon_url = $value['url'];
    $pokemon_response = file_get_contents($pokemon_url);
    $pokemon_data = json_decode($pokemon_response, true);

    //var_dump($pokemon_data);
    echo "name: " . $pokemon_data['name'] . "<br>";
    $picture = $pokemon_data['sprites']['front_default'];
    echo "<img src=$picture><br>";
    echo "type: ";
    foreach($pokemon_data['types'] as $key => $val){
        echo $val['type']['name'] . " ";
    }
    echo "<br>";
    echo "height: " . $pokemon_data['height'] . "<br>";
    echo "weight: " . $pokemon_data['weight']. "<br>";
    echo "<br>";
    print("</p>");
}
print("</div>");


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
</body>
</html>
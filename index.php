<?php
$url = 'https://pokeapi.co/api/v2/pokemon/?limit=10&offset=0';
$url2 = 'https://pokeapi.co/api/v2/pokemon/1';
$response = file_get_contents($url2);

$data = json_decode($response, true);

print("<pre>");
//  foreach($data['results'] as $key => $value){
     echo "name: " . $data['name'] . "<br>";
     $picture = $data['sprites']['front_default'];
     echo "<img src=$picture><br>";
     echo "type: ";
     foreach($data['types'] as $key => $value){
        echo $value['type']['name'] . " ";
     }
     echo "<br>";
     echo "height: " . $data['height'] . "<br>";
     echo "weight: " . $data['weight']. "<br>";
print("</pre>");

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
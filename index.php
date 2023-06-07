<?php
###########################################
#ポケモンオブジェクトの定義
class Pokemon {
    public $id;
    public $name;
    public $types;
    public $height;
    public $weight;
    public $description;

    public function __construct($id, $name, $types, $height, $weight, $description) {
        $this->id = $id;
        $this->name = $name;
        $this->types = $types;
        $this->height = $height;
        $this->weight = $weight;
        $this->description = $description;
    }
}
###########################################


###########################################
#指定したURLをjsonにデコードする関数
function decode_url($url) {
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    return $data;
}
###########################################


###########################################
#リストを表示する関数
function show(){
    session_start();
    #リスト数、ページの頭の初期値を設定
    if(empty($_SESSION['list'])||empty($_SESSION['now'])){
        $_SESSION['list'] = 10;
        $_SESSION['now'] = 0;
    }

    #リスト数の変更があった場合の設定（SESSIONで保持）
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

    #ページの頭の数字を設定（SESSIONで保持）
    if(isset($_GET['page'])){
        if($_GET['page']=="next"){
            $_SESSION['now'] = $_SESSION['now'] + $_SESSION['list'];
            $_SESSION['list'] = $_GET['list'];
        }else if($_GET['page']=="back"){
            $_SESSION['now'] = $_SESSION['now'] - $_SESSION['list'];
            $_SESSION['list'] = $_GET['list'];
        }
    }

    #SESSION情報をもとに10匹分のポケモンを拾ってくる
    $data = decode_url("https://pokeapi.co/api/v2/pokemon/?limit=" . $_SESSION['list'] . "&offset=" . $_SESSION['now']);

    echo "<div class='book'>";
    foreach($data['results'] as $key => $value){ #ポケモン１匹ずつで表示の処理を回す
        $types_text = "";
        $pokemon_url = $value['url'];
        $pokemon_data = decode_url($pokemon_url);
        $picture = $pokemon_data['sprites']['front_default'];#前面画像
        $picture_back = $pokemon_data['sprites']['back_default'];#背面画像
        $picture_official = $pokemon_data['sprites']['other']['official-artwork']['front_default'];#オフィシャルアートワーク
        
        #属性は複数あるパターンがあるのでforeachで配列に代入
        foreach($pokemon_data['types'] as $key => $val){
            $types_data = decode_url($val['type']['url']);
            $types_text = $types_text . " " . $types_data['names'][0]['name'];
        }

        #高さ、重さを格納している別URLをデコード
        $uri = rtrim($pokemon_url, '/');
        $uri = substr($uri, strrpos($uri, '/') + 1);
        $url_species = "https://pokeapi.co/api/v2/pokemon-species/" . $uri;
        $species_data = decode_url($url_species);

        #別URLから説明文もデコード（日本語のテキストを探す）
        $flavorTexts = $species_data['flavor_text_entries'];
        foreach ($flavorTexts as $flavorText) {
            if($flavorText['language']['name'] === 'ja-Hrkt'){
                $japaneseDescription = $flavorText['flavor_text'];
                break;
            }
        }

        #最後にPokemonオブジェクトに値を指定
        $pokemon = new Pokemon(
            $pokemon_data['id'],
            $species_data['names'][0]['name'],
            $types_text,
            $pokemon_data['height']*10,
            $pokemon_data['weight']/10,
            $japaneseDescription
        );

        #ブラウザ上に表示
        echo <<< EOM
        <div class='card' onclick='flipCard(this)'>
            <div class='front'>
                no.  {$pokemon->id} <b>{$pokemon->name}</b><br>
                <img src={$picture} height=100px width=100px><br>
                ぞくせい: {$pokemon->types}<br>
                たかさ: {$pokemon->height}cm<br>
                おもさ: {$pokemon->weight}kg
            </div>
            <div class='back'>
                <div class='container'>
                    <img src=$picture_official class='left'>
                    <div id='right'>
                        <img src=$picture>
                        <img src=$picture_back>
                    </div>
                </div>
                no.  {$pokemon->id} <b>{$pokemon->name}</b>
                ぞくせい: {$pokemon->types}<br>
                $japaneseDescription
            </div>
        </div>
        EOM;
    }
    print("</div>");
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
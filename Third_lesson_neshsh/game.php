<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Game Labirint</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Game Labirint</h1>
<h2>Можно передвигаться по лабиринту с помощью GET запросов:  дописываем в адресе: ?x='ваши координаты'&&y='ваши координаты'</h2>
    <?php
    $man = array(1, 1);
    $man[0] = $_GET['x'];
    $man[1] = $_GET['y'];
    //var_dump($man);
    //$array_labirint = array();
    function create_matrix()
    {
        $array_labirint = array();
        for ($y = 0; $y < 10; $y++) {
            $array_labirint[] = [];
            for ($x = 0; $x < 10; $x++) {
                $array_labirint[$y][$x] = mt_rand(0, 1);
                /*$is_it_gold = array_keys($array_labirint[$y], 2);
                $how_much_gold = count(array_keys($array_labirint[$y], 2));
                // set only one gold in raw:
                if ($how_much_gold > 1) {
                    for ($n = 1; $n < $how_much_gold; $n++) {
                        $array_labirint[$y][$is_it_gold[$n]] = 0;
                    }
                }*/
                //$a=[0,2];
                //echo $a[mt_rand(0, count($a) - 1)];
            }
            $array_labirint[$y][mt_rand(0, 9)] = mt_rand(0, 1) ? '0' : '2';
        }
        return $array_labirint;
    }
    $array_labirint = create_matrix();
    foreach ($array_labirint as $i => $raw) {
        echo "<div class = 'raw'>";
        foreach ($raw as $j => $value) {
            for ($z=0; $z<count($array_labirint[$i]); $z++) {
                if (($man[0]==$array_labirint[$z][$man[0]]&&($array_labirint[$z][$man[0]]==2))) {
                    echo "<div class='info'>В этом столбце спрятано золото<br /> Еще его можно найти в каждой строчке!!!</div>";
                }
            }
            switch ($value) {
                case '0':
                    if ($man[0]==$j&&$man[1]==$i) {
                        echo "<div class='player'> 🚶</div>";
                    } else {
                        echo "<div class='cell_free'></div>";
                    }
                    break;
                case '1':
                    if ($man[0]==$j&&$man[1]==$i) {
                        echo "<div class='player_wrong'> 🚶</div>";
                        echo "<div class='inf'>На стены не лезьть!!</div>";
                    } else {
                        echo "<div class='cell_wall'></div>";
                    }
                    break;
                case '2':
                    if ($man[0]==$j&&$man[1]==$i) {
                        echo "<div class='player_gold'>🚶</div>";
                        echo "<div class='inf'>Вы нашли золото!!!</div>";
                    } else {
                        echo "<div class='cell_free'></div>";
                    }
                    break;
            }
        }
        echo "</div>";
    }
    echo "<pre>";
    print_r($array_labirint);
    echo "</pre>";
    ?>

</body>
</html>
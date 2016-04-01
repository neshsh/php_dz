<?php
session_start();
//начальная страница form.php
// новая игра, если не пошло
if (isset($_POST['reboot'])) {
    unset($_SESSION['gold']);
    unset($_SESSION['player']);
    unset($_SESSION['matrix']);
}
// валидация формы
if (!isset($_SESSION['auth'])) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $_SESSION['auth'] = [$_POST['username'], $_POST['password']];
    }
} else {
    $_SESSION['auth'][0];
}
// счетчик золота ограниченные функции см.164 строку
if (!isset($_SESSION['gold'])) $_SESSION['gold'] = 0;


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Game Labirint</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Game Labirint</h1>
<h2>Игрок: <span style="color: red;"><?php echo $_SESSION['auth'][0];?></span> </h2>
<h3>Собрано золота: <span style="color: darkgoldenrod;"><?php echo $_SESSION['gold'];?></span> </h3>
<div class="manage">
    <h3>Управление игроком</h3>
    <form action='game.php' method='post'>
        <input id="down" type='submit' name='down' value='&or;' />
        <input id="up" type='submit' name='up' value='&and;' />
        <input id="left" type='submit' name='left' value='<' />
        <input id="right" type='submit' name='right' value='>' />
        <input id="reboot" type='submit' name='reboot' value='Новая игра'/>
    </form>
</div>
    <?php


    // функция создания массива
    function create_matrix()
    {
        $array_labirint = array();
        $side_length = 10;
        for ($y = 0; $y < $side_length; $y++) {
            $array_labirint[] = [];
            for ($x = 0; $x < $side_length; $x++) {
                $array = [0, 0, 1];
                $array_labirint[$y][$x] = $array[mt_rand(0, 2)];
            }
            $array_labirint[$y][mt_rand(0, ($side_length-1))] = mt_rand(0, 1) ? '0' : '2';
        }
        return $array_labirint;
    }
    // запись двумерного массива в сессию
    if (!isset($_SESSION['matrix'])) {
        $_SESSION['matrix'] = create_matrix();
    }
    // рисуем стенки для игрока
    $array_labirint = $_SESSION['matrix'];
    foreach ($array_labirint as $l => $value) {
        foreach ($value as $k => $n) {
            if ($n==1) {
                $count_wall[] = [$k, $l];
            }
        }
    }
    //print_r($count_wall);

    // создаем управление игроком

    if (!isset($_SESSION['player'])) {
        $_SESSION['player'] = [0, 0];
    } else {
        if (isset($_POST['down'])) {
            $_SESSION['player'][1]++;
            foreach ($count_wall as $dead_end) {
                if ($_SESSION['player'] == $dead_end) {
                    $_SESSION['player'][1]--;
                    echo "<div class='inf'>На стены не лезьть!!!</div>";
                }
            }
        }
        if (isset($_POST['up'])) {
            $_SESSION['player'][1]--;
            foreach ($count_wall as $dead_end) {
                if ($_SESSION['player'] == $dead_end) {
                    $_SESSION['player'][1]++;
                    echo "<div class='inf'>На стены не лезьть!!!</div>";
                }
            }
        }
        if (isset($_POST['right'])) {
            $_SESSION['player'][0]++;
            foreach ($count_wall as $dead_end) {
                if ($_SESSION['player'] == $dead_end) {
                    $_SESSION['player'][0]--;
                    echo "<div class='inf'>На стены не лезьть!!!</div>";
                }
            }
        }
        if (isset($_POST['left'])) {
            $_SESSION['player'][0]--;
            foreach ($count_wall as $dead_end) {
                if ($_SESSION['player'] == $dead_end) {
                    $_SESSION['player'][0]++;
                    echo "<div class='inf'>На стены не лезьть!!!</div>";
                }
            }
        }
        /*if (isset($_POST['up'])) $_SESSION['player'][1]--;
        if (isset($_POST['right'])) $_SESSION['player'][0]++;
        if (isset($_POST['left'])) $_SESSION['player'][0]--;*/
        if ($_SESSION['player'][0]<0) $_SESSION['player'][0] = 0;
        if ($_SESSION['player'][1]<0) $_SESSION['player'][1] = 0;
        if ($_SESSION['player'][1]>(count($array_labirint)-1)) $_SESSION['player'][1] = (count($array_labirint)-1);
        if ($_SESSION['player'][0]>(count($array_labirint)-1)) $_SESSION['player'][0] = (count($array_labirint)-1);
    }

    $man = $_SESSION['player'];

    // рисуем сам лабиринт
    foreach ($array_labirint as $i => $raw) {
        // поиск золота в строчках и столбцах
        foreach ($raw as $z => $golden) {
            if (($man[0] == $z && ($golden == 2))) {
                $column = "В этом столбце спрятано золото<br />";
                echo "<div class='info'>" . $column . "</div>";
            }
            if (($man[1] == $i && ($golden == 2))) {
                $string = "В этой строчке спрятано золото<br />";
                echo "<div class='info'>" . $string . "</div>";
            }
            if ($column and $string) {
                echo "<div class='info'>" . $column . $string . "</div>";
            }
        }
        echo "<div class = 'raw'>";
        foreach ($raw as $j => $value) {
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
                        echo "<div class='inf'>Начните новую игру</div>";
                    } else {
                        echo "<div class='cell_wall'></div>";
                    }
                    break;
                case '2':
                    // не получилось сделать адекватный счетчик золота, не перезаписывает значение золота на пустое после сбора и не считает общее количество, все попытки это сделать потерпели фиаско, буду признателен за помощь

                    if ($man[0]==$j&&$man[1]==$i) {
                        echo "<div class='player_gold'>🚶</div>";
                        echo "<div class='inf'>Вы нашли золото!!!</div>";
                        $_SESSION['gold']++;
                        //$array_labirint[$i][$j] = 0;
                        //if ($sum_gold = $_SESSION['gold']) {
                        //    echo "<div class='win'>Вы победили!!!</div>";
                        //}
                    } else {
                        echo "<div class='cell_free'></div>";
                    }
                    break;
            }
        }
        echo "</div>";
    }

    ?>

<?php
/*echo "<pre>";
print_r($array_labirint);
echo "</pre>";
*/?>
</body>
</html>
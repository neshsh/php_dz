<?php
session_start();
//echo $_SESSION['auth'] = [$_POST['username'], $_POST['password']];
/*echo "<form action='game.php' method='post'>
                    Username: <input type='text' name='username' value='' /><br />
                    Password: <input type='password' name='password' value='' /><br /><br />
                    <input type='submit' name='submit' value='submit' />
              </form> ";
echo $_POST['username'];
if (isset($_POST['username']) && isset($_POST['password'])) {
    $_SESSION['auth'] = $_POST['username'];
}
echo $_SESSION['auth'];*/
if (!isset($_SESSION['auth'])) {
    echo "<form action='game.php' method='post'>
                    Username: <input type='text' name='username' value='' /><br />
                    Password: <input type='password' name='password' value='' /><br /><br />
                    <input type='submit' name='submit' value='submit' />
              </form> ";
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $_SESSION['auth'] = [$_POST['username'], $_POST['password']];
    }
} else {
   echo $_SESSION['auth'];
}
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
<h2>Можно передвигаться по лабиринту с помощью GET запросов:  дописываем в адресе: ?x='ваши координаты'&&y='ваши координаты'</h2>
    <?php
    //$man = array(1, 1);
    //$man[0] = $_GET['x'];
    //$man[1] = $_GET['y'];
    //var_dump($man);
    //$array_labirint = array();
    if (!isset($_SESSION['gold'])) $_SESSION['gold'] = 0;

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

    $array_labirint = $_SESSION['matrix'];
    foreach ($array_labirint as $l => $value) {
        foreach ($value as $k => $n) {
            if ($n==1) {
                $count_wall[] = [$k, $l];
            }
        }
    }
    print_r($count_wall);

    // создаем управление игроком

    if (!isset($_SESSION['player'])) {
        $_SESSION['player'] = [0, 0];
    } else {
        if (isset($_POST['down'])) $_SESSION['player'][1]++;
        if (isset($_POST['up'])) $_SESSION['player'][1]--;
        if (isset($_POST['right'])) $_SESSION['player'][0]++;
        if (isset($_POST['left'])) $_SESSION['player'][0]--;
        if ($_SESSION['player'][0]<0) $_SESSION['player'][0] = 0;
        if ($_SESSION['player'][1]<0) $_SESSION['player'][1] = 0;
        if ($_SESSION['player'][1]>(count($array_labirint)-1)) $_SESSION['player'][1] = (count($array_labirint)-1);
        if ($_SESSION['player'][0]>(count($array_labirint)-1)) $_SESSION['player'][0] = (count($array_labirint)-1);
    }

    $man = $_SESSION['player'];

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
    /*echo "<pre>";
    print_r($array_labirint);
    echo "</pre>";*/
    ?>
    <form action='game.php' method='post'>
        <input type='submit' name='down' value='&or;' />
        <input type='submit' name='up' value='&and;' />
        <input type='submit' name='left' value='<' />
        <input type='submit' name='right' value='>' />

    </form>
</body>
</html>
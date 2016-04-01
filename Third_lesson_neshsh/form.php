<?php
session_start();
/**
 * Created by PhpStorm.
 * User: Y510
 * Date: 01.04.2016
 * Time: 0:18
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>One page form</title>
</head>
<body>
<form action='game.php' method='post'>
    <label for="username">Username: </label>
    <input type='text' name='username' value='' /><br /><br />
    <label for="password">Password:</label>
    <input type='password' name='password' value='' /><br /><br />
    <input type='submit' name='submit' value='submit' />
</form>
</body>
</html>

<?php 
session_start(); 
session_unset();

$_SESSION['user'] = $_POST['name'];

include ('connect.php');
$link = mysql_connect($db_loc, $db_user, $db_pass);
if (!$link) {
    die('Ошибка соединения: ' . mysql_error());
}
@mysql_select_db($db_name) or die ("Не могу подключиться к базе данных $db_name!");

$sql = 
'INSERT INTO users(UserName, isOnline, LastActivity, GameCode) 
VALUES("' .$_POST['name']. '", "1", NOW(), "0")';

if(!mysql_query($sql))
{echo '<center><p><b>Ошибка при добавлении данных!'.mysql_error().'</b></p></center>';} 
else
{
mysql_close($link); 
echo "<meta http-equiv='Refresh' content='0; url=game.php'>";
}
?>



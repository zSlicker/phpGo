<?php

function execQuery($query){
	include ('connect.php');
	$link = mysql_connect($db_loc, $db_user, $db_pass);
	if (!$link) {
		die('Ошибка соединения: ' . mysql_error());
	}
	@mysql_select_db($db_name) or die ("Не могу подключиться к базе данных $db_name!");

	$result = mysql_query($query);

	if(!$result)
		{echo '<center><p><b>Ошибка при добавлении данных! '.mysql_error().'</b></p></center>';} 
	else
	{		
		mysql_close($link); 
	}
	
	return $result;
}


function getOnline(){
		$result = execQuery('Select count(id) from users where isOnline=1');

		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			foreach ($line as $col_value) {
				echo $col_value;
			}
		}
}

function setOnlinePlayers(){
		$d = date("Y-n-j H:i:s");
		$result = execQuery('update users set isOnline=0 where TIMESTAMPDIFF(MINUTE, `LastActivity`, NOW()) > 1');
}
setOnlinePlayers();
getOnline();
?>
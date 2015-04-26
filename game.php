<?php  
session_start();

define("CELL_COUNT", "19");
define("CELL_SIZE", "30"); 

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


function getReadyPlayers(){
		$result = execQuery('Select UserName from users where GameCode=0');
		$_SESSION['readyPlayers'] = "";
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			foreach ($line as $col_value) {
			if($_SESSION['readyPlayers']=="")
				$_SESSION['readyPlayers'] = $col_value;
			else
				$_SESSION['readyPlayers'] = $_SESSION['readyPlayers']. ", " .$col_value;
			}
		}
}


getReadyPlayers();

echo "Ваш логин: ".$_SESSION['user']."</br>";
echo "<div id=online></div></br>";
echo "Игроки ищущие игру: ".$_SESSION['readyPlayers']."</br>";

function showField() // рисуем поле
{       
        $img = imagecreatetruecolor((CELL_SIZE*CELL_COUNT)+ CELL_SIZE, (CELL_SIZE*CELL_COUNT)+CELL_SIZE);
        $black = imagecolorallocate($img, 0, 0, 0);
        $white = imagecolorallocate($img, 255, 255, 255);
        imagefill($img, 1, 1, $white);
        
        for ($rows = 1; $rows < CELL_COUNT; $rows++)
        {
          for ($columns = 1; $columns < CELL_COUNT; $columns++)
            {
              cell_paint($img, $rows, $columns, $black);
            }
        }     
           
         
        imagejpeg($img, 'file.jpg');
        imagedestroy($img);
}

function cell_paint($img,$rows, $columns, $black) //рисуем клетку
        {
            $x = ($columns) * CELL_SIZE;
            $y = ($rows) * CELL_SIZE;

                imagerectangle($img, $x, $y, $x+CELL_SIZE, $y+CELL_SIZE, $black); 
        }
        showField();
        
       echo "<html oncontextmenu='return false;'>";
        echo "<img id='kartina' name='picture' class='test' src='file.jpg' >";
?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script>
function mouse_click(){
move = 1;
$('.test').bind('click.namespace', function(){
    var x = y = 0;
    var event = event || window.event;
      
    if (document.attachEvent != null) { // Internet Explorer & Opera
        x = window.event.clientX + (document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft);
        y = window.event.clientY + (document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop);
    } else if (!document.attachEvent && document.addEventListener) { // Gecko
        x = event.clientX + window.scrollX;
        y = event.clientY + window.scrollY;
    }      
 
    y0=document.getElementById("kartina").offsetTop;
    x0=document.getElementById("kartina").offsetLeft;
          
    mouse_x = x-x0;
    mouse_y = y-y0;

    //alert(mouse_x+'|'+mouse_y); 

    $.ajax({ 
    type: "POST", 
    url: "go.php", 
    data: "x=" + mouse_x + "&y=" + mouse_y + "&move=" + move, 
    dataType: "html", 
    success: function(img){ 
        var d = new Date();
        image= new Image();
        image.src= "file.jpg?time="+ d.getMilliseconds();
        image.onload=function(){document.picture.src= image.src;}
		if(move==1)
			move=2;
		else
			move=1;
      } 
    });
  }); 
}

mouse_click();

setInterval(function()
{
    $.ajax({
        type: "POST",
        url: "online.php",
        data: "online=check",
        success: function(data){
			$('#online').text("Игроков онлайн: " + data);
        }
    });
}, 1000);

</script>
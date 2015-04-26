<?php
session_start(); 

define("CELL_COUNT", "19");
define("CELL_SIZE", "30");                               

        if(isset($_POST['x'])) 
        { 
          if(isset($_POST['y'])) 
            { 
				if(isset($_POST['y'])) 
				{ 
				  $post_x = $_POST['x'];
				  $post_y = $_POST['y'];
				  $move = $_POST['move'];
				  
				  if(isset($_SESSION['array'])) 
				  { 
					$array=$_SESSION['array']; 
				  } 
				  $array[] =  array($post_x,$post_y, $move);
				  $_SESSION['array'] = $array; 

				  showField();  
				}			  
            }
        }
        else
        {
        unset($_SESSION);
        }

        
        function put_stone($img)
        {        
          foreach($_SESSION['array'] as $arr)
          {
            $mouse_X = (int)round((float)($arr[0] / CELL_SIZE)); //определение координат нажати¤ мыши
            $mouse_Y = (int)round((float)($arr[1] / CELL_SIZE));
          
			if($arr[2]==1)
				$color = imagecolorallocate($img, 255, 255, 255);
			else
				$color = imagecolorallocate($img, 0, 0, 0);
				
			$black = imagecolorallocate($img, 0, 0, 0);
           // 
			imagefilledellipse($img,$mouse_X*CELL_SIZE,$mouse_Y*CELL_SIZE,30,30,$color);
			imageellipse($img,$mouse_X*CELL_SIZE,$mouse_Y*CELL_SIZE,30,30,$black);
          }
        }

        
function showField() // рисуем поле
{       
        header("Content-type: image/jpg");
        $img = imagecreatetruecolor((CELL_SIZE*CELL_COUNT)+CELL_SIZE, (CELL_SIZE*CELL_COUNT)+CELL_SIZE);
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
            
         if(isset($_SESSION['array'])) 
         {             
          put_stone($img, $move);
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
?>
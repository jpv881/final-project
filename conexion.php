	<?php
		$conexion = new mysqli("localhost", "user","pass","database");

    	if(mysqli_connect_errno()){
            echo "Error al conectar";
        }else{
                //echo "Conexión realizada";
        }

        mysqli_set_charset($conexion, "utf8");

?>

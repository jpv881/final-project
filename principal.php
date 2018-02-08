<?php
session_start();
include('conexion.php');

if($_SESSION['user'] == null){
    header('Location: index.html');
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<title>Starter Template for Bootstrap 3.3.6</title>
	<link rel="shortcut icon" href="">
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
    <?php

        if($_SESSION['rol']== 1){ ?>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
            <link rel="stylesheet" href="estilos/cyborg.css">
      <?php  }else{ ?>
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	       <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">
       <?php }
    ?>
	
	<style>
		body {
			padding-top: 50px;
		}
		
		.starter-template {
			padding: 40px 15px;
			text-align: center;
		}
	</style>

	<!--[if IE]>
								<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
								<script src="https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
				<![endif]-->
    <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-67906875-2', 'auto');
  ga('send', 'pageview');

</script>
</head>

<body>
	<?php 
    if($_SESSION['rol'] == 1){
        include('menuUsuario.php');
    }else{
        include('menu.php');
    } ?>

	<div class="container">
		<div class="starter-template">
			<h1>Instalaciones Deportivas</h1>
			
		</div>
	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="angular.min.js"></script>
	<script src="principal.js"></script>
    <script src="menu.js"></script>
</body>

</html>
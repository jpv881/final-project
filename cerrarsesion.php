<?php
session_start();
       
            session_destroy();
            unset($_SESSION['user']);
            unset($_SESSION['rol']);
            unset($_SESSION['id']);
            unset($_SESSION['dias_sancion']);
            unset($_SESSION['horas_cancelar']);
            unset($_SESSION['reservar']);
            unset($_SESSION['cancelar']);
            unset($_SESSION['editar']);
            unset($_SESSION['bajas']);
            unset($_SESSION['informes']);
            unset($_SESSION['roles']);
            unset($_SESSION['instalaciones']);
      
            header('location:index.html');

?>
<?php

require_once("config.php");

if ($_SERVER["REQUEST_METHOD"] != "GET") {
    
    throw new Exception("Operação recebida não condiz com o esperado");
}

function validarCamposText($text){

    $text = trim($text);
    $text = stripslashes($text);
    $text = htmlspecialchars($text);
    $text = strtolower($text);

    return $text;
}

function buscarDadosBanco($login){

    $conn = new PDO("sqlsrv:Database=dbFacul;server=localhost\SQLEXPRESS;ConnectionPooling=0","usuarios","root");
    
    if($conn->connect_error){

        throw new Exception("Falha ao conectar com o banco de dados".$conn->connect_error);
    }

    $query = $conn->prepare("SELECT login,nome,sexo,perfil,email FROM usuarios WHERE login = ?");
    $query->bind_param("s",$login);
    $query->execute();
    $result = $query->get_result();
    $resultado = $result->fetch_assoc();

    echo $resultado['nome']",";
    echo $resultado['login'];
    echo $resultado['sexo'];
    echo $resultado['perfil']; 
    echo $resultado['email'];
}

$login = validarCamposText($_SESSION['login']);
buscarDadosBanco($login);

session_destroy();

?>

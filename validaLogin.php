<?php

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    
    throw new Exception("Operação recebida não condiz com o esperado");
}

$requisicao = $_POST;

function validarCamposText($text){

    $text = trim($text);
    $text = stripslashes($text);
    $text = htmlspecialchars($text);
    $text = strtolower($text);

    return $text;
}

function criarHash($password){

    return password_hash($password, PASSWORD_DEFAULT);
}

function buscarBanco($login, $password){

    $conn = new PDO("sqlsrv:Database=dbFacul;server=localhost\SQLEXPRESS;ConnectionPooling=0","usuarios","root");
    
    if($conn->connect_error){

        throw new Exception("Falha ao conectar com o banco de dados".$conn->connect_error);
    }

    $query = $conn->prepare("SELECT senha FROM usuarios WHERE login = ?");
    $query->bind_param("s",$login);
    $query->execute();
    $result = $query->get_result();
    $senhaBanco = $result->fetch_assoc();

    if(!password_verify($password, $senhaBanco['senha'])){

        header('location: cadastro.html');
    }
}

$login = validarCamposText($requisicao['login']);

buscarBanco($login,$requisicao['password']);

require_once("config.php");

$_SESSION['login'] = $login;

header('location: infosUsuario.php');


?>

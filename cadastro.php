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

function validarEmail($email){

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){

        throw new Exception("O email digitado é inválido. Informação obrigatória");
    }
}

function validarSexo($sexo){

    if(strcmp($sexo,"masculino") != 0 && strcmp($sexo,"feminino") != 0 && strcmp($sexo,"outros") !== 0) {

        throw new Exception("O sexo não corresponde as opções esperadas");
    }   
}

function validarPerfil($perfil){

    if(strcmp($perfil,"administrador") != 0 && strcmp($perfil,"colaborador") != 0 && strcmp($perfil,"cliente") != 0) {

        throw new Exception("O perfil não corresponde as opções esperadas");
    }   
}

function criarHash($password){

    return password_hash($password, PASSWORD_DEFAULT);
}

function salvarBanco($nome, $login, $sexo, $perfil, $password, $email){

    $conn = new PDO("sqlsrv:Database=dbFacul;server=localhost\SQLEXPRESS;ConnectionPooling=0","usuarios","root");
    
    if($conn->connect_error){

        throw new Exception("Falha ao conectar com o banco de dados".$conn->connect_error);
    }

    $query = $conn->prepare("INSERT INTO usuarios (login, nome, senha, email, sexo, perfil) VALUES (?,?,?,?,?,?)");
    $query->bind_param("ssssss",$login,$nome,$password,$email,$sexo,$perfil);
    $query->execute();
}

validarEmail($requisicao['email']);

$nome = validarCamposText($requisicao['nome']);
$login = validarCamposText($requisicao['login']);
$sexo = validarCamposText($requisicao['sexo']);
$perfil = validarCamposText($requisicao['perfil']);
$password = criarHash($requisicao['password']);

validarSexo($sexo);
validarPerfil($perfil);

salvarBanco($nome, $login, $sexo, $perfil, $password, $requisicao['email']);

header('location: login.html');
?>

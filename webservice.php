<?php
require_once 'lib/nusoap.php';
require_once 'bd.php';
require_once 'xml.php';

$server = new soap_server();
$server->configureWSDL("webservice", "urn:webservice");

$server->register("CadastraUsuario",
    array("nome" => "xsd:string",
          "email" => "xsd:string",
          "senha" => "xsd:string"),
    array("return" => "xsd:string"),
    "urn:webservice",
    "urn:webservice#CadastraUsuario",
    "rpc",
    "literal",
    "Adiciona um Usuario ao sistema");
    
$server->register("ValidaSecao",
    array("email" => "xsd:string",
          "senha" => "xsd:string"),
    array("return" => "xsd:string"),
    "urn:webservice",
    "urn:webservice#ValidaSecao",
    "rpc",
    "literal",
    "Valida a secao de um Usuario no sistema");
    
$server->register("DeletaUsuario",
    array("id" => "xsd:int"),
    array("return" => "xsd:string"),
    "urn:webservice",
    "urn:webservice#ValidaSecao",
    "rpc",
    "literal",
    "Deleta o cadastro de um Usuario registrado sistema");

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);

function CadastraUsuario($nome, $email, $senha){
    
    $stmt = $GLOBALS['db']->prepare(
    'INSERT INTO usuarios (nome, email, senha)
    VALUES (:nome, :email, :senha)');
    $stmt->execute(array(
        'nome' => $nome,
        'email' => $email,
        'senha' => $senha
    ));
    
    $id = $GLOBALS['db']->lastInsertId();
    
    $xml = xml('OK', 'Insercao feita com sucesso', 'Usuario com id '.$id.' criado com sucesso');
    
    return $xml;
    
}

function ValidaSecao($email, $senha){
    
    $stmt = $GLOBALS['db']->prepare(
    'SELECT id FROM usuarios
    WHERE email = :email AND senha = :senha');
    $stmt->execute(array(
        'email' => $email,
        'senha' => $senha
    ));
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    $resultado[validade] = TRUE;
    
    $xml = xml('OK', 'Consulta feita com sucesso', $resultado);
    
    return $xml;
    
}

function DeletaUsuario($id){
    
    $stmt = $GLOBALS['db']->prepare(
    'DELETE FROM usuarios
    WHERE id = :id');
    $stmt->execute(array(
        'id' => $id
    ));
    
    $xml = xml('OK', 'Remocao feita com sucesso', 'Usuario com id '.$id.' deletado com sucesso');
    
    return $xml;
    
}
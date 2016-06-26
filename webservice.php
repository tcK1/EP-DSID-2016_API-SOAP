<?php
/***************************IMPORTS***************************/
require_once 'lib/nusoap.php';
require_once 'bd.php';
require_once 'xml.php';
/***************************IMPORTS***************************/

/***************************REGISTROS***************************/
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
/***************************REGISTROS***************************/

/***************************FUNÇÕES***************************/
function CadastraUsuario($nome, $email, $senha){
    
    if(!isset($nome) || !isset($email) || !isset($senha)){
        $xml = xml('ERRO', 'Falha na Insercao', 'Um dos parametros em branco');
        return $xml;
    }
    
    $stmt = $GLOBALS['db']->prepare(
    'INSERT INTO usuarios (nome, email, senha)
    VALUES (:nome, :email, :senha)');
    $status = $stmt->execute(array(
        'nome' => $nome,
        'email' => $email,
        'senha' => $senha
    ));
    
    if ($status){
        $id = $GLOBALS['db']->lastInsertId();
    
        $xml = xml('OK', 'Insercao feita com sucesso', 'Usuario com id '.$id.' criado com sucesso');
    } else {
        $xml = xml('ERRO', 'Falha na Insercao', $stmt->errorInfo()); 
    }
    
    return $xml;
    
}

function ValidaSecao($email, $senha){
    
    if(!isset($email) || !isset($senha)){
        $xml = xml('ERRO', 'Falha na Consulta', 'Um dos parametros em branco');
        return $xml;
    }

    $stmt = $GLOBALS['db']->prepare(
    'SELECT * FROM usuarios
    WHERE email = :email AND senha = :senha');
    $status = $stmt->execute(array(
        'email' => $email,
        'senha' => $senha
    ));
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($status){
        if(!empty($resultado)){
            $resultado[validade] = 1;
            $xml = xml('OK', 'Consulta feita com sucesso', $resultado);
        } else {
            $resultado[validade] = -1;
            $xml = xml('OK', 'Consulta não retornou nenhum valor', $resultado);
        }
    } else {
        $xml = xml('ERRO', 'Falha na Consulta', $stmt->errorInfo()); 
    }
    
    return $xml;
    
}

function DeletaUsuario($id){
    
    if(!isset($id)){
        $xml = xml('ERRO', 'Falha na Insercao', 'ID em branco');
        return $xml;
    }
    
    $stmt = $GLOBALS['db']->prepare(
    'DELETE FROM usuarios
    WHERE id = :id');
    $status = $stmt->execute(array(
        'id' => $id
    ));
    
    if ($status){
        $xml = xml('OK', 'Remocao feita com sucesso', 'Usuario com id '.$id.' deletado com sucesso');
    } else {
        $xml = xml('ERRO', 'Falha na Remocao', $stmt->errorInfo()); 
    }
    
    return $xml;
    
}
/***************************FUNÇÕES***************************/
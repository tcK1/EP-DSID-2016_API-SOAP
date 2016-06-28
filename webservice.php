<?php
/***************************IMPORTS***************************/
require_once 'lib/nusoap.php';
require_once 'bd.php';
require_once 'aux.php';
/***************************IMPORTS***************************/

/***************************REGISTROS***************************/
$server = new soap_server();
$server->configureWSDL("webservice", 
                       "https://distribuidossoap-ztck.c9users.io/webservice?wsdl", 
                       "https://distribuidossoap-ztck.c9users.io/webservice");

$server->register("CadastraUsuario",
    array("nome" => "xsd:string",
          "email" => "xsd:string",
          "senha" => "xsd:string"),
    array("return" => "xsd:string"),
    "https://distribuidossoap-ztck.c9users.io/",
    "#",
    "rpc",
    "literal");
    
$server->register("ValidaSecao",
    array("email" => "xsd:string",
          "senha" => "xsd:string"),
    array("return" => "xsd:string"),
    "https://distribuidossoap-ztck.c9users.io/",
    "#",
    "rpc",
    "literal");
    
$server->register("DeletaUsuario",
    array("id" => "xsd:int"),
    array("return" => "xsd:string"),
    "https://distribuidossoap-ztck.c9users.io/",
    "#",
    "rpc",
    "literal");
    
$server->register("BuscaCompras",
    array("id" => "xsd:int"),
    array("return" => "xsd:string"),
    "https://distribuidossoap-ztck.c9users.io/",
    "#",
    "rpc",
    "literal");

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
/***************************REGISTROS***************************/

/***************************FUNÇÕES***************************/
function CadastraUsuario($nome, $email, $senha){
    
    if(!isset($nome) || !isset($email) || !isset($senha)){
        $xml = xml('ERRO', 'Falha na Inserção', 'Um dos parametros em branco');
        return $xml;
    }
    if(checaEmailInvalido($email)){
        $xml = xml('ERRO', 'Falha na Inserção', 'Email inválido');
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
    
        $xml = xml('OK', 'Inserção feita com sucesso', 'Usuário com id '.$id.' criado com sucesso');
    } else {
        $xml = xml('ERRO', 'Falha na Inserção', $stmt->errorInfo()); 
    }
    
    return $xml;
    
}

function ValidaSecao($email, $senha){
    
    if(!isset($email) || !isset($senha)){
        $xml = xml('ERRO', 'Falha na Consulta', 'Um dos parametros em branco');
        return $xml;
    }
    if(checaEmailInvalido($email)){
        $xml = xml('ERRO', 'Falha na Consulta', 'Email inválido');
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
        $xml = xml('ERRO', 'Falha na Inserção', 'ID em branco');
        return $xml;
    }
    
    $stmt = $GLOBALS['db']->prepare(
    'DELETE FROM usuarios
    WHERE id = :id');
    $status = $stmt->execute(array(
        'id' => $id
    ));
    
    if ($status){
        $xml = xml('OK', 'Remoção feita com sucesso', 'Usuario com id '.$id.' deletado com sucesso');
    } else {
        $xml = xml('ERRO', 'Falha na Remoção', $stmt->errorInfo()); 
    }
    
    return $xml;
    
}

function BuscaCompras($id){
    
    if(!isset($id)){
        $xml = xml('ERRO', 'Falha na Consulta', 'ID em branco');
        return $xml;
    }
    
    $stmt = $GLOBALS['db']->prepare(
    'SELECT detalhes_venda.id_venda, detalhes, preco, pago
    FROM detalhes_venda INNER JOIN vendas
    ON vendas.id_venda = detalhes_venda.id_venda
    AND id_usuario = :id');
    $status = $stmt->execute(array(
        'id' => $id
    ));
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($status){
        if(!empty($resultado)){
            $xml = xml('OK', 'Consulta feita com sucesso', $resultado);
        } else {
            $xml = xml('OK', 'Consulta não retornou nenhum valor', $resultado);
        }
    } else {
        $xml = xml('ERRO', 'Falha na Consulta', $stmt->errorInfo()); 
    }
    
    return $xml;
    
}
/***************************FUNÇÕES***************************/
<?php
echo '<pre>';

ini_set("soap.wsdl_cache_enabled", 0);

$client = new SoapClient('http://distribuidossoap-ztck.c9users.io/webservice.php?wsdl');

echo '-------------------------------Funcoes-------------------------------';

$functions = $client->__getFunctions ();
var_dump ($functions);

echo '-------------------------------CadastraUsuario-------------------------------</br>';

$function = 'CadastraUsuario';
 
$arguments= array(
    'nome'   => 'asd',
    'email'   => 'asd',
    'senha'   => 'srr3we'
);
                
$options = array('location' => 'http://distribuidossoap-ztck.c9users.io/webservice.php');

echo '**********************Resposta*************************';

$result = $client->__soapCall($function, $arguments, $options);
var_dump($result);

echo '**********************XML Lido*************************';

$xml = simplexml_load_string($result, "SimpleXMLElement", LIBXML_NOCDATA);
var_dump($xml);

echo '-------------------------------ValidaSecao-------------------------------</br>';

$function = 'ValidaSecao';
 
$arguments= array(
    'email'   => 'asd',
    'senha'   => 'srr3we'
);
                
$options = array('location' => 'http://distribuidossoap-ztck.c9users.io/webservice.php');

echo '**********************Resposta*************************';

$result = $client->__soapCall($function, $arguments, $options);
var_dump($result);

echo '**********************XML Lido*************************';

$xml = simplexml_load_string($result, "SimpleXMLElement", LIBXML_NOCDATA);
var_dump($xml);

echo '**********************Array Lido*************************';

$json = json_encode($xml);
$array = json_decode($json,TRUE);
var_dump($array);

echo '-------------------------------DeletaUsuario-------------------------------</br>';

$function = 'DeletaUsuario';
 
$arguments= array(
    'id'   => $array[id]
);
                
$options = array('location' => 'http://distribuidossoap-ztck.c9users.io/webservice.php');

echo '**********************Resposta*************************';

$result = $client->__soapCall($function, $arguments, $options);
var_dump($result);

echo '**********************XML Lido*************************';

$xml = simplexml_load_string($result, "SimpleXMLElement", LIBXML_NOCDATA);
var_dump($xml);

echo '</pre>';

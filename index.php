<?php
echo '<pre>';

ini_set("soap.wsdl_cache_enabled", 0);

$client = new SoapClient('http://distribuidossoap-ztck.c9users.io/webservice?wsdl');
$options = array('location' => 'http://distribuidossoap-ztck.c9users.io/webservice');

echo '-------------------------------Funcoes-------------------------------';

$functions = $client->__getFunctions();
var_dump ($functions);

echo '-------------------------------CadastraUsuario-------------------------------</br>';

$function = 'CadastraUsuario';
 
$arguments= array(
    'nome'   => 'TesteSOAP',
    'email'   => 'teste@soap.com',
    'senha'   => 'soap'
);

echo '**********************Resposta*************************';

$result = $client->__soapCall($function, $arguments, $options);
var_dump($result);

echo '**********************XML Lido*************************';

$xml = simplexml_load_string($result, "SimpleXMLElement", LIBXML_NOCDATA);
var_dump($xml);

echo '**********************Array Lido*************************</br>';

$json = json_encode($xml);
$array = json_decode($json,TRUE);
print_r($array);

echo '-------------------------------ValidaSecao-------------------------------</br>';

$function = 'ValidaSecao';
 
$arguments= array(
    'email'   => 'teste@soap.com',
    'senha'   => 'soap'
);

echo '**********************Resposta*************************';

$result = $client->__soapCall($function, $arguments, $options);
var_dump($result);

echo '**********************XML Lido*************************';

$xml = simplexml_load_string($result, "SimpleXMLElement", LIBXML_NOCDATA);
var_dump($xml);

echo '**********************Array Lido*************************</br>';

$json = json_encode($xml);
$array = json_decode($json,TRUE);
print_r($array);

echo '-------------------------------DeletaUsuario-------------------------------</br>';

$function = 'DeletaUsuario';
 
$arguments= array(
    'id'   => $array[id]
);

echo '**********************Resposta*************************';

$result = $client->__soapCall($function, $arguments, $options);
var_dump($result);

echo '**********************XML Lido*************************';

$xml = simplexml_load_string($result, "SimpleXMLElement", LIBXML_NOCDATA);
var_dump($xml);

echo '**********************Array Lido*************************</br>';

$json = json_encode($xml);
$array = json_decode($json,TRUE);
print_r($array);

echo '</pre>';

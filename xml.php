<?php

function xml($status, $descricao, $conteudo){
    
    $xml = new SimpleXMLElement('<resposta/>');
    $xml->addAttribute('status', $status);
    $xml->addAttribute('descricao', $descricao);
    
    if(!is_array($conteudo)) $xml->addChild('mensagem', $conteudo);
    else array_to_xml($conteudo, $xml);
    
    return $xml->asXML();
    
}

function array_to_xml( $data, &$xml_data ) {
    foreach( $data as $key => $value ) {
        if( is_array($value) ) {
            if( is_numeric($key) ){
                $key = 'item'.$key;
            }
            $subnode = $xml_data->addChild($key);
            array_to_xml($value, $subnode);
        } else {
            $xml_data->addChild("$key",htmlspecialchars("$value"));
        }
     }
}
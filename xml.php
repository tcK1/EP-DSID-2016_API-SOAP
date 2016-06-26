<?php

function xml($status, $descricao, $conteudo){
    
    $xml = new SimpleXMLElement('<resposta/>');
    $xml->addAttribute('status', $status);
    $xml->addAttribute('descricao', $descricao);
    
    if(!is_array($conteudo)) $xml->addChild('mensagem', $conteudo);
    else arrayPraXml($conteudo, $xml);
    
    return $xml->asXML();
    
}

function arrayPraXml($student_info, &$xml_student_info) {
    foreach($student_info as $key => $value) {
        if(is_array($value)) {
            if(!is_numeric($key)){
                $subnode = $xml_student_info->addChild("$key");
                arrayPraXml($value, $subnode);
            }
            else{
                arrayPraXml($value, $xml_student_info);
            }
        }
        else {
            $xml_student_info->addChild("$key","$value");
        }
    }
}

function checaEmail($email){  
   
   $Syntaxe='#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#';  
   if(preg_match($Sintaxe, $email)) return true;  
   else return false;  
   
}
<?php declare(strict_types = 1); // atom

namespace Netmosfera\Atlas\Model\Identifiers\Statics;

function staticNamedIdentifierFromString(String $stringifiedStaticNamedIdentifier){
    $pieces = explode("\\", $stringifiedStaticNamedIdentifier);
    $staticIdentifier = NULL;
    foreach($pieces as $piece){
        $staticIdentifier = new StaticNamedIdentifier($staticIdentifier, $piece);
    }
    return $staticIdentifier;
}

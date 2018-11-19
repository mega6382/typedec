<?php declare(strict_types = 1); // atom

namespace Netmosfera\Atlas\Extraction\Factories\HTML;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\Atlas\Model\Identifiers\NamedTypeIdentifier;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function renderPlainTextNamedTypeIdentifier(NamedTypeIdentifier $type){
    $ta = "";
    if($type->getArguments() !== []){
        $p = [];
        foreach($type->getArguments() as $argument){
            $p[] = renderPlainTextTypeIdentifier($argument, TRUE);
        }
        $ta = "&lt;" . implode(", ", $p) . "&gt;"; // @TODO this must be escaped where this function is used, not here
    }
    return $type->getName()->toString() . $ta;
}

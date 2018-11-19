<?php declare(strict_types = 1); // atom

namespace Netmosfera\Atlas\Extraction\Factories\HTML;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\Atlas\Model\Identifiers\NamedTypeIdentifier;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function renderNamedTypeIdentifier(NamedTypeIdentifier $type){
    $ta = "";
    if($type->getArguments() !== []){
        $p = [];
        foreach($type->getArguments() as $argument){
            $p[] = renderTypeIdentifier($argument, TRUE);
        }
        $ta = "&lt;" . implode(", ", $p) . "&gt;";
    }
    return sprintf(
        '<a atlas-documentation-link="%s"><abbr title="%s">%s</abbr></a>%s',
        $type->getName()->toString(),
        $type->getName()->toString(),
        $type->getName()->getLast(),
        $ta
    );
}

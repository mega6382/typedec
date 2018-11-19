<?php declare(strict_types = 1); // atom

namespace Netmosfera\Atlas\Extraction\Factories\HTML;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\Atlas\Model\Identifiers\SetTypeIdentifier;
use Netmosfera\Atlas\Model\Identifiers\NamedTypeIdentifier;
use Netmosfera\Atlas\Model\Identifiers\TypeIdentifier;
use Netmosfera\Atlas\Model\Identifiers\TypeVariableIdentifier;
use Netmosfera\Atlas\Model\Identifiers\UnionTypeIdentifier;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function renderTypeIdentifier(TypeIdentifier $td, Bool $root){
    if($td instanceof NamedTypeIdentifier){
        return renderNamedTypeIdentifier($td);
    }elseif($td instanceof SetTypeIdentifier){
        $types = [];
        foreach($td->getTypes() as $t){
            $types[] = renderTypeIdentifier($t, FALSE);
        }
        $op = $td instanceof UnionTypeIdentifier ? " | " : " & ";
        if($root){
            return implode($op, $types);
        }else{
            return "(" . implode($op, $types) . ")";
        }

    }elseif($td instanceof TypeVariableIdentifier){
        return $td->getName();
    }
    throw new \Error;
}


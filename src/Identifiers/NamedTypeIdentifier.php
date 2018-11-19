<?php declare(strict_types = 1); // atom

namespace Netmosfera\Atlas\Model\Identifiers;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\Atlas\Model\Identifiers\Statics\StaticNamedIdentifier;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class NamedTypeIdentifier implements TypeIdentifier
{
    private $name;

    /**
     * @var         TypeIdentifier[]                                                        `Array<Int, TypeIdentifier>`
     */
    private $typeArguments;

    function __construct(StaticNamedIdentifier $name, Array $typeArguments = []){
        $this->name = $name;
        $this->typeArguments = (function(TypeIdentifier ...$a){ return $a; })(...$typeArguments);
    }

    function getName(): StaticNamedIdentifier{
        return $this->name;
    }

    function getArguments(): array{
        return $this->typeArguments;
    }

    function equals($other): Bool{
        if(!$other instanceof NamedTypeIdentifier){
            return FALSE;
        }

        if($this->name->toString() !== $other->name->toString()){
            return FALSE;
        }

        if(count($this->typeArguments) !== count($other->typeArguments)){
            return FALSE;
        }

        foreach($this->typeArguments as $i => $typeArgument){
            if($typeArgument->equals($other->typeArguments[$i]) === FALSE){
                return FALSE;
            }
        }

        return TRUE;
    }
}

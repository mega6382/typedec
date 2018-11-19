<?php declare(strict_types = 1); // atom

namespace Netmosfera\Atlas\Model\Identifiers;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class TypeVariableIdentifier implements TypeIdentifier
{
    private $name;

    function __construct(String $name){
        $this->name = $name;
    }

    function getName(): String{
        return $this->name;
    }

    function equals($other): Bool{
        return $other instanceof TypeVariableIdentifier && $this->name === $other->name;
    }
}

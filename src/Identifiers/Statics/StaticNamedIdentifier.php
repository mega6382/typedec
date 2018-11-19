<?php declare(strict_types = 1); // atom

namespace Netmosfera\Atlas\Model\Identifiers\Statics;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Error;
use function Netmosfera\Atlas\equal;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class StaticNamedIdentifier
{
    private $parent;
    private $name;

    function __construct(?StaticNamedIdentifier $parent, String $name){
        if(preg_match("@^[a-zA-Z_][a-zA-Z0-9_]*$@", $name) !== 1){ throw new Error(); }
        $this->parent = $parent;
        $this->name = $name;
    }

    function browse(String $name): StaticNamedIdentifier{
        return new self($this, $name);
    }

    function getLast(): String{
        return $this->name;
    }

    function getParent(): ?StaticNamedIdentifier{
        return $this->parent;
    }

    /** @returns StaticNamedIdentifier[] */
    function getAncestors(Bool $includeSelf = FALSE): array{
        $self = $this;
        $ancestors = [];
        while($self->getParent() !== NULL){
            $ancestors[] = $self->getParent();
            $self = $self->getParent();
        }
        $ancestors = array_reverse($ancestors);
        if($includeSelf){
            $ancestors[] = $this;
        }
        return $ancestors;
    }

    function isRoot(): Bool{
        return FALSE;
    }

    function toStringArray(): array{
        $array = $this->parent === NULL ? [] : $this->parent->toStringArray();
        $array[] = $this->name;
        return $array;
    }

    function toString(): String{
        return implode("\\", $this->toStringArray());
    }

    function equals($other): Bool{
        return
            $other instanceof self &&
            $other->name === $this->name &&
            equal($other->getParent(), $this->getParent());
    }
}

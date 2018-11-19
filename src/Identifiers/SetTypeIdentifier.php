<?php declare(strict_types = 1); // atom

namespace Netmosfera\Atlas\Model\Identifiers;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Error;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

abstract class SetTypeIdentifier implements TypeIdentifier
{
    /**
     * @TODOC
     *
     * @var         TypeIdentifier[]                                                        `Array<Int, TypeIdentifier>`
     */
    private $types;

    function __construct(Array $types){
        $this->types = [];

        foreach($types as $type){
            if($this->contains($type) === FALSE){
                $this->add($type);
            }
        }
    }

    function add(TypeIdentifier $content){
        if($this->contains($content) === FALSE){
            $this->types[] = $content;
        }
    }

    function contains($content): Bool{
        foreach($this->types as $existingType){
            if($existingType->equals($content)){
                return TRUE;
            }
        }
        return FALSE;
    }

    function normalize($def){
        if($this->count() === 0){
            return $def;
        }
        if($this->count() === 1){
            return $this->types[0];
        }
        return $this;
    }

    function count(){
        return count($this->types);
    }

    /** @returns TypeIdentifier[] */
    function getTypes(): array{
        return $this->types;
    }

    function equals($other): Bool{
        if($other instanceof static === FALSE){
            return FALSE;
        }

        /** @var SetTypeIdentifier $other */

        if(count($this->types) !== count($other->types)){
            return FALSE;
        }

        foreach($this->types as $type){
            if($other->contains($type) === FALSE){
                return FALSE;
            }
        }

        return TRUE;
    }
}

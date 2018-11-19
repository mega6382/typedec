<?php declare(strict_types = 1); // atom

namespace Netmosfera\Atlas\Extraction\Factories\FromString\TypeIdentifiers;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Closure;
use Netmosfera\Atlas\Model\Identifiers\TypeIdentifier;
use Netmosfera\Atlas\Model\Identifiers\UnionTypeIdentifier;
use Netmosfera\Atlas\Extraction\Factories\Tools\Traverser;
use Netmosfera\Atlas\Model\Identifiers\IntersectionTypeIdentifier;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function makeTypeIdentifierFromString(
    Traverser $traverser,
    Bool $obligatoryParentheses,
    Closure $resolveName
): ?TypeIdentifier{
    $beforeStart = $traverser->save();

    if($obligatoryParentheses && $traverser->consume('\s*\(') === NULL){
        return NULL;
    }

    $typeIdentifiers = [];
    $operatorRegex = '(?:&|\|)';

    CONSUME_ELEMENT:
    {
        $traverser->consume('\s*');
        $typeIdentifier = NULL;
        if($typeIdentifier === NULL){
            $typeIdentifier = makeNamedTypeIdentifierArrayFromString($traverser, $resolveName);
        }
        if($typeIdentifier === NULL){
            $typeIdentifier = makeNamedTypeIdentifierFromString($traverser, $resolveName);
        }
        if($typeIdentifier === NULL){
            $typeIdentifier = makeTypeIdentifierFromString($traverser, TRUE, $resolveName);
        }
        if($typeIdentifier === NULL){
            goto FINISH;
        }
        $typeIdentifiers[] = $typeIdentifier;
        $afterElementConsumption = $traverser->save();
    }

    CHECK_HAS_ANOTHER_ELEMENT:
    {
        $traverser->consume('\s*');
        $setType = $traverser->consume($operatorRegex);

        if($setType === "&"){
            $operatorRegex = '&';
            goto CONSUME_ELEMENT;
        }elseif($setType === "|"){
            $operatorRegex = '\|';
            goto CONSUME_ELEMENT;
        }else{
            $traverser->rollback($afterElementConsumption);
        }
    }

    FINISH:
    {
        if($typeIdentifiers === []){
            $traverser->rollback($beforeStart);
            return NULL;
        }

        if($obligatoryParentheses && $traverser->consume('\s*\)') === NULL){
            $traverser->rollback($beforeStart);
            return NULL;
        }

        if(count($typeIdentifiers) === 1){
            return $typeIdentifiers[0];
        }else{
            return $operatorRegex === "&" ?
                new IntersectionTypeIdentifier($typeIdentifiers) :
                new UnionTypeIdentifier($typeIdentifiers);
        }
    }
}

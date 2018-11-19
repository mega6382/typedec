<?php declare(strict_types = 1); // atom

namespace Netmosfera\Atlas\Extraction\Factories\FromString\TypeIdentifiers;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Closure;
use Netmosfera\Atlas\Model\Identifiers\TypeIdentifier;
use Netmosfera\Atlas\Extraction\Factories\Tools\Traverser;
use Netmosfera\Atlas\Model\Identifiers\NamedTypeIdentifier;
use Netmosfera\Atlas\Model\Identifiers\TypeVariableIdentifier;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function makeNamedTypeIdentifierFromString(Traverser $traverser, Closure $resolveName): ?TypeIdentifier{
    $beforeStart = $traverser->save();

    CONSUME_TYPE_VARIABLE:
    {
        $traverser->consume("\s*");
        $typeVariable = $traverser->consume('\$[a-zA-Z_][a-zA-Z_0-9]*');
        if($typeVariable === NULL){
            $traverser->rollback($beforeStart);
        }else{
            return new TypeVariableIdentifier($typeVariable);
        }
    }

    CONSUME_TYPE_NAME:
    {
        $traverser->consume("\s*");

        $typeName = $traverser->consume('[a-zA-Z_][a-zA-Z_0-9]*(?:\\[a-zA-Z_][a-zA-Z_0-9]*)*');
        if($typeName === NULL){
            $traverser->rollback($beforeStart);
            return NULL;
        }
        $resolvedTypeName = $resolveName($typeName);
        $afterTypeName = $traverser->save();
    }

    CONSUME_TYPE_ARGUMENTS:
    {
        if($traverser->consume("\s*<") === NULL){
            return new NamedTypeIdentifier($resolvedTypeName, []);
        }
        $typeArguments = [];
    }

    CONSUME_TYPE_ARGUMENT:
    {
        $traverser->consume("\s*");
        $argument = makeTypeIdentifierFromString($traverser, FALSE, $resolveName);
        if($argument === NULL){
            $traverser->rollback($afterTypeName);
            return new NamedTypeIdentifier($resolvedTypeName, []);
        }
        $typeArguments[] = $argument;
    }

    TRY_FINISH:
    {
        if($traverser->consume("\s*,\s*>") !== NULL || $traverser->consume("\s*>") !== NULL){
            return new NamedTypeIdentifier($resolvedTypeName, $typeArguments);
        }elseif($traverser->consume("\s*,") !== NULL){
            goto CONSUME_TYPE_ARGUMENT;
        }else{
            $traverser->rollback($afterTypeName);
            return new NamedTypeIdentifier($resolvedTypeName, []);
        }
    }
}

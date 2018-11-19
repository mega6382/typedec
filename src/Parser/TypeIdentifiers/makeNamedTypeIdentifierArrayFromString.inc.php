<?php declare(strict_types = 1); // atom

namespace Netmosfera\Atlas\Extraction\Factories\FromString\TypeIdentifiers;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Closure;
use Netmosfera\Atlas\Model\Identifiers\NamedTypeIdentifier;
use Netmosfera\Atlas\Extraction\Factories\Tools\Traverser;
use function Netmosfera\Atlas\Model\Identifiers\Statics\staticNamedIdentifierFromString;
use function substr;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function makeNamedTypeIdentifierArrayFromString(Traverser $traverser, Closure $resolveName){
    $beforeStart = $traverser->save();

    $traverser->consume("\s*");

    $typeName = $traverser->consume('[a-zA-Z_][a-zA-Z_0-9]*(?:\\[a-zA-Z_][a-zA-Z_0-9]*)*\[\]');
    if($typeName === NULL){
        $traverser->rollback($beforeStart);
        return NULL;
    }
    $typeName = $resolveName(substr($typeName, 0, -2));

    return new NamedTypeIdentifier(staticNamedIdentifierFromString("Array"), [
        new NamedTypeIdentifier(staticNamedIdentifierFromString("Int")),
        new NamedTypeIdentifier($typeName)
    ]);
}

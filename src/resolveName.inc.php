<?php declare(strict_types = 1); // atom

namespace Netmosfera\Atlas\Extraction\Factories\NameResolution;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\Atlas\Model\Identifiers\Statics\StaticNamedIdentifier;
use function Netmosfera\Atlas\Model\Identifiers\Statics\staticNamedIdentifierFromString;
use PhpParser\Node\Name;
use PhpParser\BuilderHelpers;
use PhpParser\Node\Name\Relative;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Name\FullyQualified;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

function resolveName(
    String $name,
    Array $uses,
    Namespace_ $currentNS,
    ?String $selfValue = NULL
): StaticNamedIdentifier{
    /** @var String[] $uses */

    // @TODO improve this - can avoid using php parser

    if(($name === "self" || $name === "static") && $selfValue !== NULL){
        $s = new FullyQualified($selfValue);
        return staticNamedIdentifierFromString($s->toString());
    }

    $name = BuilderHelpers::normalizeName($name);

    if($name instanceof FullyQualified){
        return staticNamedIdentifierFromString($name->toString());
    }

    if($name instanceof Relative){
        $namespaceParts = $currentNS->name === NULL ? [] : $currentNS->name->parts;
        $parts = array_merge($namespaceParts, $name->parts);
    }else{
        $key = $name->getFirst();
        if(isset($uses[$key])){
            $baseParts = (new Name($uses[$key]))->parts;
            $parts = array_merge($baseParts, array_slice($name->parts, 1));
        }else{
            $namespaceParts = $currentNS->name === NULL ? [] : $currentNS->name->parts;
            $parts = array_merge($namespaceParts, $name->parts);
        }
    }

    $s = new FullyQualified($parts);
    return staticNamedIdentifierFromString($s->toString());
}


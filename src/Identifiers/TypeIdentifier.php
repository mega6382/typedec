<?php declare(strict_types = 1); // atom

namespace Netmosfera\Atlas\Model\Identifiers;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

interface TypeIdentifier
{
    function equals($other): Bool;
}


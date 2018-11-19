<?php declare(strict_types = 1); // atom

namespace Netmosfera\Atlas\Extraction\Factories\Tools;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class Traverser
{
    private $data;

    private $offset;

    private $preview;

    public function __construct(String $data){
        $this->data = $data;
        $this->offset = 0;
    }

    public function save(): Int{
        return $this->offset;
    }

    public function rollback(Int $offset){
        $this->offset = $offset;
        $this->preview = substr($this->data, $this->offset);
    }

    public function consume(String $regexp): ?String{
        $result = preg_match("/^" . $regexp . "/s", substr($this->data, $this->offset), $matches);
        if($result === 1){
            $this->offset += strlen($matches[0]);
            $this->preview = substr($this->data, $this->offset);
            return $matches[0];
        }
        return NULL;
    }
}

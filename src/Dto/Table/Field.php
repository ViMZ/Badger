<?php

namespace App\Dto\Table;

class Field
{
    public function __construct(
        public string $name,
        public \Closure $valueCallback
    ) {
    }
}

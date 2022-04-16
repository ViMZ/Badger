<?php

namespace App\Dto\Table;

class Action
{
    public function __construct(
        public string $label,
        public string $url
    ) {
    }
}

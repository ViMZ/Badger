<?php

namespace App\Dto\Menu;

class Menu
{
    public array $entries;

    public function __construct(
        array $entries = [],
    ) {
        $this->entries = $entries;
    }

    public function add(Entry $entry): self
    {
        $this->entries[] = $entry;

        return $this;
    }
}

<?php

namespace App\Dto\Menu;

class Entry
{
    public function __construct(
        public string $name,
        public string $route,
    ) {
        $this->name = $name;
        $this->route = $route;
    }
}

<?php

namespace App\Dto\Table;

class Table
{
    public array $items;

    /** @var Field[]|array */
    public array $fields;

    /** @var Action[]|array */
    public array $actions;

    public function __construct(
        array $items,
        array $fields = [],
        array $actions = []
    ) {
        $this->items = $items;
        $this->fields = $fields;
        $this->actions = $actions;
    }

    public function add(string $fieldName, \Closure $fieldValueCallback): self
    {
        $this->fields[] = new Field($fieldName, $fieldValueCallback);

        return $this;
    }

    public function addAction(string $label, string $url): self
    {
        $this->actions[] = new Action($label, $url);

        return $this;
    }
}

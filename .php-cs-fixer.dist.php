<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var');

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'yoda_style' => false,
        'linebreak_after_opening_tag' => true,
        'ordered_imports' => true,
        'phpdoc_order' => true,
        'array_syntax' => [
            'syntax' => 'short',
        ],
        'blank_line_before_statement' => true,
        '@DoctrineAnnotation' => true,
        '@PHP70Migration' => true,
        '@PHP71Migration' => true,

    ])
    ->setFinder($finder);

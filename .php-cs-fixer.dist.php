<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = new Finder()
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->exclude([
        'var',
        'vendor',
        'node_modules',
        'docker',
        'data',
    ])
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return new Config()
    ->setRiskyAllowed(true)
    ->setFinder($finder)
    ->setUsingCache(true)
    ->setCacheFile(__DIR__ . '/var/.php-cs-fixer.cache')
    ->setRules([
        // Base Symfony standard
        '@Symfony' => true,
        '@Symfony:risky' => true,
        // PHP 8.4
        '@PHP84Migration' => true,
        // Strict types & quality
        'declare_strict_types' => true,
        'strict_comparison' => true,
        'strict_param' => true,
        // Imports
        'no_unused_imports' => true,
        'ordered_imports' => [
            'imports_order' => ['class', 'function', 'const'],
            'sort_algorithm' => 'alpha',
        ],
        // Arrays
        'array_syntax' => ['syntax' => 'short'],
        'normalize_index_brace' => true,
        // Visibility
        'visibility_required' => [
            'elements' => ['method', 'property', 'const'],
        ],
        // Modern PHP
        'native_function_invocation' => [
            'include' => [],
        ],
        'modernize_strpos' => true,
        'modernize_types_casting' => true,
        // Clean code
        'no_superfluous_phpdoc_tags' => true,
        'no_empty_phpdoc' => true,
        'phpdoc_align' => ['align' => 'left'],
        'phpdoc_trim' => true,
        // Class and methods
        'class_attributes_separation' => [
            'elements' => [
                'const' => 'one',
                'method' => 'one',
                'property' => 'one',
            ],
        ],
        // Simplification code
        'simplified_if_return' => false,
        'simplified_null_return' => false,
        // Readable
        'binary_operator_spaces' => [
            'default' => 'single_space',
            'operators' => [
                '=>' => 'single_space',
                '=' => 'single_space',
                '|' => 'single_space',
                '&' => 'single_space',
            ],
        ],
        // Misc
        'single_quote' => true,
        'trailing_comma_in_multiline' => [
            'elements' => ['arrays', 'arguments', 'parameters'],
        ],
        'no_extra_blank_lines' => true,
        'blank_line_after_namespace' => true,
        'blank_line_after_opening_tag' => true,
    ])
    ->setFinder($finder);

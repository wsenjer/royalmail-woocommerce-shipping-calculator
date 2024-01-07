<?php

declare(strict_types=1);

use Isolated\Symfony\Component\Finder\Finder;

return [
    'prefix' => 'WPRubyRoyalMail\\Build', // Replace with your desired prefix.
    'exclude-namespaces' => [
        'Bamarni\Composer',
        'CoenJacobs\Mozart',
        'League',
        'Psr\Container',
        'Symfony\Component',
    ],
    'finders' => [
        Finder::create()
            ->files()
            ->in('vendor') // This assumes your dependencies are in the 'vendor' directory.
            ->exclude(['tests', 'Tests', 'vendor-bin'])
            ->name('*.php')
            ->ignoreVCS(true),

    ],
];

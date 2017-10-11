<?php

return [
    'env' => 'dev',
    'settings' => [

        'displayErrorDetails' => true,

        'view' => [
            'templates_path' => dirname(__DIR__) . '/src/App/View',
            'twig' => [
                //'cache' => dirname(__DIR__) . '/var/cache/twig',
            ]
        ],

        'monolog' => [
            'name' => 'app',
            'path' => dirname(__DIR__) . '/var/logs/prod.log'
        ]

    ]
];

<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
$appEnv = env('APP_ENV', 'dev');
if ($appEnv == 'dev') {
    $formatter = [
        'class'       => \Monolog\Formatter\LineFormatter::class,
        'constructor' => [
            'format'                => "||%datetime%||%channel%||%level_name%||%message%||%context%||%extra%\n",
            'allowInlineLineBreaks' => true,
            'includeStacktraces'    => true,
        ],
    ];
} else {
    $formatter = [
        'class'       => \Monolog\Formatter\JsonFormatter::class,
        'constructor' => [],
    ];
}


return [
    'default' => [
        'handlers' => [
            [   #'class' => Monolog\Handler\StreamHandler::class,
                #'stream' => BASE_PATH . '/runtime/logs/hyperf.log',
                # 按日期滚动
                'class'       => Monolog\Handler\RotatingFileHandler::class,
                'constructor' => [
                    'filename' => BASE_PATH . '/runtime/logs/hyperf.log',
                    'level'    => Monolog\Logger::INFO,
                ],
                'formatter'   => $formatter,
            ],
            [
                # 按日期滚动
                'class'       => Monolog\Handler\RotatingFileHandler::class,
                'constructor' => [
                    'filename' => BASE_PATH . '/runtime/logs/hyperf-debug.log',
                    'level'    => Monolog\Logger::DEBUG,
                ],
                'formatter'   => $formatter,
            ],
            [
                # 按日期滚动
                'class'       => Monolog\Handler\RotatingFileHandler::class,
                'constructor' => [
                    'filename' => BASE_PATH . '/runtime/logs/hyperf-error.log',
                    'level'    => Monolog\Logger::ERROR,
                ],
                'formatter'   => $formatter,
            ],
        ],
    ]
];

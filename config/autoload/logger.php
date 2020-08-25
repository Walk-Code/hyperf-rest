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
return [
    'default' => [
        'handlers' => [
            [   #'class' => Monolog\Handler\StreamHandler::class,
                # 按日期滚动
                'class'       => Monolog\Handler\RotatingFileHandler::class,
                'constructor' => [
                    #'stream' => BASE_PATH . '/runtime/logs/hyperf.log',
                    'filename' => BASE_PATH . '/runtime/logs/hyperf.log',
                    'level'  => Monolog\Logger::INFO,
                ],
                'formatter'   => [
                    'class'       => Monolog\Formatter\LineFormatter::class,
                    'constructor' => [
                        'format'                => null,
                        'dateFormat'            => 'Y-m-d H:i:s',
                        'allowInlineLineBreaks' => true,
                    ],
                ],
            ],
            [
                # 按日期滚动
                'class'       => Monolog\Handler\RotatingFileHandler::class,
                'constructor' => [
                    #'stream' => BASE_PATH . '/runtime/logs/hyperf-debug.log',
                    'filename' => BASE_PATH . '/runtime/logs/hyperf-debug.log',
                    'level'  => Monolog\Logger::DEBUG,
                ],
                'formatter'   => [
                    'class'       => Monolog\Formatter\LineFormatter::class,
                    'constructor' => [
                        'format'                => null,
                        'dateFormat'            => 'Y-m-d H:i:s',
                        'allowInlineLineBreaks' => true,
                    ],
                ],
            ],
        ],
    ],
];

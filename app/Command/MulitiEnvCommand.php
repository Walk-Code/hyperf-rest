<?php

declare(strict_types=1);

namespace App\Command;

use Dotenv\Dotenv;
use Dotenv\Repository\Adapter\EnvConstAdapter;
use Dotenv\Repository\Adapter\PutenvAdapter;
use Dotenv\Repository\RepositoryBuilder;
use Hyperf\Command\Command as HyperfCommand;
use Psr\Container\ContainerInterface;
use Swoole\Process;
use Symfony\Component\Console\Input\InputArgument;

/**
 * @Command
 */
class MulitiEnvCommand extends HyperfCommand {
    /**
     * @var ContainerInterface
     */
    protected $container;

    protected $name = "run:env";

     public function configure() {
         parent::configure();
         $this->setDescription('Hyperf run environment');
     }

    public function handle() {
        //$argument = $this->input->getArgument('name') ?? 'dev';
        //$this->line('Hello ' . $argument, 'info');
        $this->start();
        //$this->setEnv($argument);
    }

    protected function getArguments() {
        return [
            ['name', InputArgument::OPTIONAL, '获取应用程序运行环境'],
        ];
    }

    /**
     * 基于swoole process启动项目
     */
    private function start(){
        $this->clearRuntimeContainer();
        $process = new Process(function (Process $childProcess) {
            $argc = [BASE_PATH.'/bin/hyperf.php', 'start'];
            $php = exec('which php');
            $childProcess->exec($php, $argc);
        });

        # 通过commands的参数来选择指定环境下的配置文件
        //$argument = $this->input->getArgument('name') ?? 'dev';


        return $process->start();
    }

    /**
     * 清除运行容器
     */
    private function clearRuntimeContainer() {
        exec('rm -rf '. BASE_PATH.'/runtime/container');
    }

    /**
     * 通过common设置环境变量
     * @param $env
     */
    private function setEnv($env) {
        if (file_exists(BASE_PATH . '/.env.' . $env)) {
            # 构建自定义repository
            $repository = RepositoryBuilder::create()
                ->withReaders([
                    new EnvConstAdapter(),
                ])->withWriters([
                    new EnvConstAdapter(),
                    new PutenvAdapter(),
                ])->immutable()
                ->make();

            Dotenv::create($repository, [BASE_PATH], '.env.' . $env)->load();
        }
    }
}

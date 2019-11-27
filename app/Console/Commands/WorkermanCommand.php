<?php

namespace App\Console\Commands;

use App\Common\WorkermanHandler;
use Illuminate\Console\Command;
use Workerman\Worker;

class WorkermanCommand extends Command
{
    private $server;
    private $port = "20480";
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workerman {action} {-d?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'the workerman server';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        global $argv;
        $arg = $this->argument('action');
        $argv[1] = $argv[2];
        $argv[2] = isset($argv[3]) ? "-{$argv[3]}" : '';

        switch ($arg) {
            case 'start':
                $this->start();
                break;
            case 'stop':
                $this->stop();
                break;
            case 'restart':
                $this->restart();
                break;
            case 'reload':
                $this->reload();
                break;
        }
    }

    private function start(){
        // 创建一个Worker监听20002端口，不使用任何应用层协议
        $this->server = new Worker("websocket://0.0.0.0:$this->port");
        // 启动4个进程对外提供服务
        $this->server->count = 4;
        $handler = new WorkermanHandler();
        // 连接时回调
        $this->server->onConnect = [$handler, 'onConnect'];
        // 收到客户端信息时回调
        $this->server->onMessage = [$handler, 'onMessage'];
        // 进程启动后的回调
        $this->server->onWorkerStart = [$handler, 'onWorkerStart'];
        // 断开时触发的回调
        $this->server->onClose = [$handler, 'onClose'];
        // 运行worker
        Worker::runAll();
    }
    private function stop(){
        $worker = new Worker("tcp://0.0.0.0:$this->port");
        // 设置此实例收到reload信号后是否reload重启
        $worker->reloadable = false;
        $worker->onWorkerStop = function($worker)
        {
            echo "Worker stop...\n";
        };
        // 运行worker
        Worker::runAll();
    }
    private function restart(){
        $worker = new Worker("tcp://0.0.0.0:$this->port");
        // 设置此实例收到reload信号后是否reload重启
        $worker->reloadable = true;
        $worker->onWorkerStart = function($worker)
        {
            echo "Worker restart...\n";
        };
        // 运行worker
        Worker::runAll();
    }
    private function reload(){
        $worker = new Worker("tcp://0.0.0.0:$this->port");
        // 设置此实例收到reload信号后是否reload重启
        $worker->reloadable = false;
        $worker->onWorkerStart = function($worker)
        {
            echo "Worker reload...\n";
        };
        // 运行worker
        Worker::runAll();
    }


}

<?php


namespace App\Console\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestCommand extends Command
{
    /**
     * 命令行执行命令
     * @var string
     */
    protected $signature = 'test:run';

    /**
     * 命令描述
     *
     * @var string
     */
    protected $description = 'test command';

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
     * @return void
     */
    public function handle(): void
    {
        Log::info(__METHOD__);
    }
}

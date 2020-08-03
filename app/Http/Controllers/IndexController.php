<?php


namespace App\Http\Controllers;


use App\Events\TestEvent;
use App\Table\UserTable;
use App\Tasks\TestTask;
use App\Utils\MessageTrait;
use Hhxsv5\LaravelS\Swoole\Task\Event;
use Hhxsv5\LaravelS\Swoole\Task\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IndexController extends Controller
{
    use MessageTrait;

    protected $userTable;

    public function index(Request $request)
    {
        return view('index');
    }

    /**
     * 控制器中推送数据
     *
     * @param Request $request
     * @return array
     */
    public function push(Request $request): array
    {
        $msg = $request->input('msg', 'Push data to websocket in Controller');

        $this->deliver($msg);

        $this->userTable = new UserTable();

        $users = $this->userTable->all();
        if (!empty($users)) {
            $user = $users[array_rand($users)];

            $this->pushMessage(app('swoole'), $user['fd'], $msg, 'message');
        }

        $this->fire($msg);

        return ['code' => 200, 'msg' => $msg];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function test(Request $request): array
    {
        return [
            $request->all(),  // 请求参数
            $request->server->all() // 服务器参数
        ];
    }

    /**
     * @param string $msg
     * @return bool
     */
    private function deliver($msg = 'task data'): bool
    {
        // 实例化TestTask并通过deliver投递，此操作是异步的，投递后立即返回，由Task进程继续处理TestTask中的handle逻辑
        $task = new TestTask($msg);

        $task->delay(1); // 延迟3秒投递任务

        $task->setTries(3); // 出现异常时，累计尝试3次

        Log::info(__METHOD__, [$msg]);

        return Task::deliver($task);
    }

    /**
     * @param string $msg
     * @return bool
     */
    private function fire($msg = 'event data'): bool
    {
        // 实例化TestEvent并通过fire触发，此操作是异步的，触发后立即返回，由Task进程继续处理监听器中的handle逻辑
        $event = new TestEvent($msg);

        $event->delay(2); // 延迟10秒触发

        $event->setTries(3); // 出现异常时，累计尝试3次

        Log::info(__METHOD__, [$msg]);

        return Event::fire($event);
    }
}

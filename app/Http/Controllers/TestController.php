<?php

namespace App\Http\Controllers;

use App\User;
use Constant;
use stdClass;
use Exception;
use Aws\Sqs\SqsClient;
use App\Contracts\Queue;
use Aws\Laravel\AwsFacade;
use App\Jobs\ProcessPodcast;
use Illuminate\Http\Request;
use App\Implementations\Message;
use App\Services\Facades\AppLog;
use App\Services\ExporterService;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    private $exporterService;
    private $queue;

    public function __construct(ExporterService $exporterService, Queue $queue)
    {
        $this->exporterService = $exporterService;
        $this->queue = $queue;
    }

    public function __invoke($page)
    {
        $metaTitle = __('titles_'.$page);
        if ($metaTitle == 'titles_'.$page) {
            $metaTitle = null;
        }
        //var_dump($page, request()->segments(1));
        return view('pages.'.$page, ['metaTitle' => $metaTitle]);
    }

    public function update(User $user, Request $request)
    {
        $user->update($request->all());
        return redirect()->route('admin.users.index');
    }

    public function download()
    {
        return $this->exporterService->sales();
    }

    public function testLog()
    {
        User::create([
            'name' => 'testing'.time(),
            'email' => 'testing'.time().'@gmail.com',
            'email_verified_at' => now(),
            'password' => 'p12345678', // password
        ]);
        Log::info('Log Facade');
        AppLog::web()->warning('Testing log 1 xxxxxxxxxxxxxxxxx......................');
        AppLog::api()->debug('Testing log 1......................');
        AppLog::web()->warning('Testing log 2 xxxxxxxxxxxxxxxxx......................');
        AppLog::api()->debug('Testing log 2......................');
        AppLog::info('Common Test Logging...');
        AppLog::warning('Common Test Logging warning...');
        AppLog::api()->warning('Testing log api warning......................');
        echo 'Testing log...';

        $permission = $this->permission();
        dd(
            // $permission->canRead,
            // $permission->canCreate,
            // $permission->canUpdate,
            // $permission->canDelete,
            '------------- sales_report ===> sales_month -----------',
            $permission->screens()->sales_report()->applyField('sales_month')->canRead,
            $permission->screens()->sales_report()->applyField('sales_month')->canCreate,
            $permission->screens()->sales_report()->applyField('sales_month')->canUpdate,
            $permission->screens()->sales_report()->applyField('sales_month')->canDelete,
            '------------- root permission -----------',
            $permission->root()->canRead,
            $permission->root()->canCreate,
            $permission->root()->canUpdate,
            $permission->root()->canDelete,
            '------------- sales_report ===> sales_year -----------',
            $permission->screens()->sales_report()->applyField('sales_year')->canRead,
            $permission->screens()->sales_report()->applyField('sales_year')->canCreate,
            $permission->screens()->sales_report()->applyField('sales_year')->canUpdate,
            $permission->screens()->sales_report()->applyField('sales_year')->canDelete,
            '------------- sales_report -----------',
            $permission->screens()->sales_report()->canRead,
            $permission->screens()->sales_report()->canCreate,
            $permission->screens()->sales_report()->canUpdate,
            $permission->screens()->sales_report()->canDelete,
            '------------- sales_report_detail -----------',
            $permission->screens()->sales_report_detail()->canRead,
            $permission->screens()->sales_report_detail()->canCreate,
            $permission->screens()->sales_report_detail()->canUpdate,
            $permission->screens()->sales_report_detail()->canDelete,

            $permission->applyField('test')->canDelete,
        );
    }

    public function permission()
    {
        $actions = [
            'canRead'   => 'read',
            'canCreate' => 'create',
            'canUpdate' => 'update',
            'canDelete' => 'delete',
        ];
        $roles = [
            'admin' => [
                'read' => true,
                'create' => true,
                'update' => true,
                'delete' => true,
            ],
            'manager' => [
                'read' => true,
                'create' => true,
                'update' => true,
                'delete' => true,
            ],
            'leader' => [
                'read' => true,
                'create' => true,
                'update' => true,
                'delete' => false,
            ],
            'account' => [
                'read' => true,
                'create' => false,
                'update' => false,
                'delete' => false,
            ],
        ];
        $screens = [
            'sales_report' => [
                'read'   => ['admin', 'manager', 'leader', 'account'],
                'create' => ['admin', 'manager', 'leader'],
                'update' => ['admin', 'manager', 'leader'],
                'delete' => ['admin', 'manager'],
                'fields' => [
                    'sales_month' => [
                        'read'   => ['admin', 'manager', 'leader', 'account'],
                        'create' => ['admin', 'manager', 'leader'],
                        'update' => ['admin', 'manager'],
                        'delete' => ['admin', 'manager'],
                    ],
                    'sales_year' => [
                        'read'   => ['admin', 'manager', 'leader', 'account'],
                        'create' => ['admin'],
                        'update' => ['admin'],
                        'delete' => ['admin'],
                    ]
                ]
            ],
            'sales_report_detail' => [
                'fiel'
            ]
        ];

        $currentRole = 'leader';

        return new class ($actions, $roles, $screens, $currentRole) {
            private $_actions = [];
            private $_roles = [];
            private $_screens = [];
            private $_currentRole = 'leader';
            private $_current_method = null;
            private $deps = '';

            /**
             * __construct
             *
             * @param  mixed $actions
             * @param  mixed $roles
             * @param  mixed $screens
             * @param  mixed $currentRole
             * @return void
             */
            public function __construct($actions, $roles, $screens, $currentRole)
            {
                $this->_actions = $actions;
                $this->_roles = $roles;
                $this->_screens = $screens;
                $this->_currentRole = $currentRole;
            }

            /**
             * __call
             *
             * @param  mixed $method
             * @param  mixed $arguments
             * @return void
             */
            public function __call($method, $arguments)
            {
                $this->_current_method = $method;
                if (array_key_exists($this->_current_method, $this->_screens)) {
                    $this->deps = "screens.{$this->_current_method}";
                    $this->_setPermissions($this->_screens[$this->_current_method]);
                } else if (in_array($this->_current_method, ['applyField', 'forField', 'onlyField'])) {
                    if (empty($arguments) || !is_string($arguments[0]) || empty($arguments[0])) {
                        throw new Exception("The '{$this->_current_method}' method must has a non empty argument.");
                    }
                    if ($this->deps) {
                        $parts = explode('.', $this->deps);
                        $s = $this->_screens;
                        foreach ($parts as $k => $dep) {
                            if ($k > 0) {
                                $s = $s[$dep];
                            }
                        }
                        if (array_key_exists('fields', $s)) {
                            $fields = $s['fields'][$arguments[0]];
                            $this->_setPermissions($fields);
                        } else {
                            //throw new Exception("Key [fields] not exist in {$this->deps}");
                        }
                    }
                } else {
                    $this->deps = '';
                    $this->_setPermissions();
                }
                return $this;
            }

            /**
             * screens
             *
             * @return void
             */
            public function screens()
            {
                return $this;
            }

            /**
             * _setPermissions
             *
             * @param array $permissions
             * @return void
             */
            private function _setPermissions($permissions = [])
            {
                $permissions = !empty($permissions) ? $permissions : $this->_roles[$this->_currentRole];
                foreach ($this->_actions as $permission => $action) {
                    if (array_key_exists($action, $permissions)) {
                        $this->$permission = $permissions[$action];
                    } else {
                        $this->$permission  = true;
                    }
                }
            }

        };
    }

    public function testSQS3()
    {
        $resultSend = true;
        $message = ['message' => 'Test SQS Queue FiFo '.date('Y-m-d H:i:s')];

        try {
            dispatch(new ProcessPodcast($message))->onQueue(env('SQS_QUEUE_FIFO'));
        } catch (\Throwable $e) {
            $message['message'] = $e->getMessage();
            $resultSend = false;
        }

        session()->put('sqs', json_encode([
            'sent' => [
                'type' => 'Aws SQS Queue Fifo',
                'success' => $resultSend,
                'message' => $message['message'],
            ],
        ], JSON_PRETTY_PRINT));

        return redirect()->route('home');
    }

    public function testSQS()
    {
        $message = ['message' => 'Hello, SQS Queue '.date('Y-m-d H:i:s')];
        $resultSend = $this->queue->fifo()->send(new Message($message));

        $resultReceive = $this->queue->fifo()->receive();
        $dataReceive = $resultReceive->data ?? null;

        if ($resultReceive) {
            try {
                $resultReceive->process();
                $this->queue->fifo()->delete($resultReceive);
            } catch (\Throwable $e) {
                $this->queue->fifo()->release($resultReceive);
                echo $e->getMessage();
            }
        }

        session()->put('sqs', json_encode([
            'sent' => [
                'type' => 'Aws SQS Queue',
                'success' => $resultSend,
                'message' => $resultSend ? $message['message'] : 'Message could not sent.',
            ],
            'receive' => [
                'type' => 'Aws SQS Queue',
                'success' => !!$resultReceive,
                'message' => $dataReceive,
            ]
        ], JSON_PRETTY_PRINT));

        return redirect()->route('home');
    }

    public function testSQS2()
    {
        //https://george.webb.uno/posts/aws-simple-queue-service-php-sdk
        //$sqs = app()->make('aws')->createClient('sqs');
        //$sqs2 = AwsFacade::createClient('sqs', ['delay' => 2000]);
        $sqs_credentials = config('aws');

        // Instantiate the client
        $client = new SqsClient($sqs_credentials);

        // Create the queue
        // $queue_options = [
        //     'QueueName' =>  env('SQS_QUEUE')
        // ];
        // $client->createQueue($queue_options);

        // Get the queue URL from the queue name.
        $queue_url = $client->getQueueUrl(['QueueName' => env('SQS_QUEUE')])->get('QueueUrl');

        // The message we will be sending
        $message = ['message' => 'Hello, SQS Queue '.date('Y-m-d H:i:s')];

        // Send the message
        $resultSend = $client->sendMessage([
            'QueueUrl' => $queue_url,
            'MessageBody' => json_encode($message),
            //'MessageGroupId' => uniqid(), // fifo
            //'MessageDeduplicationId' => uniqid(), // fifo
        ]);

        // Receive a message from the queue
        $resultReceive = $client->receiveMessage([
            'QueueUrl' => $queue_url
        ]);

        if ($resultReceive['Messages'] == null) {
            // No message to process
            dd('No message to process');
            exit;
        }

        // Get the message information
        // $result_message = array_pop($resultReceive['Messages']);
        // $queue_handle = $result_message['ReceiptHandle'];
        // $message_json = $result_message['Body'];

        // Process receive message

        // Delete message from aws sqs queue
        // $client->deleteMessage([
        //     'QueueUrl' => $queue_url,
        //     'ReceiptHandle' => $queue_handle
        // ]);


        // Dealing with failures
        // $client->changeMessageVisibility([
        //     'QueueUrl' => $queue_url,
        //     'ReceiptHandle' => $queue_handle,
        //     'VisibilityTimeout' => 0
        // ]);

        session()->put('sqs', json_encode([
            'send' => [
                'message' => $message,
                'result' => $resultSend->get('MessageId')
            ],
            'receive' => $resultReceive['Messages']
        ], JSON_PRETTY_PRINT));

        return redirect()->route('home');
    }

    public function testValidation()
    {

    }
}

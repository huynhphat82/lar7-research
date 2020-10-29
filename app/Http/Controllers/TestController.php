<?php

namespace App\Http\Controllers;

use App\Services\ExporterService;
use App\Services\Facades\AppLog;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use stdClass;

class TestController extends Controller
{
    private $exporterService;

    public function __construct(ExporterService $exporterService)
    {
        $this->exporterService = $exporterService;
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
                } else if ($this->_current_method === 'applyField') {
                    if (empty($arguments) || !is_string($arguments[0]) || empty($arguments[0])) {
                        throw new Exception("The 'applyField' method must has a non empty argument.");
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
}

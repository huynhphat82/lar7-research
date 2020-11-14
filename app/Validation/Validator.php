<?php

namespace App\Validation;

use Constant;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class Validator
{
    /**
     * validate
     *
     * @param  mixed $params
     * @return void
     */
    public function validate($input = [])
    {
        if (empty($input)) {
            $input = request()->all();
        }

        $validator = FacadesValidator::make($input, $this->rules(), $this->messages(), $this->attributes());

        if ($validator->fails()) {
            return $validator;
        }

        return true;
    }

    public function rules()
    {
        return [];
    }

    public function messages()
    {
        return [];
    }

    public function attributes()
    {
        return [];
    }

    /**
     * Validate automatically
     *
     * @param  \Illuminate\Http\Request $request
     * @return mixed
     */
    public static function autovalidate($request = null)
    {
        $request = $request ?: request();
        $requestMethod = $request->getMethod();
        $controllerAction = $request->route()->getActionMethod();
        $action = $request->route()->getAction();

        if (!array_key_exists('controller', $action)) {
            return true;
        }

        // Check controlle action
        $nameActionParts = explode(DIRECTORY_SEPARATOR, $request->route()->getActionName());
        $nameAction = end($nameActionParts);
        $splits = explode('@', $nameAction);
        if (count($splits) != 2) {
            $controllerAction = '';
        }
        // Create validation file
        $controllerParts = explode(DIRECTORY_SEPARATOR, get_class($request->route()->getController()));
        $controller = preg_replace('/Controller$/i', '', end($controllerParts));
        // RequestMethod + ControllerName (without 'Controller' postfix) + ControllerAction + 'Request'
        $classValidation = ucfirst(strtolower($requestMethod)).ucfirst($controller).ucfirst($controllerAction).'Request';
        $requestPath = self::_resolveRequestPath();
        $pathFileValidation = base_path($requestPath['path']).DIRECTORY_SEPARATOR.$classValidation.'.php';
        // Check validation file
        if (file_exists($pathFileValidation)) {
            //include($pathFileValidation);
            //$classValidation = "\App\Validation\\{$classValidation}";
            //return (new $classValidation)->validate();
            return app()->make("{$requestPath['namespace']}\\{$classValidation}")->validate();
        } else {
            Log::warning("[Validation][Api] => File [{$pathFileValidation}] not exist. It is ignored.");
        }
        return true;
    }

    /**
     * Get request path
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    private static function _resolveRequestPath($request = null)
    {
        $mapping = config('mapping');
        $request = $request ?: request();
        $action = $request->route()->getAction();
        //$mappingKey = $request->route()->getActionName();

        $resovlePath = function ($config) {
            if (is_string($config)) {
                $config = [$config];
            }
            if (!is_array($config) || empty($config)) {
                throw new Exception('It must be a non-empty array or a string.');
            }
            if (count($config) === 1) {
                // build namespace of target validation class
                $config[] = implode('\\', array_map(function ($segment) {
                    return ucfirst($segment);
                }, explode('/', $config[0])));
            }
            return [
                'path' => $config[0],
                'namespace' => $config[1],
            ];
        };

        // 1. Priority 1st: controller@method
        $controllerMethodKey = $action['controller'];
        if (array_key_exists($controllerMethodKey, $mapping)) {
            return $resovlePath($mapping[$controllerMethodKey]);
        }
        // 2. Priority 2nd: controller
        $controller = $action['namespace'];
        if (array_key_exists($controller, $mapping)) {
            return $resovlePath($mapping[$controller]);
        }

        // 3. Priority 3rd: url is api
        if (isApi()) {
            return $resovlePath($mapping[Constant::API_REQUEST_PATH_KEY]);
        }
        // 4. Priority 4th: url is web
        return $resovlePath($mapping[Constant::WEB_REQUEST_PATH_KEY]);
    }

}

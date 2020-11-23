<?php

namespace App\Validation;

use Constant;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class Validator
{
    /**
     * Validate request
     *
     * @param  array $params
     * @return bool|\Illuminate\Contracts\Validation\Validator
     */
    public function validate($input = [])
    {
        if (empty($input)) {
            $input = !empty($this->input()) ? $this->input() : request()->all();
        }

        $validator = FacadesValidator::make($input, $this->rules(), $this->messages(), $this->attributes());

        if ($validator->fails()) {
            return $validator;
        }

        return true;
    }

    /**
     * Declare input for validation
     *
     * @return array
     */
    public function input()
    {
        return [];
    }

    /**
     * Define validation rules
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Customize error messages
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }

    /**
     * Cusomize the field names
     *
     * @return array
     */
    public function attributes()
    {
        return [];
    }

    /**
     * Validate request automatically
     *
     * @param  \Illuminate\Http\Request $request
     * @return mixed
     */
    public static function autovalidate($request = null)
    {
        $request = $request ?: request();
        $action = $request->route()->getAction();
        // if use closure, ignore validation
        if (!array_key_exists('controller', $action)) {
            Log::warning("[Validation][Api] => Controller is not used (Closure). Validation is ignored.");
            return true;
        }
        // Create validation class name
        $classValidation = self::_buildValidationClassName($request);
        // Resolve path & namspace of validation class
        $requestPath = self::_resolveRequestPath($request);
        $pathFileValidation = base_path($requestPath['path']).DIRECTORY_SEPARATOR.$classValidation.'.php';
        // Check validation file
        if (file_exists($pathFileValidation)) {
            // include($pathFileValidation);
            // $classValidation = "{$requestPath['namespace']}\\{$classValidation}";
            // return (new $classValidation)->validate();
            return app()->make("{$requestPath['namespace']}\\{$classValidation}")->validate();
        }
        Log::warning("[Validation][Api] => File [{$pathFileValidation}] not exist. Validation is ignored.");
        return true;
    }

    /**
     * Resolve the request path & namespace
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     * @throws \Exception
     */
    private static function _resolveRequestPath($request = null)
    {
        $mapping = config('mapping');
        $request = $request ?: request();
        $action = $request->route()->getAction();
        // Resolve path & namespace for validation class
        $resovlePath = function ($config) {
            if (is_string($config)) {
                $config = [$config];
            }
            // case as api, check api version
            if ($config === null) {
                $prefix = ucfirst(strtolower(request()->segment(1)));
                $version = request()->segment(2);
                if (preg_match(\Constant::API_VERSION_PATTERN, $version)) {
                    $version = ucfirst(strtolower($version));
                    $config = ["app/{$prefix}/{$version}/Requests", "App\\{$prefix}\\{$version}\Requests"];
                } else {
                    $config = ["app/{$prefix}/Requests", "App\\{$prefix}\Requests"];
                }
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
            return $resovlePath(null);
        }
        // 4. Priority 4th: url is web
        return $resovlePath($mapping[Constant::WEB_REQUEST_PATH_KEY] ?? ['app/Http/Requests', 'App\Http\Requests']);
    }

    /**
     * Build validation class name automatically
     *
     * @param  \Illuminate\Http\Request $request
     * @return string
     */
    private static function _buildValidationClassName($request = null)
    {
        // Create validation file
        $request = $request ?: request();
        $requestMethod = $request->getMethod();
        $controllerAction = $request->route()->getActionMethod();
        $controllerParts = explode(DIRECTORY_SEPARATOR, get_class($request->route()->getController()));
        $controllerName = preg_replace('/Controller$/i', '', end($controllerParts));
        // RequestMethod + ControllerName (without 'Controller' postfix) + ControllerAction + 'Request'
        return ucfirst(strtolower($requestMethod)).ucfirst($controllerName).ucfirst($controllerAction).'Request';
    }

}

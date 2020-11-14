<?php

namespace App\Validation;

use Illuminate\Support\Facades\Log;
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
        }dd($input, $this->rules());

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
        $pathFileValidation = app_path($requestPath['path']).DIRECTORY_SEPARATOR.$classValidation.'.php';

        // Check validation file
        if (file_exists($pathFileValidation)) {
            //include($pathFileValidation);
            //$classValidation = "\App\Validation\\{$classValidation}";
            //return (new $classValidation)->validate();
            return app()->make("{$requestPath['namespace']}\\{$classValidation}")->validate();
        } else {
            Log::warning("[Validation][Api] => File [{$pathFileValidation}] not exist. It is ignored.");
        }
        return null;
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
        $mappingKey = $request->route()->getActionName();
        $action = $request->route()->getAction();
        $namespace = $action['namespace'];

        if (array_key_exists($mappingKey, $mapping)) {
            $path = $mapping[$mappingKey];
            if (is_string($path)) {
                $path = [$path];
            }
            if (count($path) === 1) {
                // build namespace of target validation class
                $path[] = implode('\\', array_map(function ($segment) {
                    return ucfirst($segment);
                }, explode('/', $path[0])));
            }
            return [
                'path' => $path[0],
                'namespace' => $path[1]
            ];
        }

        if (array_key_exists($namespace, $mapping)) {

        }
        return $mapping['default_request_path'];
    }
}

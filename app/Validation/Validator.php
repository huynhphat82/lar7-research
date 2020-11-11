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

    public static function startAuto($request = null)
    {
        $request = $request ?: request();
        $requestMethod = $request->getMethod();
        $controllerAction = $request->route()->getActionMethod();
        // Check controlle action
        $nameActionParts = explode(DIRECTORY_SEPARATOR, request()->route()->getActionName());
        $nameAction = end($nameActionParts);
        $splits = explode('@', $nameAction);
        if (count($splits) != 2) {
            $controllerAction = '';
        }
        // Create validation file
        $controllerParts = explode(DIRECTORY_SEPARATOR, get_class(request()->route()->getController()));
        $controller = preg_replace('/Controller$/i', '', end($controllerParts));
        // RequestMethod + ControllerName (without 'Controller' postfix) + ControllerAction + 'Request'
        $classValidation = ucfirst(strtolower($requestMethod)).ucfirst($controller).ucfirst($controllerAction).'Request';
        $pathFileValidation = app_path('Validation').DIRECTORY_SEPARATOR.$classValidation.'.php';

        // TODO: Create papping path to validation file
        // \App\Http\Controller\NameController =>  app_path('Request').FOLDER.VALIDATION_FILENAME

        // Check validation file
        if (file_exists($pathFileValidation)) {
            //include($pathFileValidation);
            //$classValidation = "\App\Validation\\{$classValidation}";
            //return (new $classValidation)->validate();
            return app()->make("\App\Validation\\{$classValidation}")->validate();
        } else {
            Log::warning("[Validation][Api] => File [{$pathFileValidation}] not exist. It is ignored.");
        }
        return null;
    }
}

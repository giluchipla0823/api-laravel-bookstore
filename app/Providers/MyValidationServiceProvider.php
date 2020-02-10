<?php
namespace App\Providers;

use App\Services\ValidationService;
use Illuminate\Support\ServiceProvider;

class MyValidationServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['validator']->resolver(function($translator, $data, $rules, $messages)
        {
            return new ValidationService($translator, $data, $rules, $messages);
        });

        $this->app['validator']->extend('array_key_is_string', function($attribute, $value, $parameters, $validator)
        {
            $keys = $validator->getExplicitKeys($attribute);
            return !is_numeric($keys[0]);
        }, 'El campo :attribute debe contener índices de array de tipo string');

        $this->app['validator']->extend('alpha_spaces', function($attribute, $value)
        {
            return preg_match('/^[\pL\s]+$/u', $value);
        });

        $this->app['validator']->extend('without_spaces', function($attribute, $value)
        {
            return preg_match('/^\S*$/u', $value);
        }, 'El campo :attribute no debe contener espacios en blanco');


        $this->app['validator']->extend('exists_relations', function($attribute, $value, $parameters)
        {
            $relations = explode(',', $value);

            foreach ($relations as $relation) {
                if(!in_array($relation, $parameters)){
                    return false;
                }
            }

            return true;

        }, 'El campo :attribute sólo puede contener los valores: :relations');

        $this->app['validator']->replacer('exists_relations', function($message, $attribute, $rule, $parameters, $validator){
            $relations = implode(', ', $parameters);

            return str_replace(':relations', $relations, $message);
        });


    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

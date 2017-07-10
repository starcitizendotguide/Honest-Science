<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        // Custom Validatio Rules
        //@TODO Depending on how many people sign-up, we wanna move this to a queue-job system
        // to protect the RSI website from too many requests.
        Validator::extend('rsi_handle', function($attribute, $value, $paramaters, $validator) {

            $client = new \GuzzleHttp\Client(['http_errors' => false]);
            $request = $client->request('GET', 'https://robertsspaceindustries.com/citizens/' . $value);
            return ($request->getStatusCode() === 200);

        });


    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

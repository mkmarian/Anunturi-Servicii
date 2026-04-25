<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Format implicit Carbon pentru afisare in view-uri (d-m-Y)
        Carbon::setToStringFormat(config('marketplace.date_display', 'd-m-Y'));

        // Regula de validare 'romanian_date' refolosibila in Form Requests
        // Accepta dd-mm-yyyy si transforma intern in Y-m-d pentru baza de date
        Validator::extend('romanian_date', function ($attribute, $value) {
            return (bool) \DateTime::createFromFormat('d-m-Y', $value);
        }, 'Formatul datei trebuie sa fie zz-ll-aaaa.');
    }
}

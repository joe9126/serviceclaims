<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('money', function ($amount) {

            return "<?php echo 'KES. ' . number_format($amount, 2); ?>";

        });

        Blade::directive('formatDate', function ($date) {
            return "<?php echo ($date)->Carbon::createFromFormat('Y-m-d', $date)->format('d-m-Y') ?>";
                        });
        
    }
}

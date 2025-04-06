<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;


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
        View::composer(["layouts.sidebar","calorie_record.record_index"], function ($view) {
            $user = auth()->user();
            $per_years = $user->calorieRecords()
                ->selectRaw("YEAR(date) as year, SUM(calorie_intake) as year_total_intake, SUM(calorie_burned) as year_total_burned")
                ->groupBy("year")
                ->orderBy("year", "desc")
                ->get();
            $per_months = $user->calorieRecords()
                ->selectRaw("YEAR(date) as year, MONTH(date) as month, SUM(calorie_intake) as month_total_intake, SUM(calorie_burned) as month_total_burned")
                ->groupBy("year", "month")
                ->orderBy("year", "desc")
                ->orderBy("month", "desc")
                ->get();

            //dd($per_months);
            //dd($per_years);

            $view->with('per_years', $per_years);
            $view->with('per_months', $per_months);

        });
    }
}

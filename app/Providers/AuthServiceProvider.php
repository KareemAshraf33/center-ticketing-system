<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use io3x1\FilamentTranslations\Models\Translation;
use App\Models\Activity;
use App\Models\Category;
use App\Models\Student;
use App\Models\Ticket;
use App\Models\User;
use App\Policies\ActivityPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\StudentPolicy;
use App\Policies\TicketPolicy;
use App\Policies\TranslationPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Ticket::class => TicketPolicy::class,
        User::class => UserPolicy::class,
        Student::class => StudentPolicy::class,
        Category::class => CategoryPolicy::class,
        Activity::class => ActivityPolicy::class,
        Translation::class => TranslationPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}

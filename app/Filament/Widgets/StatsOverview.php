<?php

namespace App\Filament\Widgets;

use App\Models\Activity;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Student;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Number of All Tickets', Ticket::all()->count()),
            Card::make('Number of New Tickets', Ticket::where('status','New')->get()->count()),
            Card::make('Number of Pending Tickets', Ticket::where('status','Pending')->get()->count()),
            Card::make('Number of Solved Tickets', Ticket::where('status','Solved')->get()->count()),
            Card::make('Number of Closed Tickets', Ticket::where('status','Closed')->get()->count()),
            Card::make('Number of All Students', Student::all()->count()),
            Card::make('Number of All Users', User::all()->count()),
            Card::make('Number of All Activities', Activity::all()->count()),
        ];
    }
}

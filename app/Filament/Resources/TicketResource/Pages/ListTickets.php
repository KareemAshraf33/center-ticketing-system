<?php

namespace App\Filament\Resources\TicketResource\Pages;

use Filament\Pages\Actions;
use Illuminate\Support\Facades\Log;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\TicketResource;
use Illuminate\Database\Eloquent\Builder; // Add this import statement

class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;

    public function query(): Builder
    {
        // Get the currently logged-in user
        $user = auth()->user();
    
        // Check if the user has the "Support" role
        if ($user && $user->role === 'Support') {
            // If the user is a support user, only fetch their assigned tickets
            $query = parent::query()->where('user_id', $user->id);
        } else {
            // If the user doesn't have the "Support" role, fetch all tickets
            $query = parent::query();
        }
    
        Log::info('Query Log: ' . $query->toSql());
    
        return $query;
    }
    
    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

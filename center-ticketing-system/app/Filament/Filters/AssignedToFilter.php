<?php

namespace App\Filament\Filters;

use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class AssignedToFilter extends SelectFilter
{
    public function filter($query, $value)
    {
        // Check if the authenticated user is a support user.
        if (Auth::user()->role === 'Support') {
            // Filter tickets to show only those assigned to the currently authenticated support user.
            $query->where('user_id', Auth::id());
        }
    }

    public function isResettable(): bool
    {
        // Determine if the filter is resettable based on the user's role.
        return Auth::user()->role !== 'Support';
    }
}
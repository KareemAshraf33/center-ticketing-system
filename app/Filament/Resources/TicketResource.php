<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Ticket;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Filters\AssignedToFilter;
use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    public static function form(Form $form): Form
{
    $formSchema = [
        TextInput::make('owner_name')->maxLength(255)->label(__('Ticket-Form-Name')),
        Select::make('category_id')
            ->relationship('category', 'name')->required()->label(__('Ticket-Form-Category')),
        Select::make('status')
            ->options([
                'New' => 'New',
                'Pending' => 'Pending',
                'Solved' => 'Solved',
                'Closed' => 'Closed',
            ])->required()->label(__('Ticket-Form-Status')),
        TextInput::make('problem')->required()->maxLength(500)->label(__('Ticket-Form-Problem')),
        TextInput::make('last_comment')->maxLength(500)->label(__('Ticket-Form-Ticket-Note')),
        FileUpload::make('image')->label(__('Ticket-Form-Image')),
    ];

    // Check the user's role and conditionally add the 'Assigned To' field
    if (auth()->check() && auth()->user()->role !== 'Support') {
        $assignedToField = Select::make('user_id')
            ->options(function () {
                return User::where('role', 'Support')->pluck('name', 'id');
            })
            ->required()
            ->label(__('Ticket-Form-Assigned-To'));

        array_splice($formSchema, 1, 0, [$assignedToField]);
    }
    return $form->schema($formSchema);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->searchable()->label(__('Ticket-Table-Id')),
                TextColumn::make('student_id')->sortable()->searchable()->label(__('Ticket-Table-Student-Id')),
                TextColumn::make('owner_name')->searchable()->label(__('Ticket-Table-Name')),
                TextColumn::make('owner_phone')->searchable()->label(__('Ticket-Table-Phone')),
                TextColumn::make('user.name')->label(__('Ticket-Table-Assigned-To')),
                TextColumn::make('category.name')->label(__('Ticket-Table-Category')),
                TextColumn::make('status')->label(__('Ticket-Table-Status')),
                TextColumn::make('problem')->label(__('Ticket-Table-Problem')),
                ImageColumn::make('image')->label(__('Ticket-Table-Image')),
                TextColumn::make('last_comment')->label(__('Ticket-Table-Ticket-Note')),
                TextColumn::make('created_at')->label(__('Ticket-Table-Created-At')),
            ])
            ->filters([
                SelectFilter::make('status')
                ->options([
                    'New' => 'New',
                    'Pending' => 'Pending',
                    'Solved' => 'Solved',
                    'Closed' => 'Closed',
                ]),
                SelectFilter::make('user_id')
                ->options(function () {
                    return User::where('role', 'Support')->pluck('name', 'id');
                })
                ->label('Assigned To')
                ->hidden(function () {
                    // Hide the filter for support users
                    return Auth::user()->role === 'Support';
                }),
                SelectFilter::make('category_id')->relationship('category', 'name')->label('Category'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    } 

    public static function getEloquentQuery(): Builder
    {
        $query = Ticket::orderBy('created_at', 'desc');

        // Check if the authenticated user is a support user
        if (auth()->check() && auth()->user()->role === 'Support') {
            // Filter the query to show only assigned tickets to support users
            $query->where('user_id', auth()->user()->id);
        }

        return $query;
    }

    public static function getNavigationLabel(): string
    {
        return __('Tickets');
    }
}

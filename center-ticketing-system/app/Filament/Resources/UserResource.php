<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Pages\Page;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    TextInput::make('name')->required()->maxLength(255)->label(__('User-Form-Name')),
                    TextInput::make('email')->maxLength(255)->label(__('User-Form-Email')),
                    TextInput::make('password')
                        ->password()
                        ->required(fn(Page $livewire): bool => $livewire instanceof CreateRecord)
                        ->minLength(8)
                        ->dehydrated(fn ($state) => filled($state))
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state))->label(__('User-Form-Password')),
                    Select::make('role')
                        ->options([
                            'Admin' => 'Admin',
                            'Support' => 'Support',
                        ])->required()->label(__('User-Form-Role')),
                    TextInput::make('title')->label(__('User-Form-Title')),        
                    Toggle::make('active')->required()->label(__('User-Form-Active')),
               ]) 
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->searchable()->label(__('User-Table-Id')),
                TextColumn::make('name')->searchable()->label(__('User-Table-Name')),
                TextColumn::make('email')->label(__('User-Table-Email')),
                TextColumn::make('role')->label(__('User-Table-Role')),
                TextColumn::make('title')->label(__('User-Table-Title')),
                IconColumn::make('active')->boolean()->label(__('User-Table-Active')),
                TextColumn::make('created_at')->label(__('User-Table-Created-At')),
                TextColumn::make('updated_at')->label(__('User-Table-Updated-At')),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->options([
                        'Admin' => 'Admin',
                        'Support' => 'Support',
                    ])
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('Users');
    }
}

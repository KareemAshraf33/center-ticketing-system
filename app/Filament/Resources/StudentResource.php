<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Student;
use Filament\Pages\Page;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    TextInput::make('name')->required()->maxLength(255)->label(__('Student-Form-Name')),
                    TextInput::make('email')->maxLength(255)->label(__('Student-Form-Email')),
                    TextInput::make('phone')->maxLength(255)->label(__('Student-Form-Phone')),
                    TextInput::make('password')
                        ->password()
                        ->required(fn(Page $livewire): bool => $livewire instanceof CreateRecord)
                        ->minLength(8)
                        ->dehydrated(fn ($state) => filled($state))
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state))->label(__('Student-Form-Password')),       
                    Toggle::make('active')->required()->label(__('Student-Form-Active')),
               ]) 
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->searchable()->label(__('Student-Table-Id')),
                TextColumn::make('name')->searchable()->label(__('Student-Table-Name')),
                TextColumn::make('email')->label(__('Student-Table-Email')),
                TextColumn::make('phone')->label(__('Student-Table-Phone')),
                IconColumn::make('active')->boolean()->label(__('Student-Table-Active')),
                TextColumn::make('created_at')->label(__('Student-Table-Created-At')),
                TextColumn::make('updated_at')->label(__('Student-Table-Updated-At')),
            ])
            ->filters([
                SelectFilter::make('active')
                ->options([
                    '1' => 'Active',
                    '0' => 'Not Active',
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }   

    public static function getNavigationLabel(): string
    {
        return __('Students');
    }
}

<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('firstname')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('lastname')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('birth_date')
                    ->required()
                    ->maxDate(now()->subYears(18))
                    ->minDate(now()->subYears(65)),
                Forms\Components\TextInput::make('email')
                    ->required()
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('salary')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(99999)
                    ->prefix('₴'),
                Forms\Components\SpatieMediaLibraryFileUpload::make('avatar')
                    ->collection('avatars')
                    ->image()
                    ->enableOpen()
                    ->enableDownload()
                    ->enableReordering()
                    ->preserveFilenames()
                    ->maxSize(1024)
                    ->columnSpanFull(),
                Forms\Components\Select::make('role')
                    ->options(Role::all()->pluck('name', 'name'))
                    ->required()
                    ->native(false)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('firstname'),
                Tables\Columns\TextColumn::make('lastname'),
                Tables\Columns\TextColumn::make('birth_date')
                    ->date(),
                Tables\Columns\TextColumn::make('salary')
                    ->money('UAH')
                    ->suffix('₴')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('avatar')
                    ->collection('avatars'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Role')
                    ->sortable()
                    ->badge()
                    ->colors([
                        'primary' => 'admin',
                        'info' => 'manager',
                        'success' => 'driver',
                    ]),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
}

<?php

namespace App\Filament\Resources;

use App\Models\Bus;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Brand;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\BusResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BusResource\RelationManagers;

class BusResource extends Resource
{
    protected static ?string $model = Bus::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('license_plate')
                    ->required()
                    ->unique(table: Bus::class, column: 'license_plate', ignorable: fn ($record) => $record)
                    ->maxLength(7),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'firstname', fn ($query) => $query->select(['id', 'firstname', 'lastname']))
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->firstname} {$record->lastname}")
                    ->searchable()
                    ->preload()
                    ->rule(function () {
                        return function ($attribute, $value, $fail) {
                            // Check if the user is already assigned to another bus
                            $existingBus = Bus::where('user_id', $value)->first();
                            if ($existingBus) {
                                $fail('This user is already assigned to another bus.');
                            }
                        };
                    })
                    ->createOptionForm([
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
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(table: User::class, column: 'email', ignorable: fn ($record) => $record),
                    ]),
                Forms\Components\Select::make('brand_id')
                    ->relationship('brand', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->unique(table: Brand::class, column: 'name')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('license_plate')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.firstname')
                    ->label('First Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.lastname')
                    ->label('Last Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('brand.name')
                    ->searchable()
                    ->sortable(),
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
            'index' => Pages\ListBuses::route('/'),
            'create' => Pages\CreateBus::route('/create'),
            'edit' => Pages\EditBus::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        if (auth()->user()->hasRole('driver')) {
            return parent::getEloquentQuery()
                ->where('user_id', auth()->id());
        }

        return parent::getEloquentQuery();
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole(['admin', 'manager', 'driver']);
    }

    public static function canView(Model $record): bool
    {
        return auth()->user()->hasRole(['admin', 'manager', 'driver']);
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public static function canRestore(Model $record): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public static function canForceDelete(Model $record): bool
    {
        return auth()->user()->hasRole('admin');
    }
}

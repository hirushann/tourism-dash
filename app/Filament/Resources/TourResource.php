<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TourResource\Pages;
use App\Filament\Resources\TourResource\RelationManagers;
use App\Models\Tour;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TourResource extends Resource
{
    protected static ?string $model = Tour::class;

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->hasRole('driver')) {
            $query->where('user_id', auth()->id());
        }

        return $query;
    }

    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationGroup = 'Operations';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Tour Basic Details')
                    ->schema([
                        Forms\Components\TextInput::make('tour_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('vehicle_id')
                            ->relationship('vehicle', 'plate_number')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->default(fn () => auth()->id())
                            ->label('Driver')
                            ->required()
                            ->hidden(fn () => !auth()->user()->hasRole(['super admin', 'manager'])),
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Mileage & Fuel')
                    ->schema([
                        Forms\Components\TextInput::make('start_mileage')
                            ->required()
                            ->numeric()
                            ->label('Starting Mileage (KM)'),
                        Forms\Components\TextInput::make('end_mileage')
                            ->numeric()
                            ->label('Ending Mileage (KM)')
                            ->hint('Leave empty if tour is ongoing'),
                        Forms\Components\TextInput::make('fuel_amount')
                            ->numeric()
                            ->prefix('$')
                            ->label('Fuel Cost'),
                        Forms\Components\TextInput::make('refueled_place')
                            ->maxLength(255)
                            ->label('Refueled At'),
                        Forms\Components\FileUpload::make('fuel_bill_path')
                            ->image()
                            ->directory('fuel_bills')
                            ->label('Fuel Receipt')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable()
                    ->label('Date'),
                Tables\Columns\TextColumn::make('tour_name')
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('vehicle.plate_number')
                    ->label('Vehicle')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Driver')
                    ->numeric()
                    ->sortable()
                    ->hidden(fn () => auth()->user()->hasRole('driver')),
                Tables\Columns\TextColumn::make('start_mileage')
                    ->label('Start')
                    ->sortable()
                    ->suffix(' KM'),
                Tables\Columns\TextColumn::make('end_mileage')
                    ->label('End')
                    ->sortable()
                    ->placeholder('Ongoing')
                    ->color(fn ($state) => $state ? 'gray' : 'warning')
                    ->suffix(fn ($state) => $state ? ' KM' : ''),
                Tables\Columns\TextColumn::make('fuel_amount')
                    ->label('Fuel')
                    ->money('USD')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('driver')
                    ->relationship('user', 'name', fn ($query) => $query->role('driver'))
                    ->hidden(fn () => auth()->user()->hasRole('driver')),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when($data['from'], fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date))
                        ->when($data['until'], fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date))),
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
            'index' => Pages\ListTours::route('/'),
            'create' => Pages\CreateTour::route('/create'),
            'edit' => Pages\EditTour::route('/{record}/edit'),
        ];
    }
}

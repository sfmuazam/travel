<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\FinancialResource\Pages;
use App\Filament\Admin\Resources\FinancialResource\RelationManagers;
use App\Models\Financial;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FinancialResource extends Resource
{
    protected static ?string $model = Financial::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('amount')
                    ->required()
                    ->label('Amount')
                    ->dehydrateStateUsing(fn ($state) => preg_replace('/[^\d]/', '', $state))
                    ->mask(RawJs::make('$money($input)'))
                    ->prefix('Rp'),
                Select::make('type')
                    ->label('Type')
                    ->options([
                        'in' => 'In',
                        'out' => 'Out',
                    ]),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'canceled' => 'Canceled',
                        'dp_completed' => 'DP Completed',
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_transaction')->searchable()
                    ->label('ID Transaction'),
                TextColumn::make('type')->searchable(),
                TextColumn::make('amount')
                    ->formatStateUsing(fn ($state) => 'Rp' . number_format($state, 0, ',', '.')),

                TextColumn::make('status')->searchable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'completed' => 'Completed',
                    'canceled' => 'Canceled',
                    'dp_completed' => 'DP Completed',
                ]),
                SelectFilter::make('type')->options([
                    'in' => 'In',
                    'out' => 'Out',
                ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TransactionRelationManager::class,
            RelationManagers\InvoiceRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFinancials::route('/'),
            'create' => Pages\CreateFinancial::route('/create'),
            'edit' => Pages\EditFinancial::route('/{record}/edit'),
        ];
    }
}

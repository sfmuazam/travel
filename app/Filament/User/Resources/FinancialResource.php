<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\FinancialResource\Pages;
use App\Filament\User\Resources\FinancialResource\RelationManagers;
use App\Models\Financial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FinancialResource extends Resource
{
    protected static ?string $model = Financial::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 3;
    
    public static function canAccess(): bool
    {
        return auth()->user()->isAgentVerified();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_transaction')->sortable(),
                TextColumn::make('type')->searchable()->sortable(),
                TextColumn::make('amount')->sortable(),
                TextColumn::make('status')->searchable(),
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
            ])
            ->modifyQueryUsing(function (Builder $query) {
                return $query->whereHas('transaction', function ($subquery) {
                    $subquery->where('by_id', auth()->user()->id);
                });
            });
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\InvoiceRelationManager::class,
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

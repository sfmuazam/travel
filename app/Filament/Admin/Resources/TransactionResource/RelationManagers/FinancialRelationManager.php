<?php

namespace App\Filament\Admin\Resources\TransactionResource\RelationManagers;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FinancialRelationManager extends RelationManager
{
    protected static string $relationship = 'financial';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('type')
                    ->readOnly()
                    ->label('Type')
                    ,
                Select::make('status')
                    ->columnStart(1)
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'canceled' => 'Canceled',
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('status')
            ->columns([
                Tables\Columns\TextColumn::make('status')
                ->formatStateUsing(function ($state) {
                    return match ($state) {
                        'canceled' => 'Cancel',
                        'pending' => 'Unpaid',
                        'completed' => 'Paid',
                        default => $state,
                    };
                }),
                Tables\Columns\TextColumn::make('type'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

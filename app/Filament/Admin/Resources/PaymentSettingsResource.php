<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PaymentSettingsResource\Pages;
use App\Filament\Admin\Resources\PaymentSettingsResource\RelationManagers;
use App\Models\PaymentSettings;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentSettingsResource extends Resource
{
    protected static ?string $model = PaymentSettings::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('dp_percentage')
                    ->label('DP Percentage')
                    ->numeric(),
                TextInput::make('dp_due_period')
                    ->integer()
                    ->label('Due Period in Days'),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('dp_percentage'),
                TextColumn::make('dp_due_period'),
            ])
            ->filters([
                //
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentSettings::route('/'),
            // 'create' => Pages\CreatePaymentSettings::route('/create'),
            'edit' => Pages\EditPaymentSettings::route('/{record}/edit'),
        ];
    }
}

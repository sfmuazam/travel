<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AddOnProductResource\Pages;
use App\Filament\Admin\Resources\AddOnProductResource\RelationManagers;
use App\Models\AddOnProduct;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AddOnProductResource extends Resource
{
    protected static ?string $model = AddOnProduct::class;
    protected static ?string $label = 'Addons Products';

    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';
    protected static ?string $navigationGroup = "Package";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->columnSpan(1)
                    ,
                TextInput::make('original_price')
                    ->required()
                    ->columnStart(1)
                    ->prefix('Rp')
                    ->mask(RawJs::make('$money($input)'))
                    ->dehydrateStateUsing(fn ($state) => preg_replace('/[^\d]/', '', $state))
                    ->label('Price')
                    ->default(0)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, Get $get) {
                        $set('discount_price', $get('original_price'));
                    }),
                FileUpload::make('thumbnail')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('discount_percent')
                    ->nullable()
                    ->suffix('%')
                    ->integer()
                    ->default(0)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($set, Get $get) {
                        $pricex = preg_replace('/[^\d]/', '', $get('original_price'));
                        $discount_percent = preg_replace('/[^\d]/', '', $get('discount_percent'));

                        $discountPrice = $discount_percent <= 0 ? $pricex : $pricex - ($pricex * ($discount_percent / 100));
                        $discountPrice = intval($discountPrice);
                        $formatted = number_format($discountPrice, 0, '', ',');

                        $set('discount_price', $formatted);
                    }),
                TextInput::make('discount_price')
                    ->readOnly()
                    ->prefix('Rp')
                    ->dehydrateStateUsing(fn ($state) => preg_replace('/[^\d]/', '', $state))
                    ->live(),
                Textarea::make('description')
                    ->columnSpanFull()
                    ->rows(3)
                    ->required(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('original_price')
                ->formatStateUsing(fn ($state) => 'Rp' . number_format($state, 0, ',', '.')),
            ])
            ->filters([
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListAddOnProducts::route('/'),
            'create' => Pages\CreateAddOnProduct::route('/create'),
            'edit' => Pages\EditAddOnProduct::route('/{record}/edit'),
        ];
    }
}

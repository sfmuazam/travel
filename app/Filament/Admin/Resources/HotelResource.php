<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\HotelResource\Pages;
use App\Filament\Admin\Resources\HotelResource\RelationManagers;
use App\Models\Hotel;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use IbrahimBougaoua\FilamentRatingStar\Actions\RatingStar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HotelResource extends Resource
{
    protected static ?string $model = Hotel::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = "Stock";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')->required(),
                        FileUpload::make('logo')->required()->columnSpanFull(),
                        FileUpload::make('thumbnail')->required()->columnSpanFull(),
                        TextInput::make('city')->required(),
                        TextInput::make('country')->required(),
                        Textarea::make('address')->rows(3)->required(),
                        TextInput::make('phone_number')->tel(),
                        TextInput::make('email')->email(),
                        TextInput::make('website')->url(),
                        RatingStar::make('rating')
                            ->label('Rating')->required(),
                        // Hidden::make('stock')->required()->columnStart(1)->default(0),
                    ])
                    ->columns(3)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('city')->searchable(),
                TextColumn::make('country')->searchable(),
                TextColumn::make('countroom')->sortable()
                    ->label('Qty Rooms'),

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
            RelationManagers\RoomsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHotels::route('/'),
            'create' => Pages\CreateHotel::route('/create'),
            'edit' => Pages\EditHotel::route('/{record}/edit'),
        ];
    }
}

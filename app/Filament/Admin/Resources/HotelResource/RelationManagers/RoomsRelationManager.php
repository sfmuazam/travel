<?php

namespace App\Filament\Admin\Resources\HotelResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoomsRelationManager extends RelationManager
{
    protected static string $relationship = 'rooms';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('room_type_title')->required()
                    ->label('Room Type'),
                TextInput::make('room_number')->required()
                    ->label('Room Number'),
                TextInput::make('room_qty')->required()->integer()
                    ->label('Room Quantity'),
                DatePicker::make('check_in_date')->required()
                    ->label('Check In Date'),
                DatePicker::make('check_out_date')->required()
                    ->label('Check Out Date'),
            ])->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('room_type_title')->label('Room Type'),
                TextColumn::make('room_number')->label('Room Number'),
                TextColumn::make('room_qty')->label('Room Quantity'),
                TextColumn::make('check_in_date')->label('Check In Date')->date(),
                TextColumn::make('check_out_date')->label('Check Out Date')->date(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}

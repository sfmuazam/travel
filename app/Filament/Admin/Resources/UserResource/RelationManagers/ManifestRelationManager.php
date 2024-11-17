<?php

namespace App\Filament\Admin\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManifestRelationManager extends RelationManager
{
    protected static string $relationship = 'manifest';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Details')
                    ->collapsible()
                    ->schema([
                        Hidden::make('by_id')
                            ->default(auth()->user()->id),
                        TextInput::make('name')
                            ->label('First Name')
                            ->required(),
                        TextInput::make('middle_name')
                            ->label('Middle Name'),
                        TextInput::make('last_name')
                            ->label('Last Name')
                            ->required(),
                        Textarea::make('address')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                        DatePicker::make('birthdate')
                            ->required(),
                        TextInput::make('place_of_birth')
                            ->required(),
                        Select::make('gender')
                            ->required()
                            ->options([
                                'male' => 'Male',
                                'female' => 'Female',
                            ]),
                        TextInput::make('father_name')
                            ->required()
                            ->label('Father Name'),
                        TextInput::make('mother_name')
                            ->required()
                            ->label('Mother Name'),
                    ])
                    ->columns(3),
                Fieldset::make('')
                    ->relationship('filesuser')
                    ->schema([
                        Section::make('Files Requirement')
                            ->collapsible()
                            ->schema([
                                TextInput::make('family_card')
                                    ->required()
                                    ->numeric(),
                                TextInput::make('ktp')
                                    ->numeric()
                                    ->required()
                                    ->columns(1),
                                TextInput::make('passport')
                                    ->required(),
                                TextInput::make('bpjs')
                                    ->numeric()
                                    ->columns(1),
                                DatePicker::make('passport_expiry_date')
                                    ->required()
                                    ->live()
                                    ->helperText(function ($state) {
                                        if (!empty($state)) {
                                            $expiryDate = \Carbon\Carbon::parse($state);
                                            $today = \Carbon\Carbon::today();
                                            $expiryThreshold = $today->addMonths(10);

                                            if ($expiryDate->lt($expiryThreshold)) {
                                                return 'PASPOR AKAN KADALUARSA Minimal Masa berlaku passport 10 Bulan.';
                                            }
                                        }
                                        return null;
                                    })
                            ])
                            ->columns(3),
                        Section::make('File Uploads')
                            ->collapsible()
                            ->schema([
                                FileUpload::make('file_bpjs'),
                                FileUpload::make('file_ktp')->required(),
                                FileUpload::make('file_passport')->required(),
                                FileUpload::make('file_marriage_book'),
                                FileUpload::make('file_pas_photo_4x6')->required(),
                                FileUpload::make('file_covid_certificate'),
                                FileUpload::make('file_family_card')->required(),
                                FileUpload::make('file_recommendation_letter'),
                            ])
                            ->columns(2)
                    ])
                    ->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('address'),
                TextColumn::make('gender'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

<?php

namespace App\Filament\Admin\Resources\FinancialResource\RelationManagers;

use App\Models\AddOnProduct;
use App\Models\Manifest;
use App\Models\PackageType;
use App\Models\TravelPackage;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionRelationManager extends RelationManager
{
    protected static string $relationship = 'transaction';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Repeater::make('Customer')
                    ->relationship('customer')
                    ->addable(false)
                    ->reorderableWithDragAndDrop(false)
                    ->deletable(false)
                    ->schema([
                        Select::make('manifest_id')
                            ->relationship('manifest', 'name')
                            ->label('Customer')
                            ->live()
                            ->searchable('name')
                            ->options(Manifest::all()->pluck('name', 'id'))
                            ->createOptionForm([
                                Section::make('Details')
                                    ->collapsible()
                                    ->schema([
                                        Hidden::make('by_id')
                                            ->required()
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
                            ])
                            ->editOptionForm([
                                Section::make('Details')
                                    ->collapsible()
                                    ->schema([
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
                            ])
                            ->required()
                    ])
                    ->columnSpanFull(),
                Section::make('Purchase Details')
                    ->collapsible()
                    ->schema([
                        Placeholder::make('package_name')
                            ->label('Package Name')
                            ->content(fn (Get $get) => TravelPackage::find($get('travel_package_id'))->name ?? ''),
                        Placeholder::make('package_type')
                            ->label('Package Type')->content(fn (Get $get) => PackageType::find($get('travel_package_type_id'))->name ?? ''),
                        Placeholder::make('quantity')
                            ->label('Quantity')->content(fn (Get $get) => $get('qty') ?? 0),
                        Placeholder::make('total_package_price')
                            ->label('Total')->content(
                                function (Get $get) {
                                    $total_pricex = preg_replace('/[^\d]/', '',  $get('total_price'));

                                    return 'Rp ' . number_format($total_pricex, 0, ',', '.');
                                }
                            ), Repeater::make('addons')
                            ->reorderableWithDragAndDrop(false)
                            ->live()
                            ->addable(false)
                            ->deletable(false)
                            ->schema([
                                Placeholder::make('addonsx')
                                    ->dehydrated(false)
                                    ->label('')
                                    ->content('No Addons')
                                    ->hidden(function (Get $get) {
                                        return $get('addons_id') ? true : false;
                                    }),
                                Placeholder::make('addons_name')
                                    ->label('Name')
                                    ->columns(1)
                                    ->hidden(function (Get $get) {
                                        return $get('addons_id') ? false : true;
                                    })
                                    ->label('Addons Name')->content(fn (Get $get) => AddOnProduct::find($get('addons_id'))->title ?? ''),
                                Placeholder::make(('addons_pricex'))
                                    ->label('Price')
                                    ->hidden(function (Get $get) {
                                        return $get('addons_id') ? false : true;
                                    })
                                    ->content(fn (Get $get) => 'Rp ' . number_format(AddOnProduct::find($get('addons_id'))->discount_price ?? 0, 0, ',', '.')),
                            ])
                            ->columns(2)
                    ]),
                Section::make('Total Price')
                    ->schema([
                        TextInput::make('dp_amount')
                            ->hidden(function (Get $get) {
                                if ($get('payment_status') == 'partial') {
                                    return false;
                                }
                                return true;
                            })
                            ->live()
                            ->mask(RawJs::make('$money($input)'))

                            ->prefix('Rp')
                            ->readOnly()
                            ->label('DP Amount'),
                        DatePicker::make('dp_due_date')
                            ->hidden(function (Get $get) {
                                if ($get('payment_status') == 'partial') {
                                    return false;
                                } else if ($get('dp_due_date') != null) {
                                    return false;
                                }
                                return true;
                            }),

                        TextInput::make('total_price')
                            ->label('Total Price')
                            ->prefix('Rp')
                            ->live()
                            ->minValue(0)
                            ->mask(RawJs::make('$money($input)'))

                            ->readOnly()
                            ->required(),
                        TextInput::make('remaining_amount')
                            ->label('Due Amount')
                            ->prefix('Rp')
                            ->mask(RawJs::make('$money($input)'))

                            ->hidden(function (Get $get) {
                                if ($get('payment_status') == 'partial') {
                                    return false;
                                } else if ($get('remaining_amount') != null) {
                                    return false;
                                }
                                return true;
                            })
                            ->live()
                            ->minValue(0)
                            ->readOnly()
                            ->required(),
                    ]),
                Textarea::make('notes')->columnSpanFull()->rows(5),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('travel_package')
            ->columns([
                TextColumn::make('travel_package.name'),
                TextColumn::make('qty'),
                TextColumn::make('total_price')
                    ->label('Total Price')
                    ->prefix('Rp')
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

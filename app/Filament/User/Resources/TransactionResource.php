<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\TransactionResource\Pages;
use App\Filament\User\Resources\TransactionResource\RelationManagers;
use App\Models\AddOnProduct;
use App\Models\Manifest;
use App\Models\PackageType;
use App\Models\PaymentSettings;
use App\Models\Transaction;
use App\Models\TravelPackage;
use App\Models\Website;
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
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    // public static function canAccess(): bool
    // {
    //     return auth()->user()->isAgentVerified();
    // }
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Package')
                        ->schema([
                            Select::make('travel_package_id')
                                ->required()
                                ->live()
                                ->label('Travel Package')
                                ->options(TravelPackage::all()->mapWithKeys(function ($travelPackage) {
                                    return [$travelPackage->id => $travelPackage->name . ' - ' . $travelPackage->departure_date];
                                }))
                                ->afterStateUpdated(function (Set $set) {
                                    $set('travel_package_type_id', null);
                                    $set('qty', null);
                                    $set('total_price', null);
                                    $set('total_price2', null);
                                    $set('payment_status', null);
                                }),
                            Select::make('travel_package_type_id')
                                ->label('Package Type')
                                ->disableOptionWhen(function (Get $get) {
                                    if ($get('travel_package_id') == null) {
                                        return true;
                                    }
                                    return false;
                                })
                                ->required()
                                ->live()
                                ->options(function (Get $get) {
                                    $travelPackage = PackageType::where('id_travel_package', $get('travel_package_id'))->get();
                                    return $travelPackage->pluck('name', 'id');
                                }),
                            TextInput::make('qty')
                                ->readOnly(function (Get $get) {
                                    if ($get('travel_package_type_id') == null) {
                                        return true;
                                    }
                                    return false;
                                })
                                ->required()
                                ->label('Quantity')
                                ->integer()
                                ->live()
                                ->minValue(0)
                                ->maxValue(function (Get $get) {
                                    $travelPackage = PackageType::where('id', $get('travel_package_type_id'))->first();
                                    if ($travelPackage) {
                                        return $travelPackage->stock;
                                    }
                                })
                                ->validationMessages([
                                    'maxValue' => 'Stock is not enough',
                                ])
                                ->afterStateUpdated(function ($set, Get $get) {
                                    $travelPackage = PackageType::where('id', $get('travel_package_type_id'))->first();
                                    if ($travelPackage) {
                                        $qty = intval($get('qty'));
                                        $set('total_price', $qty * $travelPackage->discount_price);
                                        $set('total_price2', $qty * $travelPackage->discount_price);
                                    }
                                    $set('payment_status', null);
                                    if ($get('qty') > 0) {
                                        $set('customer', array_map(function ($i) use ($get) {
                                            return [
                                                'manifest_id' => '',
                                            ];
                                        }, range(0, $get('qty') - 1)));
                                    }
                                }),
                            Section::make('Total Package Price')
                                ->schema([
                                    TextInput::make('total_price2')
                                        ->live()
                                        ->readOnly()
                                        ->mask(RawJs::make('$money($input)'))
                                        ->dehydrateStateUsing(fn($state) => preg_replace('/[^\d]/', '', $state))
                                        ->label('')
                                        ->prefix('Rp')
                                        ->minValue(0)
                                        ->dehydrated(false),
                                ])->hidden(function (Get $get) {
                                    return $get('total_price2') == null;
                                }),
                            Textarea::make('notes')->columnSpanFull()->rows(5),
                        ]),
                    Wizard\Step::make('Users Info')
                        ->schema([
                            Repeater::make('customer')
                                ->relationship('customer')
                                ->addable(false)
                                ->reorderableWithDragAndDrop(false)
                                ->deletable(false)
                                ->schema([
                                    Select::make('manifest_id')
                                        ->relationship('manifest', 'name', function (Builder $query) {
                                            return $query->where('by_id', auth()->user()->id);
                                        })
                                        ->label('Customer')
                                        ->live()
                                        ->searchable('name')
                                        ->options(Manifest::where('by_id', auth()->user()->id)
                                            ->pluck('name', 'id'))

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
                        ]),
                    Wizard\Step::make('Addons Products')
                        ->schema([
                            Repeater::make('transaction_addons')
                                ->live()
                                ->disabled(function (Get $get) {
                                    if (!$get('total_price')) {
                                        return true;
                                    }
                                })
                                ->helperText(function (Get $get) {
                                    if (!$get('total_price')) {
                                        return 'Please select package first';
                                    }
                                })
                                ->relationship('addons')
                                ->reorderableWithDragAndDrop(false)
                                ->schema([
                                    Select::make('addons_id')
                                        ->label('')
                                        ->live()
                                        ->required()
                                        ->options(AddOnProduct::all()->pluck('title', 'id'))
                                        ->default(function (Get $get, Set $set) {
                                            $addonsId = $get('addons_id');
                                            if ($addonsId != null) {
                                                $addons = AddOnProduct::find($addonsId);
                                                if ($addons) {
                                                    $set('addons_price', $addons->discount_price);
                                                    $set('addons_description', $addons->description);
                                                }
                                            }
                                            return $addonsId;
                                        })
                                        ->afterStateHydrated(function ($set, Get $get) {
                                            $addonsId = $get('addons_id');
                                            if ($addonsId != null) {
                                                $addons = AddOnProduct::find($addonsId);
                                                if ($addons) {
                                                    $set('addons_price', $addons->discount_price);
                                                    $set('addons_description', $addons->description);
                                                }
                                            }
                                            return $addonsId;
                                        })
                                        ->afterStateUpdated(function ($set, Get $get) {
                                            if ($get('addons_id') != null) {
                                                $addons = AddOnProduct::find($get('addons_id'));
                                                $set('addons_price', $addons->discount_price);
                                                $set('addons_description', $addons->description);
                                            }
                                        })
                                        ->live(),
                                    TextInput::make('addons_price')
                                        ->readOnly()
                                        ->live()
                                        ->hidden(function (Get $get) {
                                            return !$get('addons_id');
                                        })
                                        ->dehydrated(false),
                                    Textarea::make('addons_description')
                                        ->dehydrated(false)
                                        ->readOnly()
                                        ->hidden(function (Get $get) {
                                            return !$get('addons_id');
                                        })
                                        ->rows(3),
                                ])
                                ->afterStateUpdated(function ($set, Get $get) {
                                    $set('payment_status', null);
                                    $addons = $get('transaction_addons') ?? [];
                                    $totalAddonPrice = 0;
                                    if (!empty($addons)) {
                                        foreach ($addons as $addon) {
                                            $addonProduct = AddOnProduct::find($addon['addons_id']);
                                            if ($addonProduct) {
                                                $totalAddonPrice += $addonProduct->discount_price;
                                            }
                                        }
                                    }
                                    $total_price_package = preg_replace('/[^\d]/', '',  $get('total_price2'));
                                    $set('total_price', $total_price_package + $totalAddonPrice);
                                })
                        ]),
                    Wizard\Step::make('Payment')
                        ->schema([
                            Section::make('Payment')
                                ->schema([
                                    Select::make('payment_status')
                                        ->label('Payment Status')
                                        ->options([
                                            'full' => 'Full Payment',
                                            'partial' => 'DP',
                                        ])
                                        ->disabledOn('edit')
                                        ->required()
                                        ->live()
                                        ->afterStateUpdated(function ($set, Get $get) {
                                            if ($get('payment_status') == 'partial') {

                                                $total_pricex = preg_replace('/[^\d]/', '',  $get('total_price'));
                                                $settings = PaymentSettings::first();
                                                $dp_percentage = $settings->dp_percentage;
                                                $days = $settings->dp_due_period;
                                                $dpamount = $total_pricex * ($dp_percentage / 100);
                                                $set('dp_amount', $dpamount);
                                                $set('remaining_amount', $total_pricex - $dpamount);
                                                $set('dp_due_date', now()->addDays($days)->format('Y-m-d'));
                                            }
                                        }),
                                    Select::make('payment_method')
                                        ->label('Payment Method')
                                        ->disabledOn('edit')
                                        ->options([
                                            'midtrans' => "Midtrans"
                                        ])
                                        ->live()
                                        ->required(),
                                ]),
                            Section::make('Purchase Details')
                                ->live()
                                ->schema([
                                    Placeholder::make('package_name')
                                        ->label('Package Name')
                                        ->content(fn(Get $get) => TravelPackage::find($get('travel_package_id'))->name ?? ''),
                                    Placeholder::make('package_type')
                                        ->label('Package Type')->content(fn(Get $get) => PackageType::find($get('travel_package_type_id'))->name ?? ''),
                                    Placeholder::make('quantity')
                                        ->label('Quantity')->content(fn(Get $get) => $get('qty') ?? 0),
                                    Placeholder::make('total_package_price')
                                        ->label('Total')->content(
                                            function (Get $get) {
                                                if ($get('total_price')) {
                                                    $total_pricex = preg_replace('/[^\d]/', '',  $get('total_price'));
                                                    return 'Rp ' . number_format($total_pricex, 0, ',', '.');
                                                }
                                            }
                                        ),
                                    Repeater::make('transaction_addons')
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
                                                ->dehydrated(false)
                                                ->label('Name')
                                                ->columns(1)
                                                ->hidden(function (Get $get) {
                                                    return $get('addons_id') ? false : true;
                                                })
                                                ->label('Addons Name')->content(fn(Get $get) => AddOnProduct::find($get('addons_id'))->title ?? ''),
                                            Placeholder::make(('addons_pricex'))
                                                ->dehydrated(false)
                                                ->label('Price')
                                                ->hidden(function (Get $get) {
                                                    return $get('addons_id') ? false : true;
                                                })
                                                ->content(fn(Get $get) => 'Rp ' . number_format(AddOnProduct::find($get('addons_id'))->discount_price ?? 0, 0, ',', '.')),
                                        ])
                                        ->columns(2)
                                ]),
                            Section::make('Total Pay')
                                ->schema([
                                    TextInput::make('dp_amount')
                                        ->hidden(function (Get $get) {
                                            if ($get('payment_status') == 'partial') {
                                                return false;
                                            }
                                            return true;
                                        })
                                        // ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.'))
                                        // ->afterStateHydrated(fn ($state) => number_format($state, 0, ',', '.'))
                                        ->live()
                                        ->dehydrateStateUsing(fn($state) => preg_replace('/[^\d]/', '', $state))
                                        ->mask(RawJs::make('$money($input)'))
                                        ->prefix('Rp')
                                        ->readOnly()
                                        ->label('DP Amount'),
                                    DatePicker::make('dp_due_date')
                                        ->label('DP Due Date')
                                        ->hidden(function (Get $get) {
                                            if ($get('payment_status') == 'partial') {
                                                return false;
                                            }
                                            return true;
                                        }),
                                    TextInput::make('total_price')
                                        ->label('Total Price')
                                        ->prefix('Rp')
                                        ->live()
                                        ->mask(RawJs::make('$money($input)'))
                                        // ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.'))
                                        ->dehydrateStateUsing(fn($state) => preg_replace('/[^\d]/', '', $state))
                                        ->readOnly()
                                        ->required(),
                                    TextInput::make('remaining_amount')
                                        ->label('Due Amount')
                                        ->mask(RawJs::make('$money($input)'))
                                        ->prefix('Rp')
                                        ->hidden(function (Get $get) {
                                            if ($get('payment_status') == 'partial') {
                                                return false;
                                            }
                                            return true;
                                        })
                                        ->live()
                                        ->default(0)
                                        ->dehydrateStateUsing(fn($state) => preg_replace('/[^\d]/', '', $state))
                                        ->readOnly()
                                        ->required(),
                                ])
                        ])
                        ->columns(2)
                ])->skippable()->columnSpanFull()->submitAction(new HtmlString(Blade::render(<<<BLADE
    <x-filament::button
        type="submit"
        size="sm"
        wire:loading.attr="disabled"
    >
        Submit
        <x-filament::loading-indicator wire:loading.delay.default class="h-5 w-5" />
    </x-filament::button>
BLADE))),
            ])
            ->disabled(
                function ($record) {
                    return $record && $record->financial && $record->financial->status != 'pending';
                }
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable(),
                TextColumn::make('travel_package.name')
                    ->label('Travel Package')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('travel_package_type.name')
                    ->label('Package Type')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('qty')
                    ->sortable(),
                TextColumn::make('financial.status')
                    ->label('Status'),
            ])
            ->filters([
                SelectFilter::make('travel_package')
                    ->relationship('travel_package', 'name'),
                SelectFilter::make('travel_package_type')
                    ->relationship('travel_package_type', 'name')
                    ->searchable(),
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'dp_completed' => 'DP Completed',
                        'canceled' => 'Canceled',

                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['value']) {
                            $query->whereHas('financial', function ($query) use ($data) {
                                $query->where('status', $data['value']);
                            });
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->hidden(function ($record) {
                        return $record->financial->status != 'pending';
                    }),
                Tables\Actions\DeleteAction::make()
                    ->hidden(function ($record) {
                        return $record->financial->status == 'completed';
                    }),
                Tables\Actions\Action::make('pay')
                    ->label('Pay')
                    ->icon('heroicon-s-credit-card')
                    ->action(function ($record) {
                        $paymentUrl = $record->payment_url;
                        return redirect()->away($paymentUrl);
                    })
                    ->hidden(function ($record) {
                        return $record->financial->status != 'pending';
                    })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                return $query->where('by_id', auth()->user()->id);
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}

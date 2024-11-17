<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TravelPackageResource\Pages;
use App\Models\Airlines;
use App\Models\Catering;
use App\Models\Hotel;
use App\Models\PackageTerms;
use App\Models\Transportation;
use App\Models\TravelCategory;
use App\Models\TravelPackage;
use Filament\Actions\Action;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class TravelPackageResource extends Resource
{
    protected static ?string $model = TravelPackage::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = "Package";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Package Info')
                        ->schema([
                            TextInput::make('name')->label('Package Name')
                                ->required()
                                ->afterStateUpdated(function (Get $get, Set $set, ?string $state) { {
                                        $slug = Str::slug($state);
                                        // $set('slug', $slug);
                                        // $uniqueSlug = self::generateUniqueSlug($slug);
                                        $set('slug', $slug);

                                        $count = TravelPackage::where('slug', $slug)->count();

                                        if ($count > 0) {
                                            $suffix = 1;
                                            $newSlug = $slug . '-' . $suffix;

                                            while (TravelPackage::where('slug', $newSlug)->exists()) {
                                                $suffix++;
                                                $newSlug = $slug . '-' . $suffix;
                                            }

                                            $set('slug', $newSlug);
                                        }
                                    }
                                })
                                ->live(onBlur: true),
                            Hidden::make('slug')
                                ->required(),
                            Textarea::make('description')->required()->rows(5)->columnSpanFull(),
                            FileUpload::make('thumbnail')->required()->columnSpanFull(),
                            Select::make('category_id')->label('Category')
                                ->columnStart(1)
                                ->required()
                                ->options(TravelCategory::all()->pluck('name', 'id')),
                            DatePicker::make('departure_date')
                                ->required()
                                ->columnStart(1)
                                ->live(onBlur: true),
                            DatePicker::make('arrival_date')
                                ->required()
                        ])->columns(2),
                    Wizard\Step::make('Package Type')
                        ->schema([
                            Repeater::make('package_types')->label('')
                                ->relationship('packageType')
                                ->schema([
                                    TextInput::make('name')->label('Package Type Name')->required(),
                                    TextInput::make('stock')->label('Quota')->integer()->required(),
                                    TextInput::make('price')
                                        ->mask(RawJs::make('$money($input)'))
                                        ->required()
                                        // ->integer()
                                        ->live(onBlur: true)
                                        ->dehydrateStateUsing(fn ($state) => preg_replace('/[^\d]/', '', $state))
                                        ->afterStateUpdated(function ($set, Get $get) {
                                            if ($get('discount_percent') > 0) {
                                                // $set('discount_percent', 0);
                                            } else {
                                                $set('discount_price', $get('price'));
                                            }
                                        })
                                        ->label('Price')
                                        ->default(0)
                                        ->prefix('Rp')
                                        ->minValue(0),
                                    TextInput::make('discount_price')
                                        ->mask(RawJs::make('$money($input)'))
                                        ->dehydrateStateUsing(fn ($state) => preg_replace('/[^\d]/', '', $state))
                                        ->prefix('Rp')
                                        ->readOnly()
                                        ->live(),
                                    TextInput::make('discount_percent')
                                        ->required()
                                        ->label('Discount Percentage')
                                        ->integer()
                                        ->suffix('%')
                                        ->default(0)
                                        ->minValue(0)
                                        ->maxValue(100)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(function ($set, Get $get) {
                                            $pricex = preg_replace('/[^\d]/', '', $get('price'));
                                            $discount_percent = preg_replace('/[^\d]/', '', $get('discount_percent'));
                                            $discountPrice = $discount_percent <= 0 ? $pricex : $pricex - ($pricex * ($discount_percent / 100));
                                            $discountPrice = intval($discountPrice);

                                            $formatted = number_format($discountPrice, 0, '', ',');

                                            $set('discount_price', $formatted);
                                        }),
                                ])
                                ->columns(2)
                        ]),
                    Wizard\Step::make('Package Term')
                        ->schema([
                            Repeater::make('package_terms')
                                ->deletable(false)
                                ->reorderableWithDragAndDrop(false)
                                ->addable(false)
                                ->schema([
                                    CheckboxList::make('includes')
                                        ->options(PackageTerms::where('type', 'include')->pluck('title', 'id')->toArray()),
                                    CheckboxList::make('exludes')
                                        ->options(PackageTerms::where('type', 'exclude')->pluck('title', 'id')->toArray()),
                                    CheckboxList::make('terms')
                                        ->options(PackageTerms::where('type', 'term')->pluck('title', 'id')->toArray()),
                                ])
                        ]),
                    Wizard\Step::make('Itinerary')
                        ->schema([
                            Repeater::make('itinerary')
                                ->label('')
                                ->schema([
                                    TextInput::make('title')
                                        ->required(),
                                    TextInput::make('location')
                                        ->columnStart(1)->required(),
                                    Textarea::make('description')
                                        ->required()
                                        ->rows(5)
                                        ->columnSpanFull(),
                                    DateTimePicker::make('datetime')->required(),
                                ])->columns(2)
                        ]),
                    Wizard\Step::make('Facility')
                        ->schema([
                            Section::make('')
                                ->schema([
                                    Select::make('id_airline')
                                        ->label('Airlines')
                                        ->options(Airlines::all()->pluck('name', 'id'))
                                        ->searchable(),
                                ]),
                            Section::make('')
                                ->schema([
                                    Select::make('id_catering')
                                        ->label('Catering')
                                        ->options(Catering::all()->pluck('name', 'id'))
                                        ->searchable(),
                                ]),
                            Section::make('')
                                ->schema([
                                    Select::make('id_transportation')
                                        ->label('Transportation')
                                        ->options(Transportation::all()->pluck('name', 'id'))
                                        ->searchable(),
                                ]),
                            Section::make('')
                                ->schema([
                                    CheckboxList::make('id_hotel')
                                        ->label('Hotel')
                                        ->options(Hotel::all()->mapWithKeys(function ($item) {
                                            return [$item->id => $item->name . ' - ' . $item->city . ', ' . $item->country];
                                        })->toArray())

                                ]),


                        ])

                ])->skippable()->columnSpanFull()->submitAction(new HtmlString(Blade::render(<<<BLADE
                <x-filament::button
                    type="submit"
                    size="sm"
                    wire:loading.attr="disabled"
                >
                    Submit
                    <x-filament::loading-indicator wire:loading.delay.default class="h-5 w-5" />
                </x-filament::button>
            BLADE)))
            ]);
    }

    public function generateUniqueSlug($slug)
    {
        $count = TravelPackage::where('slug', $slug)->count();

        if ($count > 0) {
            $suffix = 1;
            $newSlug = $slug . '-' . $suffix;

            while (TravelPackage::where('slug', $newSlug)->exists()) {
                $suffix++;
                $newSlug = $slug . '-' . $suffix;
            }

            return $newSlug;
        }

        return $slug;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                ImageColumn::make('thumbnail'),
                TextColumn::make('category.name'),
                TextColumn::make('departure_date')
                    ->dateTime('Y-m-d')
                    ->sortable(),
                TextColumn::make('arrival_date')
                    ->dateTime('Y-m-d')
                    ->sortable(),

            ])
            ->filters([
                SelectFilter::make('category')
                    ->relationship('category', 'name'),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTravelPackages::route('/'),
            'create' => Pages\CreateTravelPackage::route('/create'),
            'edit' => Pages\EditTravelPackage::route('/{record}/edit'),
        ];
    }
}

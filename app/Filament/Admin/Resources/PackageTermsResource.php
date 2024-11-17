<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PackageTermsResource\Pages;
use App\Filament\Admin\Resources\PackageTermsResource\RelationManagers;
use App\Models\PackageTerms;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PackageTermsResource extends Resource
{
    protected static ?string $model = PackageTerms::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = "Package";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->columnStart(1)
                    ->required(),
                RichEditor::make('description')
                    ->columnStart(1)
                    ->required()
                    ->columnSpanFull(),
                Select::make('type')
                    ->columnStart(1)
                    ->options([
                        'include' => 'Include',
                        'exclude' => 'Exclude',
                        'term' => 'Term',
                    ])->required()
                    ->live(),
                FileUpload::make('icon')
                    ->live()
                    ->hidden(fn (Get $get) => $get('type') == 'term')
                    ->columnStart(1)
                    ->required(fn (Get $get) => !$get('type') == 'term')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('type')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')->options([
                    'include' => 'Include',
                    'exclude' => 'Exclude',
                    'term' => 'Term',
                ])
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
            'index' => Pages\ListPackageTerms::route('/'),
            'create' => Pages\CreatePackageTerms::route('/create'),
            'edit' => Pages\EditPackageTerms::route('/{record}/edit'),
        ];
    }
}

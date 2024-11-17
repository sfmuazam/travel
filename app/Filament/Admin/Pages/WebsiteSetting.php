<?php

namespace App\Filament\Admin\Pages;

use App\Models\Website;
use Filament\Actions\Action;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class WebsiteSetting extends Page implements HasForms
{
    use InteractsWithForms;
    public ?array $data = [];

    protected static string $view = 'filament.admin.pages.website-setting';
    protected static ?string $navigationLabel = 'Website Settings';
    protected static ?string $slug = 'website-setting';
    protected static ?string $navigationGroup = "Website";

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected ?string $heading = 'Website Settings';
    protected static ?int $navigationSort = 5;

    public function mount(): void
    {
        $this->form->fill(Website::first()->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('Excellence')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Toggle::make('fr_company_advantages_status')
                                            ->label('Status'),
                                        TextInput::make('fr_company_advantages_title')
                                            ->label('Title'),
                                        TextInput::make('fr_company_advantages_subtitle')
                                            ->label('Sub Title'),
                                    ]),
                                Repeater::make('fr_company_advantages')
                                    ->label('Company Advantages')
                                    ->schema([
                                        FileUpload::make('icon')
                                            ->label('Icon')
                                            ->image()
                                            ->directory('company-advantages'),
                                        TextInput::make('title')
                                            ->label('Title'),
                                        Textarea::make('description')
                                            ->rows(3)
                                            ->label('Description'),
                                    ])
                            ]),
                        Tabs\Tab::make('Footer')
                            ->schema([
                                Textarea::make('fr_about')
                                    ->rows(3)
                                    ->label('About Company'),
                                TextInput::make('fr_copyright')
                                    ->label('Copyright'),
                                Repeater::make('fr_social_media')
                                    ->label('Social Media')
                                    ->reorderable(false)
                                    ->schema([
                                        Select::make('social_media')
                                            ->label('Social Media')
                                            ->options([
                                                'Facebook' => 'Facebook',
                                                'Twitter' => 'Twitter',
                                                'Instagram' => 'Instagram',
                                                'Youtube' => 'Youtube',
                                            ]),
                                        TextInput::make('url')->label('URL')
                                            ->url(),
                                    ])
                                    ->columns(2)
                            ]),
                        Tabs\Tab::make('Invoice')
                            ->schema([
                                Section::make('Invoice Header')
                                    ->collapsible()
                                    ->schema([
                                        TextInput::make('inv_title')
                                            ->label('Title'),
                                        FileUpload::make('inv_logo')
                                            ->image()
                                            ->label('Logo')
                                            ->maxSize(1024)
                                            ->label('Header')
                                            ->directory('invoice-header')
                                            ->columnSpanFull(),
                                        TextInput::make('inv_email')
                                            ->label('Email')
                                            ->email()
                                            ->columns(1),
                                        TextInput::make('inv_phone')
                                            ->label('Phone')
                                            ->tel()
                                            ->columns(1),
                                    ])
                                    ->columns(2),
                                Section::make('Invoice Footer')
                                    ->collapsible()
                                    ->schema([
                                        Repeater::make('invoice_bank')
                                            ->schema([
                                                TextInput::make('bank')
                                                    ->label('Bank Name')
                                                    ->required(),
                                                TextInput::make('name')
                                                    ->label('Account Name')
                                                    ->required(),
                                                TextInput::make('account')
                                                    ->label('Account Number')
                                                    ->numeric()
                                                    ->required(),

                                            ]),
                                    ])
                            ]),
                        Tabs\Tab::make('Gallery')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Toggle::make('fr_gallery_status')
                                            ->label('Status'),
                                        TextInput::make('fr_gallery_title')
                                            ->label('Title'),
                                        TextInput::make('fr_gallery_subtitle')
                                            ->label('Sub Title'),
                                    ]),
                                Repeater::make('fr_gallery')
                                    ->label('Gallery')
                                    ->schema([
                                        FileUpload::make('image')
                                            ->label('')
                                            ->directory('gallery')
                                            ->required(),
                                    ])
                            ]),
                        Tabs\Tab::make('Jumbotron')
                            ->schema([
                                FileUpload::make('fr_jumbotron')
                                    ->label('Jumbotron Image')
                                    ->getUploadedFileNameForStorageUsing(
                                        fn (TemporaryUploadedFile $file): string => (string) str('jumbotron.' . $file->getClientOriginalExtension())
                                    )
                                    ->directory('frontend'),
                                TextInput::make('fr_jumbotron_title')
                                    ->label('Jumbotron Title')
                                    ->required(),
                                Textarea::make('fr_jumbotron_subtitle')
                                    ->rows(3)
                            ]),
                        Tabs\Tab::make('Promo')
                            ->schema([
                                Repeater::make('fr_promos')
                                    ->label('Promo')
                                    ->schema([
                                        FileUpload::make('image')->required(),
                                        TextInput::make('link')->label('Link')->url()->required(),
                                    ])

                            ]),
                        Tabs\Tab::make('Testimonial')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Toggle::make('fr_testimonial_status')
                                            ->label('Status'),
                                        TextInput::make('fr_testimonial_title')
                                            ->label('Title'),
                                        TextInput::make('fr_testimonial_subtitle')
                                            ->label('Sub Title'),
                                    ]),
                                Repeater::make('fr_testimonial')
                                    ->label('Testimonial')
                                    ->schema([
                                        FileUpload::make('image')
                                            ->label('')
                                            ->directory('testimonial')
                                            ->required(),
                                    ])
                            ]),

                        Tabs\Tab::make('Website')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        TextInput::make('title')
                                            ->required()
                                            ->label('Title'),
                                        TextInput::make('subtitle')
                                            ->label('Sub Title'),
                                        Textarea::make('description')
                                            ->rows(3)
                                            ->label('Description')
                                            ->maxLength(150)
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                                FileUpload::make('logo'),
                                FileUpload::make('favicon')
                                    ->getUploadedFileNameForStorageUsing(
                                        fn (TemporaryUploadedFile $file): string => (string) str('favicon.' . $file->getClientOriginalExtension())
                                    )
                                    ->directory('favicon')
                            ]),
                    ])
                    ->activeTab(1)

            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Submit')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        // dd($this->form->getState());
        try {
            $data = $this->form->getState();
            $website = Website::first();
            // dd($website);
            $website->update($data);

            // $website->update([
            //     'title' => $data['title'],
            //     'subtitle' => $data['subtitle'],
            //     'description' => $data['description'],
            //     'favicon' => $data['favicon'],
            //     'logo' => $data['logo'],
            // ]);

            Notification::make()
                ->success()
                ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
                ->send();
        } catch (Halt $exception) {
            return;
        }
    }
}

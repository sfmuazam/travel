<?php

namespace App\Filament\User\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;

class Profile extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = "Profile";

    protected static string $view = 'filament.user.pages.profile';
    protected static ?int $navigationSort = 5;

    public ?array $data = [];

    public function mount(): void
    {
        // $this->form->fill();
        $this->form->fill(auth()->user()->attributesToArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // TextInput::make('id'),
                TextInput::make('name'),
                TextInput::make('email'),
                TextInput::make('phone_number'),
                Textarea::make('address')->rows(3),
            ])
            ->columns(2)
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
        try {
            $data = $this->form->getState();

            auth()->user()->update($data);
            Notification::make()
                ->success()
                ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
                ->send();
        } catch (Halt $exception) {
            Notification::make()
                ->danger()
                ->title('Error')
                ->send();
        }
    }
}

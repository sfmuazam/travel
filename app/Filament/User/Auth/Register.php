<?php

namespace App\Filament\User\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register as AuthRegister;

class Register extends AuthRegister {
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                TextInput::make('phone_number')
                    ->label('Phone Number')
                    ->tel()
                    ->required()
            ]);
    }
}
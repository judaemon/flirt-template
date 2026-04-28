<?php

namespace App\Filament\Pages;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    protected static string $layout = 'layouts.minimal';

    protected string $view = 'filament.pages.login';

    public function loginWithNMS()
    {
        return redirect(route('nms'));
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getCompanyEmailFormComponent(),
                $this->getPasswordFormComponent(),
            ])
            ->statePath('data');
    }

    protected function getCompanyEmailFormComponent(): Component
    {
        return TextInput::make('company_email')
            ->label(__('Email'))
            ->email()
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.company_email' => __('filament-panels::auth/pages/login.messages.failed'),
        ]);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'company_email' => $data['company_email'],
            'password' => $data['password'],
        ];
    }

    public function getHeading(): string|Htmlable
    {
        return __('Log In');
    }
}

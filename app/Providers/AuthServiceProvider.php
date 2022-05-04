<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
/*
        ResetPassword::toMailUsing(function ($notifiable, $url)
        {
            return (new MailMessage())
                ->subject('Изменение пароля')
                ->line('Нажмите на кнопку ниже, чтобы изменить пароль')
                ->action('Изменить', $url);
        });*/
/*
        ResetPassword::createUrlUsing(function ($user, string $token) {
            $url = url('/reset-password?token'.$token);

            return $url;

        });*/

        VerifyEmail::toMailUsing(function ($notifiable, $url)
        {
            return (new MailMessage())
                ->subject('Подтверждения адреса электронной почты')
                ->line('Нажмите на кнопку ниже, чтобы подтвердить адрес электронной почты')
                ->action('Подтвердить', $url);
        });

    }
}

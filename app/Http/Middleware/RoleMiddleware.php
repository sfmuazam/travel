<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Session;

class RoleMiddleware extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    protected function authenticate($request, array $guards)
    {
        $auth = Filament::auth();
        $user = $auth->user();
        $panel = Filament::getCurrentPanel();

        // if (!($auth->check()
        //     && $user instanceof FilamentUser
        //     && $user->canAccessPanel($panel))) {
        //     // Session::flush();
        //     $this->unauthenticated($request, $guards);
        // }

        if (!$auth->check()) {
            $this->unauthenticated($request, $guards);

            return;
        }
        $newpanel = new Panel();
        $newpanel->id('admin');

        // dd($panel);
        if ($auth->check()) {
            if ($user->role == 'admin') {
                if (!$panel->id('admin')) {
                    // if ($user->canAccessPanel($newpanel->id('admin'))) {
                        return redirect()->route('filament.admin.pages.dashboard');
                    // }
                }
            } elseif ($user->role == 'user' || $user->role == 'agent') {
                if (!$panel->id('user')) {
                    return redirect()->route('filament.user.pages.dashboard');
                }
            }
        }

        abort_if(
            $user instanceof FilamentUser ?
                (! $user->canAccessPanel($panel)) :
                (config('app.env') !== 'local'),
            403,
        );
    }

    protected function redirectTo($request): ?string
    {
        return Filament::getLoginUrl();
    }
}

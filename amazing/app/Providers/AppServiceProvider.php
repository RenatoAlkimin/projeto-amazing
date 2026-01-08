<?php

namespace App\Providers;

use App\Support\Context\PortalContext;
use App\Support\Context\ScopeContext;
use App\Support\Navigation\SidebarBuilder;
use App\View\Composers\SidebarComposer;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Contextos por request (safe pra Octane/long-running)
        $this->app->scoped(PortalContext::class, fn () => new PortalContext());

        $this->app->scoped(ScopeContext::class, function (Application $app) {
            /** @var Request $request */
            $request = $app->make(Request::class);
            return new ScopeContext($request);
        });

        // Builder do sidebar
        $this->app->bind(SidebarBuilder::class, function (Application $app) {
            return new SidebarBuilder(
                $app->make(PortalContext::class),
                $app->make(ScopeContext::class),
                $app->make(Request::class),
            );
        });
    }

    public function boot(): void
    {
        // Sempre que renderizar o partial do sidebar, injeta os dados
        View::composer('partials.sidebar', SidebarComposer::class);
    }
}

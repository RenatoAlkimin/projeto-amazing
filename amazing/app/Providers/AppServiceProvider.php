<?php

namespace App\Providers;

use App\Support\Access\TenantModules; // <-- add
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
        $this->app->scoped(PortalContext::class, fn() => new PortalContext());

        $this->app->scoped(ScopeContext::class, function (Application $app) {
            /** @var Request $request */
            $request = $app->make(Request::class);
            return new ScopeContext($request);
        });

        // TenantModules (pode ser scoped ou singleton; scoped é mais consistente com teu padrão)
        $this->app->scoped(TenantModules::class, fn() => new TenantModules());

        // Builder do sidebar
        $this->app->bind(SidebarBuilder::class, function (Application $app) {
            return new SidebarBuilder(
                $app->make(PortalContext::class),
                $app->make(ScopeContext::class),
                $app->make(TenantModules::class),
                $app->make(\App\Support\Access\Permissions::class),
                $app->make(Request::class),
            );
        });
    }

    public function boot(): void
    {
        View::composer('partials.sidebar', SidebarComposer::class);
    }
}

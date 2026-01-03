<?php

namespace App\View\Composers;

use App\Support\Context\PortalContext;
use App\Support\Context\ScopeContext;
use App\Support\Navigation\SidebarBuilder;
use Illuminate\View\View;

class SidebarComposer
{
    public function __construct(
        private readonly SidebarBuilder $builder,
        private readonly PortalContext $portal,
        private readonly ScopeContext $scope,
    ) {}

    public function compose(View $view): void
    {
        $view->with([
            'sidebarSections' => $this->builder->build(),
            'currentPortal' => [
                'id' => $this->portal->currentId(),
                'label' => $this->portal->label(),
            ],
            'currentScope' => $this->scope->current(),
        ]);
    }
}

<?php

namespace App\View\Composers;

use App\Support\Navigation\SidebarBuilder;
use Illuminate\View\View;

class SidebarComposer
{
    public function __construct(private SidebarBuilder $sidebar) {}

    public function compose(View $view): void
    {
        $view->with('sidebarSections', $this->sidebar->build());
    }
}

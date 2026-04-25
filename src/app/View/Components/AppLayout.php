<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    public function __construct(
        public ?string $seoTitle = null,
        public ?string $seoDescription = null,
        public ?string $ogImage = null,
        public string  $ogType = 'website',
        public ?string $canonical = null,
    ) {}

    public function render(): View
    {
        return view('layouts.app');
    }
}

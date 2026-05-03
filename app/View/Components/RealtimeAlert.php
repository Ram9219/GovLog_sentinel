<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class RealtimeAlert extends Component
{
    public function render(): View
    {
        return view('components.realtime-alert');
    }
}

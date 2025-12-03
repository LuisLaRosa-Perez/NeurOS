<?php

namespace App\Livewire;

use Livewire\Component;

class VlmInteraction extends Component
{
    public $prompt;
    public $error;

    public function render()
    {
        return view('livewire.vlm-interaction');
    }
}
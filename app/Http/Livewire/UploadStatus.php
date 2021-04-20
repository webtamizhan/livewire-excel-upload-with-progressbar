<?php

namespace App\Http\Livewire;

use App\Models\Upload;
use Livewire\Component;

class UploadStatus extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('livewire.upload-status');
    }
}

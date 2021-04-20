<?php

namespace App\Http\Livewire;

use App\Imports\ExcelImport;
use App\Models\Upload;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class ExcelUpload extends Component
{
    use WithFileUploads;

    public $excelFile;
    public $fileName;
    public $isUploaded = false;


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('livewire.excel-upload');
    }

    public function updated($value)
    {
        $this->fileName = $this->excelFile->getClientOriginalName();
    }

    public function uploadExcel()
    {
        $this->validate(
            [
                'excelFile' => 'required'
            ]
        );

        $upload = new Upload();
        $upload->file_name = $this->excelFile->getClientOriginalName();
        $upload->uploaded_at = date("Y-m-d H:i:s");
        $upload->file_size = $this->excelFile->getSize();
        $upload->file_ext = $this->excelFile->getClientOriginalExtension();
        $upload->file_type = $this->excelFile->getClientMimeType();
        $upload->created_at = date("Y-m-d H:i:s");
        $upload->status = "uploaded";
        $upload->save();

        $destinationPath = 'uploads';
        $path = Storage::putFile($destinationPath, $this->excelFile);

        ray($path);

        $import = new ExcelImport($upload->id);
        Excel::import($import, $path);

        $this->isUploaded = true;
        $this->fileName = '';
    }
}

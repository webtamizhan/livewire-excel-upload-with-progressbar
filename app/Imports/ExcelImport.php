<?php

namespace App\Imports;

use App\Models\Upload;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\BeforeImport;

class ExcelImport implements ToModel, WithHeadingRow, WithChunkReading, WithEvents, ShouldQueue
{
    private $upload_id;

    public function __construct(int $upload_id)
    {
        $this->upload_id = $upload_id;
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            // Handle by a closure.
            BeforeImport::class => function (BeforeImport $event) {
                $total_rows = collect($event->reader->getTotalRows())->flatten()->values();
                $tmp_total_rows = isset($total_rows[0]) ? $total_rows[0] - 1 : 0;
                ray("total rows " . $tmp_total_rows);
                $upload = Upload::find($this->upload_id);
                $upload->status = "in progress";
                $upload->total = $tmp_total_rows;
                $upload->save();
            },
            // Handle by a closure.
            AfterImport::class => function (AfterImport $event) {
                $upload = Upload::find($this->upload_id);
                $upload->status = "finished";
                $upload->save();
            },

        ];
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        //Do your magic here

        //get processed from current
        $uploadRow = Upload::find($this->upload_id);
        $uploadRow->current = $uploadRow->current > 0 ? $uploadRow->current + 1 : 1;
        $uploadRow->save();

    }

    public function chunkSize(): int
    {
        return 500; // change your chunk size
    }

    public function transformDate($value, $format = 'Y-m-d')
    {
        try {
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {
            return \Carbon\Carbon::createFromFormat($format, $value);
        }
    }
}

<?php

namespace App\Exports;

use App\Models\ChaleError;
use App\Models\General;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToArray;

class ChaleErrorExport implements FromCollection, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $my_category;
    public $my_start_date;
    public $my_end_date;
    public $search_value;
    public function __construct($category, $start_date, $end_date, $value)
    {
        $this->search_value = $value;
        $this->my_category = $category;
        $this->my_start_date = $start_date;
        $this->my_end_date = $end_date;
    }

    public function collection()
    {
        try {
            $chunkSize = 1000;
            // return ChaleError::all();
            // Add headings as the first row

            $my_date = General::addDayToDate($this->my_end_date, 1);

            $headings = [
                'Nominee', 'Nominee Phone', 'Nominator Phone', 'Code', 'Transaction ID', 'Response', 'Age Range', 'Created At'
            ];

            $errors = ChaleError::select('nominee', 'nominee_phone', 'nominator_phone', 'code', 'transaction_id', 'response', 'dob', 'created_at')
                ->get();

            if ($this->my_category !== null && $this->my_start_date == null && $this->my_end_date == null) {
                $errors = ChaleError::where('response', 'LIKE', "%$this->my_category%")
                    ->select('nominee', 'nominee_phone', 'nominator_phone', 'code', 'transaction_id', 'response', 'dob', 'created_at')
                    ->get();
            } elseif ($this->my_category !== null && $this->my_start_date !== null && $this->my_end_date !== null) {
                $errors = ChaleError::where('response', 'LIKE', "%$this->my_category%")
                    ->select('nominee', 'nominee_phone', 'nominator_phone', 'code', 'transaction_id', 'response', 'dob', 'created_at')
                    ->whereBetween('created_at', [$this->my_start_date, $my_date])
                    ->get();
            } elseif ($this->my_category !== null && $this->my_start_date !== null && $this->my_end_date == null) {
                $errors = ChaleError::where('response', 'LIKE', "%$this->my_category%")
                    ->select('nominee', 'nominee_phone', 'nominator_phone', 'code', 'transaction_id', 'response', 'dob', 'created_at')
                    ->where('created_at', '=', $this->my_start_date)
                    ->get();
            } elseif ($this->my_category !== null && $this->my_start_date == null && $this->my_end_date !== null) {
                $errors = ChaleError::where('response', 'LIKE', "%$this->my_category%")
                    ->select('nominee', 'nominee_phone', 'nominator_phone', 'code', 'transaction_id', 'response', 'dob', 'created_at')
                    ->where('created_at', '>=', $this->my_end_date)
                    ->get();
            } elseif ($this->my_category == null && $this->my_start_date !== null && $this->my_end_date !== null) {
                $errors = ChaleError::select('nominee', 'nominee_phone', 'nominator_phone', 'code', 'transaction_id', 'response', 'dob', 'created_at')
                    ->whereBetween('created_at', [$this->my_start_date, $my_date])
                    ->get();
            } elseif ($this->my_category == null && $this->my_start_date !== null && $this->my_end_date == null) {
                $errors = ChaleError::select('nominee', 'nominee_phone', 'nominator_phone', 'code', 'transaction_id', 'response', 'dob', 'created_at')
                    ->where('created_at', '<=', $this->my_start_date)
                    ->get();
            } elseif ($this->search_value != '' && $this->my_category == null && $this->my_start_date == null && $this->my_end_date == null) {
                $searchString = $this->search_value;
                $errors = ChaleError::select('nominee', 'nominee_phone', 'nominator_phone', 'code', 'transaction_id', 'response', 'dob', 'created_at')
                    ->where(function ($query) use ($searchString) {
                        $columns = ['nominee', 'nominee_phone', 'nominator_phone', 'code', 'transaction_id', 'response', 'dob', 'created_at'];

                        foreach ($columns as $column) {
                            $query->orWhere($column, 'like', '%' . $searchString . '%');
                        }
                    })->get();
            }

            // Process data in chunks
            // return $errors->chunk($chunkSize)->flatMap(function ($chunk) use ($headings) {
            //     $collection = new Collection([$headings]); // Add headings as the first row

            //     // Add data rows
            //     $dataRows = $chunk->map(function ($row) {
            //         return $this->map($row);
            //     });

            //     return $collection->merge($dataRows);
            // });
            // return $errors->flatMap(function ($chunk) use ($headings) {
            //     $collection = new Collection([$headings]); // Add headings as the first row

            //     // Add data rows
            //     $dataRows = $chunk->map(function ($row) {
            //         return $this->map($row);
            //     });

            //     return $collection->merge($dataRows);
            // });
            $collection = collect([$headings]);

            // Map data rows
            $dataRows = $errors->map(function ($row) {
                return $this->map($row);
            });

            // Merge the collection with data rows
            return $collection->merge($dataRows);
        } catch (\Throwable $th) {
            Log::info("CHALE ERRORS EXPORT: " . $th->getMessage() . "\nLINE NUMBER: " . $th->getLine());
        }
    }

    public function map($row): array
    {
        // Log::info("DATA LOG\n" . json_encode($row));
        // return $row;
        // Ensure $row is an instance of ChaleError model
        if (is_array($row)) {
            return $row; // Return the array as is, assuming it's already in the expected format
        }

        return [
            'Nominee' => $row->nominee ?? 'N/A',
            'Nominee Phone' => $row->nominee_phone ?? 'N/A',
            'Nominator Phone' => $row->nominator_phone ?? 'N/A',
            'Code' => $row->code ?? null,
            'Transaction ID' => $row->transaction_id ?? 'N/A',
            'Response' => $row->response ?? 'N/A',
            'Age Range' => $row->dob ?? 'N/A',
            'Created At' => $row->created_at->format('Y-m-d H:i:s') ?? 'N/A',
        ];
    }
}

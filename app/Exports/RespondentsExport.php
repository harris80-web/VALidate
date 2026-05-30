<?php


namespace App\Exports;

use App\Enums\Gender;
use App\Enums\RespondentType;
use App\Models\Respondent;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RespondentsExport implements FromCollection, WithHeadings, WithMapping
{

    protected $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Region',
            'Client Type',
            'Email',
            'Age',
            'Gender',
            'Service Availed',
            'Suggestion',
            'Submitted Date',
            'Created At',
        ];
    }

    public function map($r): array
    {
        return [
            $r->id,

            // region (change depending on your relationship field)
            $r->region->name ?? 'N/A',

            // format type
            RespondentType::getDescription($r->type),

            $r->email ?? 'N/A',

            $r->age,

            // format gender: 1=Male, 2=Female (example)
            Gender::getDescription($r->gender),

            $r->service,

            $r->suggestion,

            // format submitted date
            $r->submitted_date ? date('Y-m-d', strtotime($r->submitted_date)) : '',

            // format created_at
            $r->created_at ? $r->created_at->format('Y-m-d H:i:s') : '',
        ];
    }
}

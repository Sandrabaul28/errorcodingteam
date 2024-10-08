<?php

namespace App\Exports;

use App\Models\Farmer;
use App\Models\Plant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class HVCDPExport implements FromCollection, WithHeadings, WithStyles
{
    protected $barangay;
    protected $fromDate;
    protected $toDate;

    // Constructor to accept barangay, from_date, and to_date
    public function __construct($barangay, $fromDate = null, $toDate = null)
    {
        $this->barangay = $barangay;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }

    // Get the data from the Farmers model, filtered by barangay and dates
    public function collection()
    {
        $farmersQuery = Farmer::with('inventoryValuedCrops.plant', 'affiliation');

        // Filter by barangay
        if ($this->barangay) {
            $farmersQuery->whereHas('affiliation', function ($query) {
                $query->where('name_of_barangay', $this->barangay);
            });
        }

        // Filter by date range if provided
        if ($this->fromDate && $this->toDate) {
            $fromDateTime = $this->fromDate . ' 00:00:00';
            $toDateTime = $this->toDate . ' 23:59:59';
            $farmersQuery->whereBetween('created_at', [$fromDateTime, $toDateTime]);
        }

        $farmers = $farmersQuery->get()->map(function ($farmer) {
            $data = [
                $farmer->id,
                $farmer->last_name,
                $farmer->first_name,
                $farmer->middle_name,
                $farmer->extension,
            ];

            // Plant counts
            $plantCounts = [];
            $uniquePlants = Plant::all();

            foreach ($uniquePlants as $plant) {
                // Get count of plants for this farmer
                $count = $farmer->inventoryValuedCrops->where('plant.name_of_plants', $plant->name_of_plants)->first()->count ?? 0;
                $plantCounts[] = $count;
            }

            return array_merge($data, $plantCounts);
        });

        return $farmers;
    }

    // Define the headings for the Excel file
    public function headings(): array
    {
        $uniquePlants = Plant::all();
        $plantHeadings = $uniquePlants->pluck('name_of_plants')->toArray();

        return array_merge([
            'No.',
            'Surname',
            'Firstname',
            'MI',
            'EXT',
        ], $plantHeadings);
    }

    // Apply styles to the spreadsheet
    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $sheet->getStyle('A1:' . 'E1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['argb' => 'FF4CAF50'],
            ],
        ]);

        // Auto-fit columns
        foreach (range('A', 'Z') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return $sheet;
    }
}

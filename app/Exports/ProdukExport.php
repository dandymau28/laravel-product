<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;
use \Maatwebsite\Excel\Sheet;
use DB;

class ProdukExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithDrawings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table('master.products')->select('product_code', 'product_name', 'price')->get();
    }

    public function register()
    {
        Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
            $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
        });
    }

    public function startCell(): string
    {
        return 'A2';
    }

    public function headings(): array
    {
        return [
            [
                '',
                'Data Produk Nutech'
            ],
            [
            'Kode Produk',
            'Nama Produk',
            'Harga',
            ]
        ];
    }

    public function registerEvents(): array {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells('B1:C1');

                $highestColumn = $event->sheet->getDelegate()->getHighestColumn();
                $highestRow = $event->sheet->getDelegate()->getHighestRow();
                $rowData = 'A2:' . $highestColumn . $highestRow;

                $styleData = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => [ 'argb' => '000000' ]
                        ]
                    ]
                ];

                $event->sheet->getDelegate()->getStyle($rowData)->applyFromArray($styleData);

                $headers = 'A1:C1'; //headers
                $event->sheet->getDelegate()->getStyle($headers)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle($headers)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $event->sheet->getDelegate()->getStyle($headers)->getFont()->setSize(16);
                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(32);

                $event->sheet->getDelegate()->getStyle($rowData)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $event->sheet->getDelegate()->getStyle($rowData)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $event->sheet->getDelegate()->getStyle($rowData)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $event->sheet->getDelegate()->getStyle($rowData)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                $cellRange = 'A2:C2'; //nama kolom
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFill()->getStartColor()->setARGB('00FF00');
            },
            BeforeExport::class => function(BeforeExport $event) {
                $event->writer->getProperties()->setTitle('Data Produk Nutech');
            }
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo nutech');
        $drawing->setPath(public_path('/creator/img/nutech.png'));
        $drawing->setHeight(40);
        $drawing->setCoordinates('A1');

        return $drawing;
    }
}

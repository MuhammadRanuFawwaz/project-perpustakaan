<?php

namespace App\Exports;

use App\Models\Buku;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BukuExport implements FromCollection, WithHeadings, WithMapping, WithEvents, WithCustomStartCell, WithDrawings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function startCell(): string
    {
        return 'A7';
    }

    public function collection()
    {
        $query = Buku::with([
            'kategori',
            'ddc',
        ]);

        if ($this->request->start_date) {
            $query->whereDate('tanggal_kirim', '>=', $this->request->start_date);
        }

        if ($this->request->end_date) {
            $query->whereDate('tanggal_kirim', '<=', $this->request->end_date);
        }

        if ($this->request->id_kategori) {
            $query->where('id_kategori', $this->request->id_kategori);
        }

        if ($this->request->id_ddc) {
            $query->where('id_ddc', $this->request->id_ddc);
        }

        if ($this->request->jenjang_kelas) {
            $query->where('jenjang_kelas', $this->request->jenjang_kelas);
        }

        if ($this->request->search) {
            $query->where(function ($q) {
                $q->where('kode_buku', 'like', '%' . $this->request->search . '%')
                    ->orWhere('judul_buku', 'like', '%' . $this->request->search . '%')
                    ->orWhere('kode_ddc', 'like', '%' . $this->request->search . '%')
                    ->orWhereHas('ddc', function ($ddc) {
                        $ddc->where('kode_ddc', 'like', '%' . $this->request->search . '%')
                            ->orWhere('nama_ddc', 'like', '%' . $this->request->search . '%');
                    });
            });
        }

        return $query
            ->orderBy('tanggal_kirim', 'asc')
            ->orderBy('judul_buku', 'asc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Kode Buku',
            'Judul Buku',
            'Kategori',
            'DDC',
            'Kelas',
            'Stok',
            'Tanggal Kirim',
        ];
    }

    public function map($buku): array
    {
        return [
            $buku->kode_buku,
            $buku->judul_buku,
            $buku->kategori->nama_kategori ?? '-',
            ($buku->ddc->kode_ddc ?? $buku->kode_ddc ?? '-') . ' - ' . ($buku->ddc->nama_ddc ?? '-'),
            $buku->jenjang_kelas ?? 'Umum',
            $buku->stok,
            \Carbon\Carbon::parse($buku->tanggal_kirim)->format('d-m-Y'),
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();

        $drawing->setName('Logo');
        $drawing->setDescription('Logo Sekolah');
        $drawing->setPath(public_path('images/Smkn1Tarumajaya.png'));
        $drawing->setHeight(90);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(20);
        $drawing->setOffsetY(15);

        return $drawing;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                $sheet->mergeCells('A1:G6');

                $sheet->getStyle('A1:G6')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => '4A86E8',
                        ],
                    ],
                ]);

                $sheet->setCellValue('A1', 'DATA BUKU PERPUSTAKAAN SMKN 1 TARUMAJAYA');

                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                    ],
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical' => 'center',
                    ],
                ]);

                $sheet->getStyle('A7:G7')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical' => 'center',
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => 'thin',
                        ],
                    ],
                ]);

                $lastRow = $sheet->getHighestRow();

                $sheet->getStyle('A8:G' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => 'thin',
                        ],
                    ],
                ]);

                foreach (range('A', 'G') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                $sheet->getRowDimension(1)->setRowHeight(100);
            }
        ];
    }
}

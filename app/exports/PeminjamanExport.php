<?php

namespace App\Exports;

use App\Models\DetailPeminjaman;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class PeminjamanExport implements FromCollection, WithHeadings, WithMapping, WithEvents, WithCustomStartCell, WithDrawings
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
        $query = DetailPeminjaman::with(['peminjaman.pengunjung.kelas', 'buku']);

        if ($this->request->start_date) {
            $query->whereHas('peminjaman', function ($q) {
                $q->whereDate('tanggal_peminjaman', '>=', $this->request->start_date);
            });
        }

        if ($this->request->end_date) {
            $query->whereHas('peminjaman', function ($q) {
                $q->whereDate('tanggal_peminjaman', '<=', $this->request->end_date);
            });
        }

        if ($this->request->status_peminjaman) {
            $query->whereHas('peminjaman', function ($q) {
                $q->where('status_peminjaman', $this->request->status_peminjaman);
            });
        }

        if ($this->request->search) {
            $query->where(function ($q) {
                $q->whereHas('peminjaman.pengunjung', function ($pengunjung) {
                    $pengunjung->where('nama_pengunjung', 'like', '%' . $this->request->search . '%');
                })
                    ->orWhereHas('buku', function ($buku) {
                        $buku->where('kode_buku', 'like', '%' . $this->request->search . '%')
                            ->orWhere('judul_buku', 'like', '%' . $this->request->search . '%');
                    });
            });
        }

        return $query
            ->join('peminjaman', 'detail_peminjaman.id_peminjaman', '=', 'peminjaman.id')
            ->orderBy('peminjaman.tanggal_peminjaman', 'asc')
            ->select('detail_peminjaman.*')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Nama Peminjam',
            'Jenis',
            'Kelas',
            'Jurusan',
            'Kode Buku',
            'Judul Buku',
            'Tanggal Pinjam',
            'Tanggal Kembali',
            'Status Buku',
        ];
    }

    public function map($detail): array
    {
        $pengunjung = $detail->peminjaman->pengunjung;

        return [
            $pengunjung->nama_pengunjung ?? '-',
            $pengunjung->jenis_pengunjung ?? '-',
            $pengunjung->kelas->nama_kelas ?? '-',
            $pengunjung->kelas->jurusan ?? '-',
            $detail->kode_buku,
            $detail->buku->judul_buku ?? '-',
            Carbon::parse($detail->peminjaman->tanggal_peminjaman)->format('d-m-Y'),
            $detail->tanggal_dikembalikan ? Carbon::parse($detail->tanggal_dikembalikan)->format('d-m-Y') : '-',
            ucfirst($detail->status_buku),
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

                $sheet->mergeCells('A1:I6');

                $sheet->getStyle('A1:I6')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => '4A86E8',
                        ],
                    ],
                ]);

                $sheet->setCellValue('A1', 'DATA PEMINJAMAN PERPUSTAKAAN SMKN 1 TARUMAJAYA');

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

                $sheet->getStyle('A7:I7')->applyFromArray([
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

                $sheet->getStyle('A8:I' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => 'thin',
                        ],
                    ],
                ]);

                foreach (range('A', 'I') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                $sheet->getRowDimension(1)->setRowHeight(100);
            }
        ];
    }
}

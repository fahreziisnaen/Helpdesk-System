<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TicketsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $tickets;
    protected $startDate;
    protected $endDate;

    public function __construct($tickets, $startDate, $endDate)
    {
        $this->tickets = $tickets;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return $this->tickets;
    }

    public function headings(): array
    {
        return [
            'No. Tiket',
            'Judul',
            'Kategori',
            'Prioritas',
            'Status',
            'Dibuat Oleh',
            'Ditugaskan Ke',
            'Tanggal Dibuat',
            'Tanggal Selesai',
            'Tanggal Ditutup',
        ];
    }

    public function map($ticket): array
    {
        return [
            $ticket->ticket_number,
            $ticket->title,
            $ticket->categoryModel ? $ticket->categoryModel->name : ucfirst($ticket->category),
            ucfirst($ticket->priority),
            ucfirst(str_replace('_', ' ', $ticket->status)),
            $ticket->user->name,
            $ticket->assignedTechnician ? $ticket->assignedTechnician->name : '-',
            $ticket->created_at->format('d/m/Y H:i'),
            $ticket->solved_at ? $ticket->solved_at->format('d/m/Y H:i') : '-',
            $ticket->closed_at ? $ticket->closed_at->format('d/m/Y H:i') : '-',
        ];
    }

    public function title(): string
    {
        return 'Laporan Tiket';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }
}

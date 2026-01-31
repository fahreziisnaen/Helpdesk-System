<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TicketsExport;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Show report page
     */
    public function index()
    {
        $teknisi = User::where('role', 'teknisi')->get();
        return view('reports.index', compact('teknisi'));
    }

    /**
     * Generate report
     */
    public function generate(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'nullable|in:open,in_progress,solved,closed',
            'teknisi_id' => 'nullable|exists:users,id',
            'format' => 'required|in:excel,pdf',
        ]);

        $query = Ticket::with(['user', 'assignedTechnician', 'categoryModel'])
            ->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->teknisi_id) {
            $query->where('assigned_to', $request->teknisi_id);
        }

        $tickets = $query->get();

        if ($request->format === 'excel') {
            return Excel::download(
                new TicketsExport($tickets, $request->start_date, $request->end_date),
                'laporan-tiket-' . date('Y-m-d') . '.xlsx'
            );
        } else {
            $pdf = Pdf::loadView('reports.pdf', [
                'tickets' => $tickets,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'filters' => [
                    'status' => $request->status,
                    'teknisi' => $request->teknisi_id ? User::find($request->teknisi_id)->name : null,
                ],
            ]);

            return $pdf->download('laporan-tiket-' . date('Y-m-d') . '.pdf');
        }
    }
}

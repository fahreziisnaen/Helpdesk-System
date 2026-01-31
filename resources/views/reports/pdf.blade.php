<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Tiket</title>
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #0078D4;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #0078D4;
            margin: 0;
        }
        .info {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #0078D4;
            color: white;
            padding: 10px;
            text-align: left;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>HELPDESK SYSTEM</h1>
        <h2>Laporan Tiket</h2>
    </div>

    <div class="info">
        <p><strong>Periode:</strong> {{ date('d/m/Y', strtotime($start_date)) }} - {{ date('d/m/Y', strtotime($end_date)) }}</p>
        @if($filters['status'])
        <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $filters['status'])) }}</p>
        @endif
        @if($filters['teknisi'])
        <p><strong>Teknisi:</strong> {{ $filters['teknisi'] }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>No. Tiket</th>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Prioritas</th>
                <th>Status</th>
                <th>User</th>
                <th>Teknisi</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
            <tr>
                <td>{{ $ticket->ticket_number }}</td>
                <td>{{ $ticket->title }}</td>
                <td>{{ $ticket->categoryModel ? $ticket->categoryModel->name : ucfirst($ticket->category) }}</td>
                <td>{{ ucfirst($ticket->priority) }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</td>
                <td>{{ $ticket->user->name }}</td>
                <td>{{ $ticket->assignedTechnician ? $ticket->assignedTechnician->name : '-' }}</td>
                <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
        <p>Total Tiket: {{ $tickets->count() }}</p>
    </div>
</body>
</html>

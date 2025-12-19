<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Planificaciones</title>
    <style>
        body {
            font-family: sans-serif;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .table th {
            background-color: #f2f2f2;
            text-align: left;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            width: 100px;
            height: auto;
        }
        .header h1 {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('logo.png') }}" alt="Logo">
        <h1>Reporte de Planificaciones</h1>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Docente</th>
                <th>Área Académica</th>
                <th>Estado</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $item)
                <tr>
                    <td>{{ $item->user->name ?? 'N/A' }}</td>
                    <td>{{ $item->subject->name ?? 'N/A' }}</td>
                    <td>{{ ucfirst($item->status) }}</td>
                    <td>{{ $item->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">No se encontraron resultados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>

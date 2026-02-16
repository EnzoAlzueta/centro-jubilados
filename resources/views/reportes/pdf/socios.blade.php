<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Reporte de Socios</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            color: #333;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #777;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            padding: 10px;
            text-align: left;
        }

        td {
            border-bottom: 1px solid #dee2e6;
            padding: 8px;
        }

        .badge {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }

        .badge-success {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .badge-danger {
            background-color: #f8d7da;
            color: #842029;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="title">Centro de Jubilados</div>
        <div>Reporte General de Socios</div>
        <div class="text-right">Fecha: {{ $fecha }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nro</th>
                <th>Nombre y Apellido</th>
                <th>DNI</th>
                <th>Barrio</th>
                <th>Teléfono</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($socios as $socio)
            <tr>
                <td>{{ $socio->numero_socio }}</td>
                <td>{{ $socio->apellido }}, {{ $socio->nombre }}</td>
                <td>{{ $socio->dni }}</td>
                <td>{{ $socio->barrio ? $socio->barrio->nombre : 'N/A' }}</td>
                <td>{{ $socio->telefono }}</td>
                <td>
                    <span class="badge {{ $socio->habilitado ? 'badge-success' : 'badge-danger' }}">
                        {{ $socio->habilitado ? 'Activo' : 'Inactivo' }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Página
        <script type="text/php">echo $PAGE_NUM;</script> de
        <script type="text/php">echo $PAGE_COUNT;</script>
    </div>
</body>

</html>
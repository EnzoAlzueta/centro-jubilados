<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Reporte de Alquileres</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            color: #333;
            font-size: 11px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #28a745;
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
            color: #28a745;
            margin-bottom: 5px;
        }

        .info {
            margin-bottom: 20px;
            font-size: 12px;
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
            font-size: 9px;
            font-weight: bold;
        }

        .badge-info {
            background-color: #cff4fc;
            color: #055160;
        }

        .badge-success {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .badge-warning {
            background-color: #fff3cd;
            color: #664d03;
        }

        .text-right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="title">Centro de Jubilados</div>
        <div>Reporte Mensual de Alquileres y Eventos</div>
        <div class="text-right">Fecha de Emisión: {{ $fecha }}</div>
    </div>

    <div class="info">
        Período: <span class="bold">{{ $nombreMes }} {{ $anio }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th>Fecha Evento</th>
                <th>Solicitante</th>
                <th>Sector</th>
                <th>Evento</th>
                <th>Precio</th>
                <th>Seña</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($alquileres as $alquiler)
            <tr>
                <td>{{ \Carbon\Carbon::parse($alquiler->fecha_evento)->format('d/m/Y') }}</td>
                <td>
                    @if($alquiler->socio_id)
                    {{ $alquiler->socio->apellido }}, {{ $alquiler->socio->nombre }}
                    @else
                    {{ $alquiler->solicitante_externo }} (EXT)
                    @endif
                </td>
                <td>{{ $alquiler->sector->nombre }}</td>
                <td>{{ $alquiler->tipo_evento }}</td>
                <td>${{ number_format($alquiler->precio, 2) }}</td>
                <td>${{ number_format($alquiler->seña_pagada, 2) }}</td>
                <td>
                    <span
                        class="badge {{ $alquiler->estado == 'finalizado' ? 'badge-success' : ($alquiler->estado == 'reservado' ? 'badge-info' : 'badge-warning') }}">
                        {{ ucfirst($alquiler->estado) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($alquileres->isEmpty())
    <p style="text-align: center; margin-top: 30px; color: #888;">No se encontraron alquileres en el período
        seleccionado.</p>
    @endif

    <div class="footer">
        Página
        <script type="text/php">echo $PAGE_NUM;</script> de
        <script type="text/php">echo $PAGE_COUNT;</script>
    </div>
</body>

</html>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Reporte de Caja</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            color: #333;
            font-size: 11px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #0dcaf0;
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
            color: #0dcaf0;
            margin-bottom: 5px;
        }

        .info {
            margin-bottom: 20px;
            font-size: 12px;
        }

        .summary-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .summary-item {
            margin-bottom: 5px;
            font-size: 13px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
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

        .text-success {
            color: #198754;
            font-weight: bold;
        }

        .text-danger {
            color: #dc3545;
            font-weight: bold;
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
        <div>Reporte Detallado de Caja y Movimientos</div>
        <div class="text-right">Fecha de Emisión: {{ $fecha }}</div>
    </div>

    <div class="info">
        Período: <span class="bold">{{ $nombreMes }} {{ $anio }}</span>
    </div>

    <div class="summary-box">
        <div class="summary-item">Total Ingresos: <span class="text-success">${{ number_format($totalIngresos, 2)
                }}</span></div>
        <div class="summary-item">Total Egresos: <span class="text-danger">${{ number_format($totalEgresos, 2) }}</span>
        </div>
        <div class="summary-item" style="border-top: 1px solid #ccc; padding-top: 5px; margin-top: 5px;">
            Saldo del Período: <span class="{{ $saldo >= 0 ? 'text-success' : 'text-danger' }}">${{
                number_format($saldo, 2) }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Concepto</th>
                <th>Categoría</th>
                <th>Tipo</th>
                <th class="text-right">Monto</th>
            </tr>
        </thead>
        <tbody>
            @foreach($movimientos as $movimiento)
            <tr>
                <td>{{ \Carbon\Carbon::parse($movimiento->fecha)->format('d/m/Y') }}</td>
                <td>{{ $movimiento->concepto }}</td>
                <td>{{ ucfirst($movimiento->categoria) }}</td>
                <td>
                    <span class="{{ $movimiento->tipo == 'ingreso' ? 'text-success' : 'text-danger' }}">
                        {{ ucfirst($movimiento->tipo) }}
                    </span>
                </td>
                <td class="text-right bold {{ $movimiento->tipo == 'ingreso' ? 'text-success' : 'text-danger' }}">
                    {{ $movimiento->tipo == 'ingreso' ? '+' : '-' }} ${{ number_format($movimiento->monto, 2) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($movimientos->isEmpty())
    <p style="text-align: center; margin-top: 30px; color: #888;">No se registraron movimientos en el período
        seleccionado.</p>
    @endif

    <div class="footer">
        Página
        <script type="text/php">echo $PAGE_NUM;</script> de
        <script type="text/php">echo $PAGE_COUNT;</script>
    </div>
</body>

</html>
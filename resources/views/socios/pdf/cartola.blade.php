<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Cartola de Pagos - Socio #{{ $socio->numero_socio }}</title>
    <style>
        @page {
            margin: 25px 40px;
        }

        body {
            font-family: Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 11px;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .header h1 {
            font-size: 17px;
            margin: 0;
            padding: 0;
        }

        .header h2 {
            font-size: 14px;
            margin: 5px 0 0 0;
            font-weight: bold;
        }

        table {
            border-collapse: collapse;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .info-table td {
            vertical-align: bottom;
            padding-top: 8px;
        }

        .info-line {
            border-bottom: 1px dashed #000;
        }

        .grilla-meses {
            width: 100%;
            table-layout: fixed;
        }

        .grilla-meses>tbody>tr>td {
            width: 33.333%;
            border: 1px dashed #000;
            padding: 10px 12px;
            vertical-align: top;
            height: 185px;
            /* Altura fija para evitar desbordes */
            box-sizing: border-box;
        }

        .ticket-header {
            text-align: center;
            font-size: 8px;
            font-weight: bold;
            margin-bottom: 12px;
            line-height: 1.2;
        }

        .ticket-mes {
            font-weight: bold;
            font-size: 12px;
            margin-top: 15px;
            margin-bottom: 20px;
        }

        .valign-bottom {
            vertical-align: bottom;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>Centro de Jubilados y Pensionados</h1>
        <h2>"ENTRE LAS SIERRAS" - Rivadavia 2204 - Sierras Bayas</h2>
    </div>

    <table class="info-table" cellpadding="0" cellspacing="0">
        <tr>
            <td width="42">Socio</td>
            <td class="info-line">&nbsp;{{ mb_strtoupper($socio->nombre . ' ' . $socio->apellido) }}</td>
            <td width="30" style="text-align: right;">Nº&nbsp;</td>
            <td width="80" class="info-line" style="text-align: center;">{{ $socio->numero_socio }}</td>
        </tr>
        <tr>
            <td width="60">Domicilio</td>
            <td class="info-line">&nbsp;{{ mb_strtoupper(($socio->calle ? $socio->calle->nombre : '') . ' ' .
                $socio->altura . ' (' .
                $socio->barrio->nombre . ')') }}</td>
            <td width="35" style="text-align: right;">Tel.&nbsp;</td>
            <td width="100" class="info-line" style="text-align: center;">{{ $socio->telefono }}</td>
        </tr>
    </table>

    @php
    $meses = [
    ['Diciembre', 'Noviembre', 'Octubre'],
    ['Septiembre', 'Agosto', 'Julio'],
    ['Junio', 'Mayo', 'Abril'],
    ['Marzo', 'Febrero', 'Enero']
    ];

    $mesesMapping = [
    'Enero' => 1, 'Febrero' => 2, 'Marzo' => 3, 'Abril' => 4,
    'Mayo' => 5, 'Junio' => 6, 'Julio' => 7, 'Agosto' => 8,
    'Septiembre' => 9, 'Octubre' => 10, 'Noviembre' => 11, 'Diciembre' => 12
    ];
    @endphp

    <table class="grilla-meses">
        <tbody>
            @foreach($meses as $fila)
            <tr>
                @foreach($fila as $mesNombre)
                @php
                $nroMes = $mesesMapping[$mesNombre];
                $cuota = $cuotasPagas->get($nroMes);
                @endphp
                <td>
                    <div class="ticket-header">
                        Centro de Jubilados y Pensionados<br>"Entre las Sierras" - S. Bayas
                    </div>

                    <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 6px;">
                        <tr>
                            <td width="35" class="valign-bottom" style="font-size: 11px;">Socio</td>
                            <td class="valign-bottom"
                                style="border-bottom: 1px dashed #666; font-size: 11px; padding-left: 5px;">
                                {{ mb_strtoupper($socio->nombre . ' ' . $socio->apellido) }}
                            </td>
                        </tr>
                    </table>

                    <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 6px;">
                        <tr>
                            <td width="18" class="valign-bottom" style="font-size: 11px;">Nº</td>
                            <td width="40" class="valign-bottom"
                                style="border-bottom: 1px dashed #666; text-align: center; font-size: 12px;">
                                {{ $socio->numero_socio }}
                            </td>
                            <td width="15" class="valign-bottom"
                                style="text-align: center; font-weight: bold; font-size: 12px;">$</td>
                            <td class="valign-bottom"
                                style="border-bottom: 1px dashed #666; text-align: center; font-size: 12px; font-weight: bold;">
                                {{ $cuota ? number_format($cuota->monto, 0, ',', '.') : '' }}
                            </td>
                        </tr>
                    </table>

                    <div class="ticket-mes" style="position: relative;">
                        {{ $mesNombre }}
                        @if($cuota)
                        <div
                            style="position: absolute; right: 0; top: 0; font-size: 8px; color: #333; font-weight: normal;">
                            PAGADO: {{ \Carbon\Carbon::parse($cuota->fecha_pago)->format('d/m/Y') }}
                        </div>
                        @endif
                    </div>

                    <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 35px;">
                        <tr>
                            <td width="40" class="valign-bottom" style="font-weight: bold; font-size: 12px;">
                                {{ $anio }}
                            </td>
                            <td class="valign-bottom" style="text-align: right;">
                                <div
                                    style="display:inline-block; width: 80px; border-top: 1px dashed #666; text-align: center; font-size: 9px; padding-top: 3px;">
                                    Tesorero
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
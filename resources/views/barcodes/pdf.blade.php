<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Étiquettes de Codes-Barres</title>
    <style>
        @page { margin: 10mm; }
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 2mm;
        }
        td {
            text-align: center;
            vertical-align: middle;
            padding: 2mm;
            border: 1px solid #e0e0e0;
            border-radius: 2mm;
            background-color: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .barcode-image {
            margin-bottom: 1mm;
            max-width: 100%;
            overflow: hidden;
        }
        .barcode-image svg {
            max-width: 100%;
            height: auto;
        }
        .barcode-text {
            font-size: 8px;
            color: #555;
            margin-top: 1mm;
            word-break: break-all;
        }
    </style>
</head>
<body>
@foreach ($barcodes->chunk($perPage) as $pageBarcodes)
    <table>
        @foreach ($pageBarcodes->chunk($columns) as $rowBarcodes)
            <tr>
                @foreach ($rowBarcodes as $barcode)
                    <td>
                        <div class="barcode-image">
                            {!! $barcodeGenerator->getBarcodeHTML($barcode, $barcodeType, $barcodeWidth, $barcodeHeight) !!}
                        </div>
                        @if ($showText)
                            <div class="barcode-text">{{ $barcode }}</div>
                        @endif
                    </td>
                @endforeach
                @for ($i = $rowBarcodes->count(); $i < $columns; $i++)
                    <td></td>
                @endfor
            </tr>
        @endforeach
    </table>
    @if (!$loop->last)
        <div style="page-break-after: always;"></div>
    @endif
@endforeach
</body>
</html>

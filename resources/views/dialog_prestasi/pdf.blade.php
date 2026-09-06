<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Laporan Dialog Prestasi</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #000;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
        }
        .header h3 {
            margin: 5px 0 0;
            font-size: 14px;
            text-transform: uppercase;
        }
        .section-title {
            font-weight: bold;
            font-size: 13px;
            margin: 15px 0 5px;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
        }
        table.info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table.info td {
            padding: 4px 6px;
            vertical-align: top;
        }
        table.info td.label {
            width: 25%;
            font-weight: bold;
        }
        table.issues {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table.issues th, table.issues td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
        }
        table.issues th {
            background-color: #f0f0f0;
            text-align: center;
            font-weight: bold;
        }
        .signature {
            width: 100%;
            margin-top: 30px;
        }
        .signature table {
            width: 100%;
            border-collapse: collapse;
        }
        .signature td {
            width: 50%;
            vertical-align: top;
            padding: 5px;
        }
        .signature .name {
            margin-top: 50px;
            font-weight: bold;
            text-decoration: underline;
        }
        .signature .position {
            margin-top: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
            color: #555;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Dialog Prestasi</h2>
        <h3>Pejabat Pendidikan Daerah</h3>
    </div>

    <div class="section-title">Maklumat Mesyuarat</div>
    <table class="info">
        <tr>
            <td class="label">Pengerusi</td>
            <td>: {{ $dialogPrestasiReport->pengerusi }}</td>
        </tr>
        <tr>
            <td class="label">Tarikh</td>
            <td>: {{ $dialogPrestasiReport->tarikh->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td class="label">Hari</td>
            <td>: {{ $dialogPrestasiReport->hari }}</td>
        </tr>
        <tr>
            <td class="label">Masa</td>
            <td>: {{ $dialogPrestasiReport->masa->format('H:i') }}</td>
        </tr>
        <tr>
            <td class="label">Tempat</td>
            <td>: {{ $dialogPrestasiReport->tempat }}</td>
        </tr>
        <tr>
            <td class="label">Kategori</td>
            <td>: {{ $dialogPrestasiReport->kategori ?: '-' }}</td>
        </tr>
    </table>

    <div class="section-title">Kehadiran</div>
    <table class="issues">
        <thead>
            <tr>
                <th style="width:5%">Bil</th>
                <th style="width:45%">Nama</th>
                <th style="width:50%">Jawatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dialogPrestasiReport->attendances as $index => $attendance)
                <tr>
                    <td style="text-align:center">{{ $index + 1 }}</td>
                    <td>{{ $attendance->nama }}</td>
                    <td>{{ $attendance->jawatan }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align:center">Tiada rekod kehadiran.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">Isu dan Tindakan</div>
    <table class="issues">
        <thead>
            <tr>
                 <th style="width:5%">Bil</th>
                 <th style="width:20%">Isu</th>
                 <th style="width:20%">Fokus</th>
                 <th style="width:25%">Tindakan</th>
                 <th style="width:20%">Sektor/Unit</th>
            </tr>
        </thead>
        <tbody>
             @forelse ($dialogPrestasiReport->issues as $index => $issue)
                 <tr>
                     <td style="text-align:center">{{ $index + 1 }}</td>
                     <td>{{ $issue->isu }}</td>
                     <td>{{ $issue->fokus }}</td>
                     <td>{{ $issue->tindakan }}</td>
                     <td>{{ $issue->taggedSektor?->nama ?? '-' }}<br><small>{{ $issue->taggedUnit?->nama ?? '-' }}</small></td>
                 </tr>
             @empty
                 <tr>
                     <td colspan="5" style="text-align:center">Tiada isu dan tindakan.</td>
                 </tr>
             @endforelse
        </tbody>
    </table>

    <div class="section-title">Pengesahan</div>
    <div class="signature">
        <table>
            <tr>
                <td>
                    <div>Dicatat Oleh:</div>
                    <div class="name">{{ $dialogPrestasiReport->dicatat_oleh }}</div>
                    <div class="position">{{ $dialogPrestasiReport->jawatan_pencatat }}</div>
                </td>
                <td>
                    <div>Disahkan Oleh:</div>
                    <div class="name">{{ $dialogPrestasiReport->disahkan_oleh }}</div>
                    <div class="position">{{ $dialogPrestasiReport->jawatan_pengesah }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Dijana pada {{ now()->format('d/m/Y H:i') }}
    </div>

</body>
</html>
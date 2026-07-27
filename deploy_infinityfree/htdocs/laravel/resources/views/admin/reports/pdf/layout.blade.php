<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #0f172a; }
        h2 { margin-bottom: 2px; color: #0f172a; }
        .subtitle { color: #64748b; margin-bottom: 14px; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #cbd5e1; padding: 6px 8px; text-align: left; }
        th { background-color: #0f172a; color: #fff; }
        tr:nth-child(even) { background-color: #f8fafc; }
        .footer { margin-top: 20px; font-size: 10px; color: #94a3b8; text-align: right; }
    </style>
</head>
<body>
    <h2>SMKN 9 Garut - @yield('title')</h2>
    <div class="subtitle">Dicetak pada {{ now()->format('d F Y H:i') }}</div>

    @yield('body')

    <div class="footer">Learning Management System - SMKN 9 Garut</div>
</body>
</html>

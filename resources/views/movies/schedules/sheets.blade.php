<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>座席配置</title>
</head>

<body>
    @if(session('error'))
    <div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin: 10px 0; border: 1px solid #f5c6cb; border-radius: 4px;">
        {{ session('error') }}
    </div>
    @endif
    <h1>座席配置</h1>

    <div>..スクリーン..</div>



    <table border="1">
        @php
        // データを行ごとにグループ化
        $groupedSheets = $sheets->groupBy('row');
        $reservedSheetIds = $reservations->pluck('sheet_id')->toArray();
        @endphp

        @foreach(['a', 'b', 'c'] as $row)
        <tr>
            @if(isset($groupedSheets[$row]))
            @foreach($groupedSheets[$row]->sortBy('column') as $sheet)
            <td>
                @if(in_array($sheet->id, $reservedSheetIds))
                {{-- 予約済みの場合 --}}
                <div class="seat-reserved" style="background: gray;">
                    {{ strtoupper($sheet->row) }}-{{ $sheet->column }}<br>
                    <small>予約済み</small>
                </div>
                @else
                {{-- 空席の場合：リンクを表示 --}}
                <a href="{{ route('movies.schedules.reservations.create', [
                    $movie->id, 
                    $schedule->id,
                    'date' => now()->format('Y-m-d'),
                    'sheetId' => $sheet->id
                ]) }}" class="seat-available">
                    {{ strtoupper($sheet->row) }}-{{ $sheet->column }}
                </a>
                @endif
            </td>
            @endforeach
            @endif
        </tr>
        @endforeach
    </table>
</body>

</html>
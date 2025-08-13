<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>座席配置</title>
</head>

<body>
    <h1>座席配置</h1>

    <div>..スクリーン..</div>

    <table border="1">
        @php
        // データを行ごとにグループ化
        $groupedSheets = $sheets->groupBy('row');
        @endphp

        @foreach(['a', 'b', 'c'] as $row)
        <tr>
            @if(isset($groupedSheets[$row]))
            @foreach($groupedSheets[$row]->sortBy('column') as $sheet)
            <td>{{ $sheet->row }}-{{ $sheet->column }}</td>
            @endforeach
            @endif
        </tr>
        @endforeach
    </table>
</body>

</html>
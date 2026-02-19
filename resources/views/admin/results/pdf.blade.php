<!DOCTYPE html>
<html>
<head>
    <title>Election Results</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>

<h2>{{ $election->title }} - Results</h2>

<table>
    <thead>
        <tr>
            <th>Position</th>
            <th>Rank</th>
            <th>Candidate</th>
            <th>Total Votes</th>
        </tr>
    </thead>
    <tbody>
        @foreach($resultsByPosition as $block)
            @foreach(($block['results'] ?? []) as $rank => $row)
                <tr>
                    <td>{{ $block['position']->name ?? 'Unknown' }}</td>
                    <td>{{ $rank + 1 }}</td>
                    <td>{{ $row['candidate']['name'] ?? 'Unknown' }}</td>
                    <td>{{ $row['votes'] ?? 0 }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>

</body>
</html>

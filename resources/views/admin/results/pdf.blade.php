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

<h2>{{ $election->title }} — Results</h2>

<table>
    <thead>
        <tr>
            <th>Candidate</th>
            <th>Position</th>
            <th>Total Votes</th>
        </tr>
    </thead>
    <tbody>
        @foreach($results as $row)
            <tr>
                <td>{{ $row->candidate->name }}</td>
                <td>{{ $row->candidate->position->name }}</td>
                <td>{{ $row->total_votes }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>

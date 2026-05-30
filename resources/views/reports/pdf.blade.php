<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #444; padding: 6px; }
        th { background: #eee; }
    </style>
</head>
<body>
@use(App\Enums\RespondentType)
<h2>Survey Report</h2>

<table>
    <thead>
        <tr>
            <th>Service</th>
            <th>Client Type</th>
            <th>Region</th>
            <th>Date Created</th>
        </tr>
    </thead>

    <tbody>
        @foreach($respondents as $r)
        <tr>
            <td>{{ $r->service }}</td>
            <td>{{ RespondentType::getDescription($r->type) }}</td>
            <td>{{ $r->region->name }}</td>
            <td>{{ $r->created_at->format('Y-m-d') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>

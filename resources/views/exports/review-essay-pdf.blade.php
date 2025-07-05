<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Review Essay Export</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px 8px; }
        th { background: #f2f2f2; }
        h2 { margin-bottom: 0; }
        .meta { margin-bottom: 10px; color: #555; }
    </style>
</head>
<body>
    <h2>Review Essay Answers - {{ $exercise->title }}</h2>
    <div class="meta">Classroom: {{ $classroom->name }}</div>
    <table>
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Essay Question</th>
                <th>Answer</th>
                <th>Score</th>
                <th>Comment</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                <tr>
                    <td>{{ $row['student'] }}</td>
                    <td>{{ $row['question'] }}</td>
                    <td>{{ $row['answer'] }}</td>
                    <td>{{ $row['score'] }}</td>
                    <td>{{ $row['comment'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 
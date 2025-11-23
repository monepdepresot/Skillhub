<!DOCTYPE html>
<html>
<head>
    <title>ID Card</title>
    <style>
        /* Page Settings */
        body {
            font-family: sans-serif;
            background-color: #fff;
        }

        /* The ID Card Container */
        .card {
            width: 80%;
            margin: 0 auto;
            border: 2px solid #333;
            padding: 20px;
            border-radius: 10px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .header h1 { margin: 0; color: #2c3e50; }
        .header p { margin: 5px 0 0; color: #7f8c8d; }

        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 5px; font-size: 14px; }
        .label { font-weight: bold; width: 100px; }

        .courses-section {
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .badge {
            display: inline-block;
            background-color: #e2e6ea;
            border: 1px solid #ccc;
            padding: 5px 10px;
            border-radius: 15px;
            margin: 5px;
            font-size: 12px;
        }
    </style>
</head>
<body>

    <div class="card">
        <div class="header">
            <h1>SKILLHUB PARTICIPANT</h1>
            <p>Official Registration Card</p>
        </div>

        <table class="info-table">
            <tr>
                <td class="label">Name:</td>
                <td>{{ $participant->name }}</td>
            </tr>
            <tr>
                <td class="label">Email:</td>
                <td>{{ $participant->email }}</td>
            </tr>
            <tr>
                <td class="label">Phone:</td>
                <td>{{ $participant->phone }}</td>
            </tr>
            <tr>
                <td class="label">Member Since:</td>
                <td>{{ $participant->created_at->format('d F Y') }}</td>
            </tr>
        </table>

        <div class="courses-section">
            <strong>Enrolled Courses:</strong><br><br>

            @forelse($participant->courses as $course)
                <span class="badge">
                    {{ $course->name }}
                    @if($course->instructor)
                        <span style="color: #666;"> — {{ $course->instructor }}</span>
                    @endif
                </span>
                @empty
                    <span style="color: #999;">No active courses.</span>
            @endforelse
        </div>
    </div>

</body>
</html>

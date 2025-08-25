<!DOCTYPE html>
<html>

<head>
    <title>Resume Parser</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #eef2f7;
        }

        .card {
            border-radius: 1rem;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .highlight {
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 10px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="card p-4">
            <h2 class="text-center mb-4">📄 Resume Parser with AI</h2>
            <form method="POST" action="/resume-parser/upload" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Upload Resumes (Multiple PDFs)</label>
                    <input type="file" name="resume[]" class="form-control" required multiple>
                </div>

                <div class="mb-3">
                    <label class="form-label">Your Requirements (e.g. Laravel, CGPA above 3)</label>
                    <textarea name="requirements" class="form-control" rows="4" placeholder="Mention your needs..."></textarea>
                </div>

                <button class="btn btn-success w-100">Upload & Analyze</button>
            </form>


            @if (isset($results))
                <hr>
                <h3 class="mb-3 text-primary">📋 AI Evaluation for All Resumes</h4>
                @foreach ($results as $item)
                    <div class="card p-3 mb-3">
                        <h6><strong>File:</strong> {{ $item['file'] }}</h6>
                        <pre style="white-space: pre-wrap;">{{ $item['response'] }}</pre>
                    </div>
                @endforeach
            @endif

            {{--
            @isset($aiReply)
                <hr>
                <h5>🤖 AI Evaluation</h5>
                <div class="highlight">{!! nl2br(e($aiReply)) !!}</div>
                <hr> --}}
            {{-- <h6 class="text-muted">📜 Raw Resume Text</h6> --}}
            {{-- <pre class="bg-light p-3" style="white-space: pre-wrap;">{{ $text }}</pre> --}}
            {{-- @endisset --}}
        </div>
    </div>
</body>

</html>

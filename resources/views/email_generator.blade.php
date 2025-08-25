<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AI Email Generator</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">AI Email Generator</h3>
            </div>

            <div class="card-body">

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('generateEmail') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="letter_type" class="form-label">Select Letter Type</label>
                        <select name="letter_type" id="letter_type" class="form-select" required>
                            <option value="">-- Select Type --</option>
                            <option value="Leave Application">Leave Application</option>
                            <option value="Appointment Letter">Appointment Letter</option>
                            <option value="Resignation Letter">Resignation Letter</option>
                            <option value="Promotion Letter">Promotion Letter</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="details" class="form-label">Enter Required Details</label>
                        <textarea name="details" id="details" rows="4" class="form-control" placeholder="e.g., I need a 3-day leave due to personal reasons..." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Generate Email</button>
                </form>

                @if(isset($generatedEmail))
                    <hr>
                    <h5>Generated Email:</h5>
                    <div class="p-3 bg-light border rounded" style="white-space: pre-wrap;">
                        {{ $generatedEmail }}
                    </div>
                @endif

            </div>
        </div>
    </div>

</body>
</html>

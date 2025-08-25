<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>AI Image Recognizer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f3f4f6;
        }

        .container {
            margin-top: 50px;
            max-width: 600px;
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.08);
        }
    </style>
</head>

<body>

    <div class="container">
        <h2 class="mb-4 text-center">🔍 AI Image Recognizer</h2>



        <form action="{{ route('imageUploader') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="image" class="form-label">Select an image</label>
                <input class="form-control" type="file" name="images[]" multiple accept="image/*" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Analyze Image</button>
        </form>

        <div class="response">
            @if (isset($matchingImages))
                <div class="mt-4">
                    <h4>Images with Helmet:</h4>
                    @if (count($matchingImages) > 0)
                        @foreach ($matchingImages as $img)
                            <div class="mb-3">
                                <img src="{{ $img }}" width="200" />
                            </div>
                        @endforeach
                    @else
                        <p>No helmet detected in any image.</p>
                    @endif
                </div>
            @endif
        </div>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <scipt src="https://code.jquery.com/jquery-3.6.0.min.js"></scipt>

    <script></script>



</body>

</html>

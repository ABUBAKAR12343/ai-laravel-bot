<!DOCTYPE html>
<html>
<head>
    <title>Rozee Crawler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3>Rozee job Crawler</h3>
    <form action="/crawler" method="POST">
        @csrf
        <div class="mb-3">
            <input type="text" name="job_title" class="form-control" placeholder="Enter Job Title" required>
        </div>

           <div class="mb-3">
            <input type="text" name="prompt" class="form-control" placeholder="Enter Prompt" required>
        </div>
        <button class="btn btn-primary">Crawl Rozee job</button>
    </form>

    <div class="col-8" id="crawledData">

    </div>
    @if(session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif
</div>





<script>
document.getElementById("crawlForm").addEventListener("submit", function(e) {
    e.preventDefault();

    let form = e.target;
    let formData = new FormData(form);

    fetch("/crawler", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById("crawledData").innerHTML =
            `<div class="alert alert-info"><strong>AI Reply:</strong><br>${data.reply}</div>`;
    })
    .catch(error => {
        console.error("Error:", error);
        document.getElementById("crawledData").innerHTML =
            `<div class="alert alert-danger">Something went wrong.</div>`;
    });
});
</script>


</body>

</html>


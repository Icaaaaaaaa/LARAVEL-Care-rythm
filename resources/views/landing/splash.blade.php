<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Care Rythm - Splash</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; }
        .splash-logo { max-height: 180px; }
        .progress {
            height: 8px;
            margin-top: 32px;
        }
        .progress-bar {
            transition: width 2s linear;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var bar = document.getElementById('splash-progress');
            setTimeout(function() {
                window.location.href = "{{ url('/login') }}";
            }, 2000);
            setTimeout(function() {
                bar.style.width = "100%";
            }, 50);
        });
    </script>
</head>
<body class="bg-light d-flex align-items-center justify-content-center">
    <div class="text-center w-100">
        <img src="{{ asset('image/logo.jpg') }}" class="splash-logo mb-4" alt="Logo">
        <h1 class="display-6 mb-2">Care Rythm</h1>
        <p class="lead text-secondary">Membantu aktivitas harian Anda</p>
        <div class="progress mx-auto" style="width:200px;">
            <div id="splash-progress" class="progress-bar bg-primary" style="width:0%"></div>
        </div>
    </div>
</body>
</html>

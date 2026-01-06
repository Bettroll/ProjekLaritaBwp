<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Larita Bakery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1509440159596-0249088772ff?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
        }
        .auth-card { background: #fff; border-radius: 8px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.3); }
        .btn-larita { background-color: #8B4513; color: white; }
        .btn-larita:hover { background-color: #5D2E0A; color: white; }
        .pesan-error { color: #b02a37; background-color: #f8d7da; padding: 10px; border-radius: 4px; margin-bottom: 15px; border-left: 5px solid #b02a37; }
        .pesan-sukses { color: #0f5132; background-color: #d1e7dd; padding: 10px; border-radius: 4px; margin-bottom: 15px; border-left: 5px solid #0f5132; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="text-center mb-4">
                    <h2 class="text-white fw-bold">LARITA <span class="text-warning">BAKERY</span></h2>
                </div>
                <!-- Tempat isi konten -->
                @yield('konten_utama')
            </div>
        </div>
    </div>
</body>
</html>
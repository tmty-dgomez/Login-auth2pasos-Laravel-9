<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body style="background-color: #f4f6f9;">

    @include('navbar')

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card shadow-lg rounded">
                    <div class="card-body text-center">
                        <h1 class="display-4 text-primary">¡Hola, Mundo!</h1>
                        <p class="lead text-secondary">Bienvenido a tu panel de control. ¡Estás dentro!</p>

                        @if (session('success'))
                            <script>
                                Swal.fire({
                                    title: "¡Éxito!",
                                    text: "{{ session('success') }}",
                                    icon: "success",
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                            </script>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

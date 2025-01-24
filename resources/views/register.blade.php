<!doctype html>
<html lang="en">
<head>
  <title>Register</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
  <link href="{{ asset('register.css') }}" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="d-flex align-items-center">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6 col-md-8 login-box">
        <div class="col-lg-12 login-title">
          Sign Up
        </div>
        <div class="col-lg-12 login-form">
          <form method="POST" action="{{ route('register.post') }}">
            @csrf
            <div class="form-group">
              <label class="form-control-label">Name</label>
              <input type="text" name="name" class="form-control" >
            </div>
            <div class="form-group">
              <label class="form-control-label">Email</label>
              <input type="text" name="email" class="form-control" >
            </div>
            <div class="form-group">
              <label class="form-control-label">Phone number</label>
              <input type="text" name="phone" class="form-control" >
            </div>
            <div class="form-group">
              <label class="form-control-label">Password</label>
              <input type="password" name="password" class="form-control">
            </div>
            <div class="col-12 login-btm login-button d-flex justify-content-center">
              <button type="submit" class="btn btn-outline-primary">Register</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous"></script>
@if (session('success'))
    <script>
        Swal.fire({
            title: "¡Bienvenido!",
            text: "{{ session('success') }}",
            icon: "success",
            draggable: true,
            timer: 3000,
            showConfirmButton: false
        });
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "{{ session('error') }}",
        });
    </script>
@endif

@if ($errors->any())
    <script>
        var errorMessages = "{{ implode(', ', $errors->all()) }}";
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: errorMessages,
        });
    </script>
@endif

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/js/main.js"></script>
</body>
</html>

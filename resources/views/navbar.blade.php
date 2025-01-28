<nav class="navbar navbar-expand-lg navbar-light" style="background-image: linear-gradient(160deg, #071014 0%, #0db8de 100%);">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">/<a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
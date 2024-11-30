<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Default</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
  </head>
  <body style="border-radius:50px">
    <nav class="navbar navbar-expand-lg" style="height:100px; padding:20px; background-color: white; box-shadow: none;">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" width="90" height="84" class="d-inline-block align-text-top">
              </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
              <li class="nav-item dropdown">
                <button class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Product
                  </button>
                  <ul class="dropdown-menu dropdown-menu">
                    <li><a class="dropdown-item" href="#">Product 1</a></li>
                    <li><a class="dropdown-item" href="#">Product 2</a></li>
                    <li><a class="dropdown-item" href="#">Product 3</a></li>
                    <li><a class="dropdown-item" href="#">Product 4</a></li>
                  </ul>
              </li>
              <li class="nav-item dropdown">
                <button class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Company
                  </button>
                  <ul class="dropdown-menu dropdown-menu">
                    <li><a class="dropdown-item" href="#">Tentang Kami</a></li>
                    <li><a class="dropdown-item" href="#">Struktur</a></li>
                    <li><a class="dropdown-item" href="#">Life at GCS</a></li>
                  </ul>
              </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/dashboard') }}">Dashboard</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Register</a>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link">|</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                @endauth
            </ul>
            

            
          </div>
        </div>
    </nav>



    <div class="rounded-container; background-color:white" >
        <div class="row">
          <div class="col-3">
            <h1>Welcome to Ganesh Custom Shop Inventory System!</h1>
            <p>Streamline your work with Ganesh Custom Shop’s custom-designed apparel management tools. Founded in 2013 and located in Bali, we specialize in creating original clothing designs and offering custom printing services for our customers.</p>
          </div>
          <div class="col-7">
            Column
          </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
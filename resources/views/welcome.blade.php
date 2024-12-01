<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Default</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
      html, body {
        height: 100%;
        margin: 0;
      }

      body {
        display: flex;
        flex-direction: column;
      }

      main {
        flex: 1;
      }

      footer {
        background-color: #007748;
        color: white;
        text-align: center;
        padding: 10px;
        margin-top: auto; 
      }
    </style>
  </head>
  <body style="border-radius:50px">
    <!-- Navbar -->
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
              <ul class="dropdown-menu">
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
              <ul class="dropdown-menu">
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

    <!-- Main Content -->
    <main>
      <div class="rounded-container" style="background-color: #132A84; border-top-left-radius: 80px; border-top-right-radius:80px;">
        <div class="row">
          <div class="col-4" style="margin-top: 15%; margin-left:100px">
            <h1 style="color:white">Welcome to Ganesh Custom Shop Inventory System!</h1>
            <p style="color:white; margin-top: 10%">Streamline your work with Ganesh Custom Shop’s custom-designed apparel management tools. Founded in 2013 and located in Bali, we specialize in creating original clothing designs and offering custom printing services for our customers.</p>
          </div>
          <div class="col-6 d-flex flex-column justify-content-start" style="margin-top: 15%;">
            <div class="container my-4">
              <div class="row row-cols-1 row-cols-md-3 g-4">
                <!-- Card 1 -->
                <div class="col d-flex align-items-start">
                  <div class="card" style="border-radius: 20px; overflow: hidden;">
                    <img src="{{ asset('images/p1.png') }}" style="height: 300px; width: 100%;" class="img-fluid" alt="Nature Image">
                  </div>
                </div>
            
                <!-- Card 2 -->
                <div class="col d-flex align-items-start justify-content-end" style="margin-top: 50px;">
                  <div class="card" style="border-radius: 20px; overflow: hidden;">
                    <img src="{{ asset('images/p2.png') }}" style="height: 300px; width: 100%;" class="img-fluid" alt="Nature Image">
                  </div>
                </div>
            
                <!-- Card 3 -->
                <div class="col d-flex align-items-start">
                  <div class="card" style="border-radius: 20px; overflow: hidden;">
                    <img src="{{ asset('images/p3.jpg') }}" style="height: 300px; width: 100%;" class="img-fluid" alt="Nature Image">
                  </div>
                </div>
              </div>
            </div>
            
            
          </div>
        </div>
      </div>
    </main>

    <!-- Footer -->
    <footer>
      <div class="justify" style="background-color:#007748; color: white; text-align: center; padding: 10px;">
        <p>© 2024 INDUSYNC</p>
      </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>

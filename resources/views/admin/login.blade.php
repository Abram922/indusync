<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
</head>
<body>

    <h2>Login</h2>
    <form action="" method="POST">
        @csrf
        <input type="text" placeholder="Email"><br>
        <input type="text" placeholder="password">
        <button type="submit">Login</button>
    </form>
    
</body>
</html>
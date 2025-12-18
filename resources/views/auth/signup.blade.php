<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>

<body>
    <h2>Sign Up</h2>
    <form method="POST" action="{{ route('signup') }}">
        @csrf
        <div>
            <label for="name">Full Name</label><br>
            <input type="text" name="name" id="name" required>
        </div>
        <br>
        <div>
            <label for="email">Email</label><br>
            <input type="email" name="email" id="email" required>
        </div>
        <br>
        <div>
            <label for="phone">Phone Number</label><br>
            <input type="text" name="phone" id="phone" required>
        </div>
        <br>
        <div>
            <label for="password">Password</label><br>
            <input type="password" name="password" id="password" required>
        </div>
        <br>
        <div>
            <label for="password_confirmation">Confirm Password</label><br>
            <input type="password" name="password_confirmation" id="password_confirmation" required>
        </div>
        <br>
        <button type="submit">Register</button>
    </form>
    <p>
        Already have an account?
        <a href="{{ route('login') }}">Login</a>
    </p>
</body>
</html>

<!DOCTYPE html>
<html>
<body>

<form action="{{ route('auth.register') }}" method="post">
    Email:<br>
    <input type="text" name="email" value="admin@interactivesolutions.lt">
    <br>
    Password:<br>
    <input type="text" name="password" value="labas">
    <br><br>
    {{ csrf_field() }}
    <input type="submit" value="Submit">
</form>

</body>
</html>

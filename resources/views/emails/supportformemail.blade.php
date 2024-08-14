<! DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Support Form</title>
</head>
<body>
<h1>New Support Mail</h1>
<div>
    Email from: {{ $useremail }}
</div>
<br/>
<div>
    Message: <br/>
    {{ $details }} <br />
</div>
<h4> {{ $username }} </h4>
</body>
</html>

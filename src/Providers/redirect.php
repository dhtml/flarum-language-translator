<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Africoders Redirecting...</title>
    <script type="text/javascript">
        // JavaScript function to redirect after 10 seconds
        function redirect() {
            setTimeout(function() {
                window.location.href = "<?php echo $redirectUrl; ?>";
            }, 20000); // 10000 milliseconds = 20 seconds
        }
    </script>
</head>
<body onload="redirect()">
<h1>Redirecting in 20 seconds...</h1>
<p>If you are not redirected automatically, follow this <a href="<?php echo $redirectUrl; ?>">link</a>.</p>
</body>
</html>

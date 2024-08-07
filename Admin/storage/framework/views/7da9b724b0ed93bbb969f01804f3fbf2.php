<!DOCTYPE html>
<html>
<head>
    <title>Sales Registration</title>
</head>
<body>
  
    <div style="text-align: center; margin-bottom: 20px;">
        <img src="<?php echo e(asset('build/images/logo-dark.png')); ?>" alt="Company Logo" style="width: 100px; height: auto;">
    </div>

    <p>Ciao <?php echo e($name); ?>,</p>
    <p>Sei registrato come nuovo venditore nell'app 1% Contract.</p>
    <p>Le tue credenziali di accesso sono le seguenti:</p>
    <p>Email: <?php echo e($email); ?></p>
    <p>Password: <?php echo e($password); ?></p>
    <p>Verifica la tua email cliccando <a href="https://sales.appcontratti.it/login">qui</a>.</p>
</body>
</html>
<?php /**PATH /home/u121027207/domains/appcontratti.it/public_html/resources/views/emails/sales_details.blade.php ENDPATH**/ ?>
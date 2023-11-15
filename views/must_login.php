<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container-fluid">
        <div class="row justify-content-center mt-5">
            <div class="card col-6">
                <div class="card-body text-center">
                    <h4 class="card-title text-center">Please login to continue</h4>
                    <p>Login With</p>
                    <div class="row">
                        <div class="col-sm-4 col-sm-12">
                            <a href="<?= $auth_url ?>" class="m-auto btn-md btn btn-primary">Google Account</a>
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
</body>

</html>
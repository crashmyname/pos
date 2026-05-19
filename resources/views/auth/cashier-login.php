<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">

    <title>Cashier Login</title>

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <link href="<?= asset('tabler/dist/css/tabler.min.css') ?>"
          rel="stylesheet">

    <style>

        body{
            background:#f4f7fb;
        }

        .login-wrapper{
            min-height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
        }

        .login-card{
            width:100%;
            max-width:420px;
            border:0;
            border-radius:14px;
            box-shadow:0 15px 40px rgba(0,0,0,.08);
        }

        .login-logo{
            font-size:32px;
            font-weight:700;
            color:#206bc4;
        }

    </style>

</head>

<body>

<div class="login-wrapper">

    <div class="card login-card">

        <div class="card-body p-5">

            <div class="text-center mb-4">

                <div class="login-logo">
                    SMART POS
                </div>

                <div class="text-secondary mt-1">

                    Cashier Login

                </div>

            </div>

            <form action="<?= url('cashier/login/process') ?>"
                  method="post">

                <?= csrf() ?>

                <!-- USERNAME -->
                <div class="mb-3">

                    <label class="form-label">

                        Username

                    </label>

                    <input type="text"
                           name="username"
                           class="form-control"
                           required>

                </div>

                <!-- PASSWORD -->
                <div class="mb-4">

                    <label class="form-label">

                        Password

                    </label>

                    <input type="password"
                           name="password"
                           class="form-control"
                           required>

                </div>

                <!-- BUTTON -->
                <button class="btn btn-primary w-100 btn-lg">

                    LOGIN CASHIER

                </button>

            </form>

        </div>

    </div>

</div>

</body>
</html>
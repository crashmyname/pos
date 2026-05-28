<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>SMART POS</title>

    <meta name="csrf-token"
          content="<?= csrfHeader() ?>">

    <link href="<?= asset('tabler/dist/css/tabler.min.css') ?>"
          rel="stylesheet"/>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <style>

        body{
            background:#f4f7fb;
            overflow:hidden;
        }

        .cashier-navbar{

            height:68px;
            background:#ffffff;

            border-bottom:1px solid #e9ecef;

            display:flex;
            align-items:center;
            justify-content:space-between;

            padding:0 20px;

        }

        .cashier-menu{

            display:flex;
            align-items:center;
            gap:10px;

        }

        .cashier-menu a{

            text-decoration:none;

            padding:10px 16px;

            border-radius:10px;

            color:#495057;

            font-weight:600;

            transition:.2s;

        }

        .cashier-menu a:hover{

            background:#edf4ff;
            color:#206bc4;

        }

        .cashier-menu a.active{

            background:#206bc4;
            color:#fff;

        }

        .cashier-content{

            height:calc(100vh - 68px);
            overflow:auto;

        }

        .shift-badge{

            background:#e8fff1;
            color:#2fb344;

            padding:8px 14px;

            border-radius:10px;

            font-weight:bold;

        }
        .menu-disabled{
            pointer-events:none;
            opacity:.5;
            cursor:not-allowed;
        }

    </style>

</head>

<body>

    <!-- NAVBAR -->
    <div class="cashier-navbar">

        <!-- LEFT -->
        <div class="d-flex align-items-center gap-4">

            <div class="fw-bold fs-2 text-primary">

                SMART POS

            </div>

            <!-- MENU -->
            <div class="cashier-menu">

                <a href="<?= url('cashier') ?>"
                   class="<?= $title == 'Cashier' ? 'active' : ''?>">

                    POS

                </a>

                <a href="#"
                data-bs-toggle="<?= $title == 'Daily Report' ? '' : 'modal' ?>"
                data-bs-target="<?= $title == 'Daily Report' ? '' : '#holdModal' ?>"
                class="<?= $title == 'Daily Report' ? 'menu-disabled' : '' ?>">

                    Hold Bill

                </a>

                <a href="#" data-bs-toggle="<?= $title == 'Daily Report' ? '' : 'modal'?>"
                    data-bs-target="<?= $title == 'Daily Report' ? '' : '#transactionModal'?>"
                    class="<?= $title == 'Daily Report' ? 'menu-disabled' : '' ?>">

                    Transaction

                </a>

                <a href="#"
                data-bs-toggle="<?= $title == 'Daily Report' ? '' : 'modal'?>"
                data-bs-target="<?= $title == 'Daily Report' ? '' : '#returnListModal'?>"
                class="<?= $title == 'Daily Report' ? 'menu-disabled' : '' ?>">

                    Return

                </a>

                <a href="<?= route('view.report') ?>" class="<?= $title == 'Daily Report' ? 'active' : ''?>">

                    Daily Report

                </a>

            </div>

        </div>

        <!-- RIGHT -->
        <div class="d-flex align-items-center gap-3">

            <div class="shift-badge">

                ACTIVE

            </div>

            <div class="text-end">

                <div class="fw-bold">
                    <?= auth()->user()->name?>
                </div>

                <div class="small text-secondary">
                    <?= auth()->user()->role?>
                </div>

            </div>

            <form action="<?= route('logout') ?>"
                  method="post">

                <?= csrf() ?>

                <button class="btn btn-danger">

                    Logout

                </button>

            </form>

        </div>

    </div>

    <!-- CONTENT -->
    <div class="cashier-content">

        <?= $content ?>

    </div>

    <script src="<?= asset('tabler/dist/js/tabler.min.js')?>"></script>

</body>

</html>
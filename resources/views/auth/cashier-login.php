<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">

    <title>Cashier Login</title>

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <link href="<?= asset('tabler/dist/css/tabler.min.css') ?>"
          rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

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

            <form action=""
                  method="post" id="formlogin">

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
                <button class="btn btn-primary w-100 btn-lg" id="btnlogin">

                    LOGIN CASHIER

                </button>

            </form>

        </div>

    </div>

</div>
<!-- Libs JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function(){
          $('#btnlogin').on('click', function(e){
            e.preventDefault();
            const btn = $(this)
            const loading = $('#loading')
            btn.hide()
            loading.show()
              var url = '<?= route('login.cashier')?>';
              var formdata = new FormData($('#formlogin')[0]);
              $.ajax({
                  type: 'POST',
                  url: url,
                  data:formdata,
                  headers: {
                    'X-CSRF-TOKEN' : '<?= csrfHeader()?>'
                  },
                  processData:false,
                  contentType:false,
                  dataType: 'json',
                  success:function(response){
                      if (response.statusCode === 200) {
                          btn.show()
                          loading.hide()
                          let timerInterval;
                          Swal.fire({
                              icon: 'success',
                              title: "Login Berhasil",
                              timer: 2000,
                              timerProgressBar: true,
                              didOpen: () => {
                                  Swal.showLoading();
                                  const timer = Swal.getPopup().querySelector("b");
                                  timerInterval = setInterval(() => {
                                  timer.textContent = `${Swal.getTimerLeft()}`;
                                  }, 100);
                              },
                              willClose: () => {
                                  clearInterval(timerInterval);
                              }
                          }).then((result) => {
                              window.location.href = "<?= url('') ?>";
                          });
                      } else {
                          btn.show()
                          loading.hide()
                          Swal.fire({
                              icon: 'error',
                              title: 'Login Gagal',
                              text: response.message
                          })
                      }
                  },
                  error: function (xhr, status, error) {
                    let response = JSON.parse(xhr.responseText)
                      btn.show()
                      loading.hide()
                      Swal.fire({
                          icon: 'error',
                          title: 'Oops...',
                          text: response.message
                      })
                  }
              })
          })
      });
</script>
</body>
</html>
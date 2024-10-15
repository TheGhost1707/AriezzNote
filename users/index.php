<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AriezzNote</title>
    <!-- Bootstrap 5 CSS -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/my_style.css">
    <link rel="icon" href="../assets/images/aries.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMB0m7iIbq2E5Dg7F2H/oMx5NHX9W8IefxHc5F" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #90f0dd, #2193b0);
        }

        .container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            width: 100%;
            max-width: 550px;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .app-logo {
            width: 200px;
        }

        .form-control {
            border-radius: 50px;
            padding: 12px 20px;
            font-size: 16px;
            border: 1px solid #ddd;
        }

        .btn-success {
            background-color: #3fa35f;
            margin-bottom: 5px;
            border: none;
            border-radius: 50px;
            padding: 12px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .btn-success:hover {
            background-color: aquamarine;
        }

        .btn-primary {
            background-color: #2193b0;
            border: none;
            border-radius: 50px;
            padding: 12px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #6dd5ed;
        }

        @media (max-width: 576px) {
            .login-box {
                padding: 20px;
            }

            .form-control {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <?php
    session_start();

    // Check if there's a notification message set
    if (isset($_SESSION['notification'])) {
        echo "<script>
            alert('" . $_SESSION['notification'] . "');
          </script>";
        // Clear the notification after displaying it
        unset($_SESSION['notification']);
    }
    // Tampilkan pesan error jika ada
    if (isset($_SESSION['error'])) {
        echo "<script>alert('" . $_SESSION['error'] . "');</script>";
        // Hapus pesan error setelah ditampilkan agar tidak muncul lagi setelah halaman di-refresh
        unset($_SESSION['error']);
    }
    ?>
    <div class="container">
        <div class="login-box">
            <img src="../assets/images/book.png" alt="Logo" class="app-logo">
            <h1 style="margin:20px;">AriezzNote</h1>

            <!-- Login form -->
            <form action="proses_login.php" method="POST">
                <div class="mb-4">
                    <label for="email" class="visually-hidden">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email address" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="visually-hidden">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Login</button>

                <!-- Tampilkan error jika ada -->
                <?php if (!empty($error)) : ?>
                    <div class="alert alert-danger mt-3"><?php echo $error; ?></div>
                <?php endif; ?>
            </form>
            <button class="btn btn-primary w-100" onclick="window.location.href='../index.php'">Back Home</button>
            <p style="margin-top: 20px;">belum punya akun? <a href="#" data-bs-toggle="modal" data-bs-target="#registerModal">Daftar disini</a></p>
        </div>
    </div>

    <!-- Modal Pendaftaran -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">Daftar Akun Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="registerForm" method="POST" action="proses_register.php">
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Nama Lengkap" required>
                        </div>
                        <div class="mb-3">
                            <label for="email_register" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email_register" name="email" placeholder="Email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password_register" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password_register" name="password" placeholder="Password" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="" disabled selected>Pilih Role</option>
                                <option value="pembaca">Pembaca</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Daftar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
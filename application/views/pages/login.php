<!DOCTYPE html>
<html lang="en-US" dir="ltr" data-navigation-type="default" data-navbar-horizontal-shape="default">


<!-- Mirrored from prium.github.io/phoenix/v1.20.1/pages/authentication/card/sign-in.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 04 Jan 2025 13:21:48 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <base href="<?= base_url() ?>">

    <title>Sporglo Admin</title>

    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->

    <meta name="theme-color" content="#ffffff">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&amp;display=swap"
        rel="stylesheet">
 
    <link href="assets/css/theme-rtl.min.css" type="text/css" rel="stylesheet" id="style-rtl">
    <link href="assets/css/theme.min.css" type="text/css" rel="stylesheet" id="style-default">
    <link href="assets/css/user-rtl.min.css" type="text/css" rel="stylesheet" id="user-style-rtl">
    <link href="assets/css/user.min.css" type="text/css" rel="stylesheet" id="user-style-default">

</head>

<body>
    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">
        <div class="container-fluid bg-body-tertiary dark__bg-gray-1200">
            <div class="bg-holder bg-auth-card-overlay" style="background-image:url(assets/img/bg/37.png);">
            </div>
            <!--/.bg-holder-->
            <div class="row flex-center position-relative min-vh-100 g-0 py-5">
                <div class="col-11 col-sm-10 col-xl-8">
                    <div class="card border border-translucent auth-card">
                        <div class="card-body pe-md-0">
                            <div class="row align-items-center gx-0 gy-7">
                                <div
                                    class="col-auto bg-body-highlight dark__bg-gray-1100 rounded-3 position-relative overflow-hidden auth-title-box">
                                    <div class="bg-holder" style="background-image:url(assets/img/bg/38.png);">
                                    </div>
                                    <!--/.bg-holder-->
                                    <div
                                        class="position-relative px-4 px-lg-7 pt-7 pb-7 pb-sm-5 text-center text-md-start pb-lg-7 pb-md-7">
                                        <h3 class="mb-3 text-body-emphasis fs-7">Admin Login</h3>
                                        <p class="text-body-tertiary">Manage your sports effortlessly with Sporglo's
                                            robust admin features!</p>
                                        <ul class="list-unstyled mb-0 w-max-content w-md-auto">
                                            <li class="d-flex align-items-center"><span         
                                                    class="uil uil-check-circle text-success me-2"></span><span
                                                    class="text-body-tertiary fw-semibold">Secure</span></li>
                                            <li class="d-flex align-items-center"><span
                                                    class="uil uil-check-circle text-success me-2"></span><span
                                                    class="text-body-tertiary fw-semibold">User-Friendly</span></li>
                                            <li class="d-flex align-items-center"><span
                                                    class="uil uil-check-circle text-success me-2"></span><span
                                                    class="text-body-tertiary fw-semibold">Reliable</span></li>
                                        </ul>
                                    </div>

                                    <div class="position-relative z-n1 mb-6 d-none d-md-block text-center mt-md-15"><img
                                            class="auth-title-box-img d-dark-none"
                                            src="assets/img/spot-illustrations/auth.png" alt="" /><img
                                            class="auth-title-box-img d-light-none"
                                            src="assets/img/spot-illustrations/auth-dark.png" alt="" /></div>
                                </div>
                                <div class="col mx-auto">
                                    <div class="auth-form-box">
                                        <div class="text-center mb-7">
                                            <div
                                                class="d-flex flex-center align-items-center fw-bolder fs-3 d-inline-block">
                                                <img src="assets/img/logo/logo-task.png" alt="sporglo" width="58" />
                                            </div>
                                            <h3 class="text-body-highlight">Sign In</h3>
                                            <form onsubmit="validate(event)" method="POST" id="login-form" enctype="multipart/form-data">
                                                <div class="mb-3 text-start"><label class="form-label" for="email">Email
                                                        address</label>
                                                    <div class="form-icon-container">
                                                        <input class="form-control form-icon-input" id="email"
                                                            name="email" type="email" />
                                                    </div>
                                                </div>
                                                <div class="mb-3 text-start">
                                                    <label class="form-label" for="password">Password</label>
                                                    <div class="form-icon-container">
                                                        <input class="form-control form-icon-input pe-6" id="password"
                                                            type="password" name="password" />


                                                    </div>
                                                </div>
                                                <div class="row flex-between-center mb-7">
                                                    <div class="col-auto">
                                                        <div class="form-check mb-0"><input class="form-check-input"
                                                                id="basic-checkbox" type="checkbox"
                                                                checked="checked" /><label class="form-check-label mb-0"
                                                                for="basic-checkbox">Rememberme</label>
                                                        </div>
                                                    </div>

                                                </div>
                                                <button type="submit" class="btn btn-primary w-100 mb-3"
                                                    id="login-btn">Sign In</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    </main><!-- ===============================================-->
    <!--    End of Main Content-->
    <!-- ===============================================-->



    <!-- ===============================================-->
    <!--    JavaScripts-->
    <!-- ===============================================-->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="assets/js/pages/common.js"></script>
    <script src="assets/js/pages/helper.js"></script>
    <script src="assets/js/pages/pagination.js"></script>
    <script src="assets/js/pages/auth/login.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
</body>


<!-- Mirrored from prium.github.io/phoenix/v1.20.1/pages/authentication/card/sign-in.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 04 Jan 2025 13:21:49 GMT -->

</html>
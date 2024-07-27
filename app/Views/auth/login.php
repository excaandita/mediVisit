<!doctype html>
<!--
* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
* @version 1.0.0-beta20
* @link https://tabler.io
* Copyright 2018-2023 The Tabler Authors
* Copyright 2018-2023 codecalm.net Paweł Kuna
* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
-->
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
        <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
        <title>Sign in with illustration - Tabler - Premium and Open Source dashboard template with responsive and high quality UI.</title>
        <!-- CSS files -->
        <link href="<?= base_url('template/dist/css/tabler.min.css?1692870487')?>" rel="stylesheet"/>
        <link href="<?= base_url('template/dist/css/tabler-flags.min.css?1692870487')?>" rel="stylesheet"/>
        <link href="<?= base_url('template/dist/css/tabler-payments.min.css?1692870487')?>" rel="stylesheet"/>
        <link href="<?= base_url('template/dist/css/tabler-vendors.min.css?1692870487')?>" rel="stylesheet"/>
        <link href="<?= base_url('template/dist/css/demo.min.css?1692870487')?>" rel="stylesheet"/>
        <style>
        @import url('https://rsms.me/inter/inter.css');
        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }
        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }
        </style>
    </head>
    <body  class=" d-flex flex-column">
        <script src="<?= base_url('template/dist/js/demo-theme.min.js?1692870487')?>"></script>
        <div class="page page-center">
            <div class="container container-normal py-4">
                <div class="row align-items-center g-4">
                    <div class="col-lg">
                        <div class="container-tight">
                            <div class="text-center mb-4">
                                <a href="." class="navbar-brand navbar-brand-autodark"><img src="<?= base_url()?>/template/static/logo.svg" height="70" alt=""></a>
                            </div>
                            <div class="card card-md">
                                <div class="card-body">
                                    <h2 class="h2 text-center mb-4">Login to your account</h2>
                                    <?php if (isset($error)): ?>
                                        <div class="alert alert-warning alert-dismissible" role="alert">
                                            <div class="d-flex">
                                                <div>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.24 3.957l-8.422 14.06a1.989 1.989 0 0 0 1.7 2.983h16.845a1.989 1.989 0 0 0 1.7 -2.983l-8.423 -14.06a1.989 1.989 0 0 0 -3.4 0z" /><path d="M12 9v4" /><path d="M12 17h.01" /></svg>
                                                </div>
                                                <div>
                                                    <?= $error; ?>
                                                </div>
                                            </div>
                                            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                                        </div>
                                    <?php endif; ?>
                                    <form action="<?= base_url('auth/login') ?>" method="POST">
                                        <div class="mb-3">
                                            <label class="form-label">Username / Email</label>
                                            <input type="text" name="identity" class="form-control" placeholder="your@email.com" autocomplete="off" required>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Password</label>
                                            <div class="input-group input-group-flat">
                                                <input type="password" name="password" class="form-control"  placeholder="password"  autocomplete="off" required>
                                            </div>
                                        </div>
                                        <div class="form-footer">
                                            <button type="submit" class="btn btn-primary w-100">Sign in</button>
                                        </div>
                                    </form>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-lg d-none d-lg-block">
                        <img src="<?= base_url()?>/template/static/illustrations/undraw_secure_login_pdn4.svg" height="300" class="d-block mx-auto" alt="">
                    </div>
                </div>
            </div>
        </div>
        <!-- Libs JS -->
        <!-- Tabler Core -->
        <script src="<?=base_url('template/dist/js/tabler.min.js?1692870487')?>" defer></script>
        <script src="<?=base_url('template/dist/js/demo.min.js?1692870487')?>" defer></script>
    </body>
</html>
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
    <title>Dashboard - Tabler - Premium and Open Source dashboard template with responsive and high quality UI.</title>
    <!-- CSS files -->
    <link href="<?= base_url()?>/template/dist/css/tabler.min.css?1692870487" rel="stylesheet"/>
    <link href="<?= base_url()?>/template/dist/css/tabler-flags.min.css?1692870487" rel="stylesheet"/>
    <link href="<?= base_url()?>/template/dist/css/tabler-payments.min.css?1692870487" rel="stylesheet"/>
    <link href="<?= base_url()?>/template/dist/css/tabler-vendors.min.css?1692870487" rel="stylesheet"/>
    <link href="<?= base_url()?>/template/dist/css/demo.min.css?1692870487" rel="stylesheet"/>

    <link href="<?= base_url()?>/asset/css/select2.min.css" rel="stylesheet"/>
    <style>
      @import url('https://rsms.me/inter/inter.css');
      :root {
      	--tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
      }
      body {
      	font-feature-settings: "cv03", "cv04", "cv11";
      }
    </style>
    <style>
      /* Loading Style */
      .loadingOverlay-custom {
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background-color: rgba(0, 0, 0, 0.5);
          backdrop-filter: blur(1px); 
          display: none;
          z-index: 9999;
      }

      #loadingSpinner {
          position: absolute;
          top: 50%;
          left: 50%;
          z-index: 10000; 
          display: none;
          width: 3rem; 
          height: 3rem;
      }
      /* Loading Style */
    </style>

    <script src="<?= base_url()?>/asset/js/jquery.min.js"></script>
    <script src="<?= base_url()?>/asset/js/sweetalert2.all.min.js"></script>
    <script src="<?= base_url()?>/asset/js/select2.min.js"></script>
    
    <script type="text/javascript">
        function showLoading() {
            $('#loadingSpinner').show();  // Menampilkan spinner
            $('#loadingOverlay').show();
        }

        function hideLoading() {
            $('#loadingSpinner').hide();  // Menyembunyikan spinner
            $('#loadingOverlay').hide();
        }
    </script>

  </head>
  <body >
    <script src="<?= base_url()?>/template/dist/js/demo-theme.min.js?1692870487"></script>

    <!-- LOADING SPINNER -->
    <div class="loadingOverlay-custom" id="loadingOverlay"></div>
    <div class="spinner-border text-primary" role="status" id="loadingSpinner" style="">
        <span class="visually-hidden">Loading...</span>
    </div>
    <!-- LOADING SPINNER -->
    
    <div class="page">
        <!-- Navbar -->
        <?= $this->include('layout/navbar') ?>
        
        <?= $this->include('layout/navbar-menu') ?>
        <!-- Navbar -->

        <div class="page-wrapper">
            <!-- Content -->
            <?= $this->renderSection('content') ?>
            <!-- Content -->

            <!-- Footer -->
            <footer class="footer footer-transparent d-print-none">
                <div class="container-xl">
                    <div class="row text-center align-items-center flex-row-reverse">
                        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                            <ul class="list-inline list-inline-dots mb-0">
                            <li class="list-inline-item">
                                Copyright &copy; 2024
                                <a href="." class="link-secondary">MediVisit</a>.
                                All rights reserved.
                            </li>
                            <li class="list-inline-item">
                                <a href="./changelog.html" class="link-secondary" rel="noopener">
                                v1.0.0
                                </a>
                            </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- Footer -->

        </div>

    </div>
    <!-- Libs JS -->
    <script src="<?= base_url()?>/asset/js/jquery-ui.js"></script>

    <script src="<?= base_url()?>/template/dist/libs/apexcharts/dist/apexcharts.min.js?1692870487" defer></script>
    <script src="<?= base_url()?>/template/dist/libs/jsvectormap/dist/js/jsvectormap.min.js?1692870487" defer></script>
    <script src="<?= base_url()?>/template/dist/libs/jsvectormap/dist/maps/world.js?1692870487" defer></script>
    <script src="<?= base_url()?>/template/dist/libs/jsvectormap/dist/maps/world-merc.js?1692870487" defer></script>
    <!-- Tabler Core -->
    <script src="<?= base_url()?>/template/dist/js/tabler.min.js?1692870487" defer></script>
    <script src="<?= base_url()?>/template/dist/js/demo.min.js?1692870487" defer></script>
    
  </body>
</html>
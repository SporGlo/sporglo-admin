<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en-US" dir="ltr" data-navigation-type="default" data-navbar-horizontal-shape="default">

<head>
    <base href="<?= base_url() ?>">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title><?= $page_title ?? 'Sporglo Admin' ?></title>

    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <!-- Favicons -->
    <link rel="shortcut icon" href="assets/images/114x114.png">
    <link rel="apple-touch-icon" href="assets/images/57x57.png">
    <link rel="apple-touch-icon" sizes="72x72" href="assets/images/72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="assets/images/114x114.png">
    <link rel="manifest" href="assets/img/favicons/manifest.json">
    <meta name="msapplication-TileImage" content="assets/img/favicons/mstile-150x150.png">
    <meta name="theme-color" content="#ffffff">
    <script src="vendors/simplebar/simplebar.min.js"></script>
    <script src="assets/js/config.js"></script>

    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <link href="vendors/simplebar/simplebar.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="../../../unicons.iconscout.com/release/v4.0.8/css/line.css"> -->
    <link href="assets/css/theme-rtl.min.css" type="text/css" rel="stylesheet" id="style-rtl">
    <link href="assets/css/theme.min.css" type="text/css" rel="stylesheet" id="style-default">
    <link href="assets/css/user-rtl.min.css" type="text/css" rel="stylesheet" id="user-style-rtl">
    <link href="assets/css/user.min.css" type="text/css" rel="stylesheet" id="user-style-default">
    <link rel="stylesheet" href="assets/css/app/common.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <script>
        var phoenixIsRTL = window.config.config.phoenixIsRTL;
        if (phoenixIsRTL) {
            var linkDefault = document.getElementById('style-default');
            var userLinkDefault = document.getElementById('user-style-default');
            linkDefault.setAttribute('disabled', true);
            userLinkDefault.setAttribute('disabled', true);
            document.querySelector('html').setAttribute('dir', 'rtl');
        } else {
            var linkRTL = document.getElementById('style-rtl');
            var userLinkRTL = document.getElementById('user-style-rtl');
            linkRTL.setAttribute('disabled', true);
            userLinkRTL.setAttribute('disabled', true);
        }
    </script>
    <link href="vendors/leaflet/leaflet.css" rel="stylesheet">
    <link href="vendors/leaflet.markercluster/MarkerCluster.css" rel="stylesheet">
    <link href="vendors/leaflet.markercluster/MarkerCluster.Default.css" rel="stylesheet">
</head>

<body>
    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">
        <!-- SIDEBAR  -->
        <?php $this->load->view('partials/__sidebar') ?>
        <!-- TOPNAV  -->
        <?php $this->load->view('partials/__topnav') ?>
        <div class="content">
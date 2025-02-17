<nav class="navbar navbar-vertical navbar-expand-lg">
    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
        <!-- scrollbar removed-->
        <div class="navbar-vertical-content">
            <ul class="navbar-nav flex-column" id="navbarVerticalNav">
                <li class="nav-item">
                    <div class="nav-item-wrapper">
                        <a class="nav-link label-1" href="<?= base_url() ?>" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><i class="fa-solid fa-house"></i></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Home</span></span></div>
                        </a>
                    </div>
                </li>
                <li class="nav-item">
                    <!-- label-->
                    <p class="navbar-vertical-label">Master Module</p>
                    <hr class="navbar-vertical-line" /><!-- parent pages-->
                    <div class="nav-item-wrapper">
                        <a class="nav-link dropdown-indicator label-1" href="#nv-email" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="nv-email">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon-wrapper"><span class="fas fa-caret-right dropdown-indicator-icon"></span></div><span class="nav-link-icon"><i class="fa-solid fa-users"></i></span><span class="nav-link-text">User Management</span>
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul class="nav collapse parent" data-bs-parent="#navbarVerticalCollapse" id="nv-email">
                                <li class="collapsed-nav-item-title d-none">User Management</li>
                                <li class="nav-item"><a class="nav-link" href="index.php/admin">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Admins</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="apps/email/email-detail.html">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Users</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                            </ul>
                        </div>
                    </div><!-- LINK ENDS-->
                    <div class="nav-item-wrapper">
                        <a class="nav-link label-1" href="index.php/sports" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><i class="fa-regular fa-futbol"></i></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Sports</span></span></div>
                        </a>
                    </div><!-- LINK ENDS-->
                    <div class="nav-item-wrapper">
                        <a class="nav-link label-1" href="index.php/position" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><i class="fa-solid fa-bars-staggered"></i></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Positions</span></span></div>
                        </a>
                    </div><!-- LINK ENDS-->
                </li>
                <li class="nav-item">
                    <!-- label-->
                    <p class="navbar-vertical-label">Approvals</p>
                    <hr class="navbar-vertical-line" /><!-- parent pages-->
                    <div class="nav-item-wrapper">
                        <a class="nav-link label-1" href="index.php/approval/emails" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><i class="fa-solid fa-envelope-circle-check"></i></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Emails Approval</span></span></div>
                        </a>
                    </div><!-- parent pages-->
                    <div class="nav-item-wrapper">
                        <a class="nav-link label-1" href="index.php/approval/posts" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><i class="fa-solid fa-check-to-slot"></i></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Posts Approval</span></span></div>
                        </a>
                    </div><!-- parent pages-->
                </li>
                <li class="nav-item">
                    <!-- label-->
                    <p class="navbar-vertical-label">Dynamic Forms</p>
                    <hr class="navbar-vertical-line" /><!-- parent pages-->

                    <div class="nav-item-wrapper"><a class="nav-link dropdown-indicator label-1" href="#nv-icons" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="nv-icons">
                            <div class="d-flex align-items-center">
                                <div class="dropdown-indicator-icon-wrapper"><span class="fas fa-caret-right dropdown-indicator-icon"></span></div><span class="nav-link-icon"><span data-feather="grid"></span></span><span class="nav-link-text">Staff Forms</span>
                            </div>
                        </a>
                        <div class="parent-wrapper label-1">
                            <ul class="nav collapse parent" data-bs-parent="#navbarVerticalCollapse" id="nv-icons">
                                <li class="collapsed-nav-item-title d-none">Staff Forms</li>
                                <li class="nav-item"><a class="nav-link" href="index.phpstaff">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Staff User Type</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="modules/icons/font-awesome.html">
                                        <div class="d-flex align-items-center"><span class="nav-link-text">Staff Fields Form</span></div>
                                    </a><!-- more inner pages-->
                                </li>
                            </ul>
                        </div>
                    </div><!-- parent pages-->
                </li>
                <li class="nav-item">
                    <!-- label-->
                    <p class="navbar-vertical-label">Account & Setting</p>  
                    <div class="nav-item-wrapper">
                        <a class="nav-link label-1" onclick="logoutAction()" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><i class="fa fa-sign-out" aria-hidden="true"></i>
                            </span><span class="nav-link-text-wrapper"><span class="nav-link-text">Log out</span></span>
                        </div>
                        </a>
                    </div>   
                </li>
            </ul>
        </div>
    </div>
    <div class="navbar-vertical-footer"><button class="btn navbar-vertical-toggle border-0 fw-semibold w-100 white-space-nowrap d-flex align-items-center"><span class="uil uil-left-arrow-to-left fs-8"></span><span class="uil uil-arrow-from-right fs-8"></span><span class="navbar-vertical-footer-text ms-2">Collapsed View</span></button></div>
</nav>
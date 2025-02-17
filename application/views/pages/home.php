<!-- MAIN CONTENT START -->
<div class="page-body">
    <div class="container-xxl mx-auto p-0">

        <!-- Dashboard Cards -->
        <div class="row row-deck row-cards mt-4">
            <div class="col-12">
                <div class="row row-cards">

                    <!-- Admin Count Card -->
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm shadow-md rounded-lg">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <i class="fa fa-user-shield fa-xl" style="color: #74C0FC;"></i>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium text-lg">Admins</div>
                                        <div class="text-muted" id="AdminCount">
                                            <?= $counts['admin']; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Club Count Card -->
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm shadow-md rounded-lg">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <i class="fa-solid fa-cube fa-xl" style="color: #63E6BE;"></i>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium text-lg">Clubs</div>
                                        <div class="text-muted" id="ClubCount">
                                            <?= $counts['club']; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Player Count Card -->
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm shadow-md rounded-lg">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <i class="fa-solid fa-futbol fa-xl" style="color: #74C0FC;"></i>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium text-lg">Players</div>
                                        <div class="text-muted" id="PlayerCount">
                                            <?= $counts['player']; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Staff Count Card -->
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm shadow-md rounded-lg">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <i class="fa-solid fa-users fa-xl" style="color: #135bd8;"></i>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium text-lg">Staff</div>
                                        <div class="text-muted" id="StaffCount">
                                            <?= $counts['staff']; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- MAIN CONTENT END -->

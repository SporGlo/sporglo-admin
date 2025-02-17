<div class="row">
    <nav class="mb-3" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="#!">Master Module</a></li>
            <li class="breadcrumb-item"><a href="#!">User Management</a></li>
            <li class="breadcrumb-item active">Admin</li>
        </ol>
    </nav>
    <div>
        <div class="row align-items-center justify-content-between g-3 mb-4">
            <div class="col-auto">
                <h2 class="text-bold text-body-emphasis">Add New Admin</h2>
            </div>
            <div class="col-auto">
                <div class="d-flex align-items-center gap-2"> <a href="admin"
                        class="btn btn-sm text-primary text-decoration-underline"> <i class="fa-solid fa-users"></i>
                        Admin List</a> </div>
            </div>
        </div>
        <div class="mx-n4 mx-lg-n6 px-4 px-lg-6 mb-9 bg-body-emphasis border-top mt-2 position-relative top-1">
            <form id="adminForm" onsubmit="SubmitAdmin(event)" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="id">
                <div class="row">
                    <div class="col-md-12 mt-4">
                        <div class="row mb-1"> <label class="col-sm-2 col-form-label col-form-label-sm"
                                for="type">Admin Type <span class="text-danger">*</span>
                                <span class="float-end d-none d-lg-block">:</span> </label>
                            <div class="col-sm-5">
                                <select class="form-control form-control-sm" name="admin_type" id="admin_type">
                                    <option value="">Choose Admin Type</option>
                                    <option value="Sub Admin">Sub Admin</option>
                                    <option value="Admin">Admin</option>
                                </select>
                                <p class="text-danger err-lbl mb-0 app-fs-sm" id="lbl-admin_type"></p>
                            </div>
                        </div>
                        <div class="row mb-1"> <label class="col-sm-2 col-form-label col-form-label-sm"
                                for="first_name">First Name <span class="text-danger">*</span> <span
                                    class="float-end d-none d-lg-block">:</span> </label>
                            <div class="col-sm-5">
                                <input class="form-control form-control-sm" type="text" name="first_name" id="first_name"
                                    placeholder="Enter First Name">
                                <p class="text-danger err-lbl mb-0 app-fs-sm" id="lbl-first_name"></p>
                            </div>
                        </div>
                        <div class="row mb-1"> <label class="col-sm-2 col-form-label col-form-label-sm"
                                for="last_name">Last Name <span class="text-danger">*</span> <span
                                    class="float-end d-none d-lg-block">:</span> </label>
                            <div class="col-sm-5"> <input class="form-control form-control-sm" type="text"
                                    name="last_name" id="last_name" placeholder="Enter Last Name">
                                <p class="text-danger err-lbl mb-0 app-fs-sm" id="lbl-last_name"></p>
                            </div>
                        </div>
                        <div class="row mb-1"> <label class="col-sm-2 col-form-label col-form-label-sm"
                                for="email">Email<span class="text-danger">*</span> <span
                                    class="float-end d-none d-lg-block">:</span> </label>
                            <div class="col-sm-3"> <input class="form-control form-control-sm" type="email"
                                    name="email" id="email" placeholder="Enter your email">
                                <p class="text-danger err-lbl mb-0 app-fs-sm" id="lbl-email"></p>
                            </div>
                        </div>
                        <div class="row mb-1 elements-to-hide"> <label class="col-sm-2 col-form-label col-form-label-sm"
                                for="password">Password<span class="text-danger">*</span> <span
                                    class="float-end d-none d-lg-block">:</span> </label>
                            <div class="col-sm-3"> <input class="form-control form-control-sm" type="password"
                                    name="password" id="password" placeholder="Enter your password" >
                                <p class="text-danger err-lbl mb-0 app-fs-sm" id="lbl-password"></p>
                            </div>
                        </div>
                        <div class="row mb-1 elements-to-hide"> <label class="col-sm-2 col-form-label col-form-label-sm"
                                for="password">Confirm Password<span class="text-danger">*</span> <span
                                    class="float-end d-none d-lg-block">:</span> </label>
                            <div class="col-sm-3"> <input class="form-control form-control-sm" type="password"
                                    name="confirm_password" id="confirm_password" placeholder="Enter your confirm password">
                                <p class="text-danger err-lbl mb-0 app-fs-sm" id="lbl-confirm_password"></p>
                            </div>
                        </div>
                        <div class="row mb-1"> <label class="col-sm-2 col-form-label col-form-label-sm"
                                for="city">City<span class="text-danger">*</span> <span
                                    class="float-end d-none d-lg-block">:</span> </label>
                            <div class="col-sm-3"> <input class="form-control form-control-sm" type="text"
                                    name="city" id="city" placeholder="Enter your city">
                                <p class="text-danger err-lbl mb-0 app-fs-sm" id="lbl-city"></p>
                            </div>
                        </div>
                        <div class="row mb-1"> <label class="col-sm-2 col-form-label col-form-label-sm"
                                for="type">Country <span class="text-danger">*</span>
                                <span class="float-end d-none d-lg-block">:</span> </label>
                            <div class="col-sm-3">
                                <select class="form-control form-control-sm" name="country" id="country">
                                    <option value="">Choose Country
                                    </option>
                                    <option value="India">India</option>
                                    <option value="USA">USA</option>
                                </select>
                                <p class="text-danger err-lbl mb-0 app-fs-sm" id="lbl-country"></p>
                            </div>
                        </div>


                        <div class="row my-4">
                            <div class="col-md-12 text-center">
                                 <button type="submit" class="btn btn-sm btn-success" id="submit-btn">
                                    <i class="fa-solid fa-plus"></i> Save Admin Details</button> </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
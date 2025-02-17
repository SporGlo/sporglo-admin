<!-- <div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-lg">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">Sports Management</h3>
                <form id="sportsForm" onsubmit="SubmitSport(event)" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="id">

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="type" class="form-label">Sport Type <span class="text-danger">*</span> </label>
                            <select class="form-select" id="type" name="type">
                                <option value="" disabled selected>Select Sport Type</option>
                                <option value="Team Sport">Team Sport</option>
                                <option value="Individual Sport">Individual Sport</option>
                                <option value="Both">Both</option>
                            </select>
                            <p class="text-danger err-lbl mb-0" id="lbl-type"></p>

                        </div>
                        <div class="col-md-6">
                            <label for="sport_name" class="form-label">Sport Name <span class="text-danger">*</span> </label>
                            <input type="text" id="sport_name" name="sport_name" class="form-control" placeholder="Enter sport name" />
                            <p class="text-danger err-lbl mb-0" id="lbl-sport_name"></p>

                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="sport_order" class="form-label">Sport Order <span class="text-danger">*</span> </label>
                        <input type="number" id="sport_order" name="sport_order" class="form-control" placeholder="Enter sport order" />
                        <p class="text-danger err-lbl mb-0" id="lbl-sport_order"></p>

                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span> </label>
                        <textarea id="description" name="description" class="form-control" rows="4" placeholder="Write a brief description about the sport"></textarea>
                        <p class="text-danger err-lbl mb-0" id="lbl-description"></p>

                    </div>

                    <div class="mb-4">
                        <label for="history" class="form-label">History <span class="text-danger">*</span> </label>
                        <textarea id="history" name="history" class="form-control" rows="4" placeholder="Provide a brief history of the sport"></textarea>
                        <p class="text-danger err-lbl mb-0" id="lbl-history"></p>

                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="cover_image" class="form-label">Cover Image <span class="text-danger">*</span> </label>
                            <input type="file" id="cover_image" name="cover_image" class="form-control" accept="image/png, image/jpeg" />
                            <p class="text-danger err-lbl mb-0" id="lbl-cover_image"></p>

                        </div>
                        <div class="col-md-6">
                            <label for="category_image" class="form-label">Category Image <span class="text-danger">*</span> </label>
                            <input type="file" id="category_image" name="category_image" class="form-control" accept="image/png, image/jpeg" />
                            <p class="text-danger err-lbl mb-0" id="lbl-category_image"></p>

                        </div>
                    </div>

               
                    <div class="text-end">
                        <button type="submit" class="btn btn-submit btn-primary" id="submit-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> -->
<div class="row">
    <nav class="mb-3" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="#!">Master Module</a></li>
            <li class="breadcrumb-item"><a href="sports">Sports</a></li>
            <li class="breadcrumb-item active">New Sports</li>
        </ol>
    </nav>
    <div>
        <div class="row align-items-center justify-content-between g-3 mb-4">
            <div class="col-auto">
                <h2 class="text-bold text-body-emphasis">Add New Sports</h2>
            </div>
            <div class="col-auto">
                <div class="d-flex align-items-center gap-2"> <a href="sports"
                        class="btn btn-sm text-primary text-decoration-underline"> <i class="fa-solid fa-users"></i>
                        Sports List</a> </div>
            </div>
        </div>
        <div class="mx-n4 mx-lg-n6 px-4 px-lg-6 mb-9 bg-body-emphasis border-top mt-2 position-relative top-1">
            <form id="sportsForm" onsubmit="SubmitSport(event)" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="id">
                <div class="row">
                    <div class="col-md-12 mt-4">
                        <div class="row mb-1"> <label class="col-sm-2 col-form-label col-form-label-sm"
                                for="type">Sport Type <span class="text-danger">*</span>
                                <span class="float-end d-none d-lg-block">:</span> </label>
                            <div class="col-sm-5">
                                <select class="form-control form-control-sm" name="type" id="type">
                                    <option value="">Choose Sport Type</option>
                                    <option value="Team Sport">Team Sport</option>
                                    <option value="Individual Sport">Individual Sport</option>
                                    <option value="Both">Both</option>
                                </select>
                                <p class="text-danger err-lbl mb-0 app-fs-sm" id="lbl-type"></p>
                            </div>
                        </div>
                        <div class="row mb-1"> <label class="col-sm-2 col-form-label col-form-label-sm"
                                for="sport_name">Sport Name <span class="text-danger">*</span> <span
                                    class="float-end d-none d-lg-block">:</span> </label>
                            <div class="col-sm-5">
                                <input class="form-control form-control-sm" type="text" name="sport_name" id="sport_name"
                                    placeholder="Enter Sport Name">
                                <p class="text-danger err-lbl mb-0 app-fs-sm" id="lbl-sport_name"></p>
                            </div>
                        </div>
                        <div class="row mb-1"> <label class="col-sm-2 col-form-label col-form-label-sm"
                                for="sport_order">Sport Order <span class="text-danger">*</span> <span
                                    class="float-end d-none d-lg-block">:</span> </label>
                            <div class="col-sm-3"> <input class="form-control form-control-sm" type="number"
                                    name="sport_order" id="sport_order">
                                <p class="text-danger err-lbl mb-0 app-fs-sm" id="lbl-sport_order"></p>
                            </div>
                        </div>
                        <div class="row mb-1"> <label class="col-sm-2 col-form-label col-form-label-sm"
                                for="role_id">Description <span class="text-danger">*</span> <span
                                    class="float-end d-none d-lg-block">:</span> </label>
                            <div class="col-sm-5">
                                <textarea id="description" name="description" class="form-control" rows="4" placeholder="Write a brief description about the sport"></textarea>
                                <p class="text-danger err-lbl mb-0 app-fs-sm" id="lbl-description"></p>
                            </div>
                        </div>
                        <div class="row mb-1"> <label class="col-sm-2 col-form-label col-form-label-sm"
                                for="status">History <span class="text-danger">*</span> <span
                                    class="float-end d-none d-lg-block">:</span> </label>
                            <div class="col-sm-5">
                                <textarea id="history" name="history" class="form-control" rows="4" placeholder="Provide a brief history of the sport"></textarea>
                                <p class="text-danger err-lbl mb-0 app-fs-sm" id="lbl-history"></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <!-- Cover Image and Category Image in the same row -->
                            <div class="col-md-3">
                                <label class="form-label" for="cover_image">
                                    Cover Image <span class="text-danger">*</span>:
                                </label>
                                <input type="file" id="cover_image" name="cover_image" class="form-control" accept="image/png, image/jpeg" />
                                <p class="text-danger err-lbl mb-0" id="cover_image_error"></p>
                                <img id="coverPreview" src="#" alt="Preview" class="w-30 mt-2 d-none rounded">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label" for="category_image">
                                    Category Image <span class="text-danger">*</span>:
                                </label>
                                <input type="file" id="category_image" name="category_image" class="form-control" accept="image/png, image/jpeg" />
                                <p class="text-danger err-lbl mb-0" id="category_image_error"></p>
                                <img id="categoryPreview" src="#" alt="Preview" class="w-30  mt-2 d-none rounded">
                            </div>
                        </div>



                        <div class="row my-4">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-sm btn-success" id="submit-btn">
                                    <i class="fa-solid fa-plus"></i> Save Sports Details</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
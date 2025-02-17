<form id="positionForm" method="post" enctype="multipart/form-data" onsubmit="SubmitPosition(event)"> <input type="hidden" name="id" id="id">
    <div class="row">
        <div class="col-md-12 mt-4">
            <div class="row mb-1"> <label class="col-sm-4 col-form-label col-form-label-sm" for="sport_id">Sport Name <span class="text-danger">*</span> <span class="float-end d-none d-lg-block">:</span> </label>
                <div class="col-sm-8">
                    <select class="form-control"  name="sport_id" id="sport_id">
                        <option></option>

                    </select>
                    <p class="text-danger err-lbl mb-0 app-fs-sm" id="lbl-sport_id"></p>
                </div>
            </div>
            <div class="row mb-1"> <label class="col-sm-4 col-form-label col-form-label-sm" for="position_name">Position Name <span class="text-danger">*</span> <span class="float-end d-none d-lg-block">:</span> </label>
                <div class="col-sm-8"> <input class="form-control form-control-sm" type="text" name="position_name" id="position_name" placeholder="Enter Sport Position">
                    <p class="text-danger err-lbl mb-0 app-fs-sm" id="lbl-position_name"></p>
                </div>
            </div>
            <div class="row mb-1"> <label class="col-sm-4 col-form-label col-form-label-sm" for="position_type">Position Type <span class="text-danger">*</span> <span class="float-end d-none d-lg-block">:</span> </label>
                <div class="col-sm-8"> <input class="form-control form-control-sm" type="text" name="position_type" id="position_type" placeholder="Enter Position Type">
                    <p class="text-danger err-lbl mb-0 app-fs-sm" id="lbl-position_type"></p>
                </div>
            </div>
            <div class="row my-4">
                <div class="col-md-12 text-center"> <button class="btn btn-sm btn-success" id="submit-btn"> <i class="fa-solid fa-plus"></i> Save Position Details</button> </div>
            </div>
        </div>
    </div>
</form>
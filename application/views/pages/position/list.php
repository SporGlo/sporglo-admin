<div class="row">
    <nav class="mb-3" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="#!">Master Module</a></li>
            <li class="breadcrumb-item active">Positions</li>
        </ol>
    </nav>
    <div>
        <div class="row align-items-center justify-content-between g-3 mb-4">
            <div class="col-auto">
                <h2 class="text-bold text-body-emphasis">Position </h2>
            </div>
        </div>
        <div class="mx-n4 mx-lg-n6 px-4 px-lg-6 mb-9 bg-body-emphasis border-y mt-2 position-relative top-1">
            <div class="row mt-4">
                <div class="col-md-6">
                    <h5>Create New Position</h5> <?php $this->load->view('pages/position/new') ?>
                </div>
                <div class="col-md-6">
                    <div class="table-responsive scrollbar ms-n1 ps-1">
                        <table class="table fs-9 mb-0" id="position-table">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Sport Name</th>
                                    <th class="text-center">Position Name</th>
                                    <th class="text-center">Position Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="position-table-tbody"> </tbody>
                        </table>
                    </div>
                    <div class="py-2"> <?= renderPaginate('current-page', 'total-pages', 'page-of-pages', 'range-of-records') ?> </div>
                </div>
            </div>
        </div>
    </div>
</div>
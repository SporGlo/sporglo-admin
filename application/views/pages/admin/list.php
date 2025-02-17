

<div class="row">
    <nav class="mb-3" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="#!">Master Module</a></li>
            <li class="breadcrumb-item active">Admin</li>
        </ol>
    </nav>
    <div>
        <div class="row align-items-center justify-content-between g-3 mb-4">
            <div class="col-auto">
                <h2 class="text-bold text-body-emphasis">Admin List</h2>
            </div>
            <div class="col-auto">
                <div class="d-flex align-items-center gap-2"> <a href="admin/add" class="btn btn-sm text-primary text-decoration-underline"> <i class="fa-solid fa-plus"></i> Add New Admin</a> </div>
            </div>
        </div>
        <div class="mx-n4 mx-lg-n6 px-4 px-lg-6 mb-9 bg-body-emphasis border-y mt-2 position-relative top-1">
            <div>
                <table class="table mb-0" id="admin-table">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Admin Id</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Full Name</th>
                            <th class="text-center">City</th>
                            <th class="text-center">Country</th>
                            <th class="text-center">Type</th>
                            <th class="">Action</th>
                        </tr>
                    </thead>
                    <tbody id="admin-table-tbody"> </tbody>
                </table>
            </div>
            <div class="py-2"> <?= renderPaginate('current-page', 'total-pages', 'page-of-pages', 'range-of-records') ?> </div>
        </div>
    </div>
</div>
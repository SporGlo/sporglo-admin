const tableId = "admin-table";
const table = document.getElementById(tableId);
const tbody = document.querySelector(`#${tableId} tbody`);
const numberOfHeaders = document.querySelectorAll(`#${tableId} thead th`).length || 0;

function renderNoResponseCode(option, isAdmin = false) {
    return `<tr>
                        <td colspan="9">
                            <div class="row justify-content-center">
                                <div class="col-md-5 text-center">
                                    <img src="assets/images/admin_not_found.png" class="img-fluid" alt="">
                                    <div class="my-4">
                                        <h3 class="">No Admin found</h3>
                                        <p> Start by adding your first Admin to streamline your processes.</p>
                                        <a href="admin/add" class="btn btn-sm btn-primary"> <i class="fa fa-plus"></i> Add New Admin</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>`;
}

// Set Up Paginate
const paginate = new Pagination({
    currentPageId: 'current-page',
    totalPagesId: 'total-pages',
    pageOfPageId: 'page-of-pages',
    recordsRangeId: 'range-of-records'
});
paginate.pageLimit = 10; // Set your page limit here

function renderAdminList(admins) {
    const adminTbody = document.querySelector(`#${tableId} tbody`);

    if (!admins) {
        throw new Error("Admin details not found");
    }
    if (admins && admins.length > 0) {
        let content = ''
        let counter = 1;
        admins.forEach(admin => {
            content += `<tr data-sport-id="${admin?.id}">
                            <td class="text-center">${counter++}</td>
                            <td class="text-center">${admin?.admin_id}</td>
                            <td class="text-center">${admin?.email}</td>
                            <td class="text-center">${admin?.first_name} ${admin?.last_name}</td>
                            <td class="text-center">${admin?.city}</td>
                            <td class="text-center">${admin?.country}</td>
                            <td class="text-center">${admin?.admin_type}</td>


                            <td class="text-end">
                                <div class="hstack gap-2 fs-15">
                                    <a href="admin/add/${admin.id}?action=edit" class="btn btn-icon btn-sm text-success" title="Edit">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                    <a href="javascript:void(0);" onclick="deleteAdmin(${admin.id})"  class="btn btn-icon btn-sm text-danger" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>`
        });
        adminTbody.innerHTML = '';
        adminTbody.innerHTML = content;

    } else {
        adminTbody.innerHTML = renderNoResponseCode();
    }

}

async function fetchAdminList() {
    try {
        // Check token exist
        const authToken = validateUserAuthToken();
        if (!authToken) return; 

        // Set loader to the screen 
        const skeletonLoaderContent = adminListSkeleton();
        repeatAndAppendSkeletonContent(tableId, skeletonLoaderContent, paginate.pageLimit || 0);

        const url = `${ApiUrl}admin`;
        const filters = filterCriterias([]);

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                limit: paginate.pageLimit,
                currentPage: paginate.currentPage,
                filters: filters
            })
        });

        if (!response.ok) {
            throw new Error('Failed to fetch admin data');
        }

        const data = await response.json();
        paginate.totalPages = parseFloat(data?.pagination?.total_pages) || 0;
        paginate.totalRecords = parseFloat(data?.pagination?.total_records) || 0;

        renderAdminList(data.admins || []);

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        tbody.innerHTML = renderNoResponseCode();
        console.error(error);
    }
}

async function deleteAdmin(sportID) {

    if (!sportID) {
        throw new Error("Invalid Admin ID, Please try Again");
    }

    try {

        // Show a confirmation alert
        const confirmation = await Swal.fire({
            title: "Are you sure?",
            text: "Do you really want to delete this Admin? This action cannot be undone.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it",
            cancelButtonText: "Cancel",
            customClass: {
                popup: 'small-swal',
                confirmButton: 'swal-confirm-btn',
                cancelButton: 'swal-cancel-btn',
            },
        });

        if (!confirmation.isConfirmed) return;

        const authToken = validateUserAuthToken();
        if (!authToken) return;

        // Show a non-closable alert box while the activity is being deleted
        Swal.fire({
            title: "Deleting Admin ...",
            text: "Please wait while the admin is being deleted.",
            icon: "info",
            showConfirmButton: false,
            allowOutsideClick: false,
            customClass: {
                popup: 'small-swal',
            },
        });

        const url = `${ApiUrl}admin/delete/${sportID}`;

        const response = await fetch(url, {
            method: 'DELETE', // Change to DELETE for a delete request
            headers: {
                'Authorization': `Bearer ${authToken}`
            }
        });

        const data = await response.json(); // Parse the JSON response

        // Close the loading alert box
        Swal.close();

        if (!response.ok) {
            // If the response is not ok, throw an error with the message from the response
            throw new Error(data.error || 'Failed to delete admin details');
        }

        if (data.status) {
            // Here, we directly handle the deletion without checking data.status
            toasterNotification({ type: 'success', message: data?.message || "Record Deleted Successfully" });
            fetchAdminList();
        } else {
            throw new Error(data.message || 'Failed to delete sport details');
        }

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        Swal.close();
    }
}





document.addEventListener('DOMContentLoaded', () => {
    // Fetch initial product data
    fetchAdminList();

});
function handlePagination(action) {
    paginate.paginate(action); // Update current page based on the action
    fetchAdminList(); // Fetch records
}
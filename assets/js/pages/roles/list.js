const tableId = "roles-table";
const table = document.getElementById(tableId);
const tbody = document.querySelector(`#${tableId} tbody`);
const numberOfHeaders = document.querySelectorAll(`#${tableId} thead th`).length || 0;

function renderNoResponseCode(option, isAdmin = false) {
    return `<tr><td class="text-center" colspan="${option?.colspan}">No data available</td></tr>`;
}

// Set Up Paginate
const paginate = new Pagination({
    currentPageId: 'current-page',
    totalPagesId: 'total-pages',
    pageOfPageId: 'page-of-pages',
    recordsRangeId: 'range-of-records'
});
paginate.pageLimit = 10; // Set your page limit here

const roleStatus = {
    Active: "success",
    Inactive: "danger"
}

function renderRolesList(roles) {
    const rolesTbody = document.querySelector(`#${tableId} tbody`);

    if (!roles) {
        throw new Error("roles list not found");
    }

    if (roles && roles.length > 0) {
        let content = ''
        let counter = 0;
        roles.forEach(role => {

            content += `<tr data-role-id=${role.id}>
                            <td>${++counter}</td>
                            <td><p class="mb-0">${role.role_name}</p></td>
                            <td>
                            <span class="badge badge-phoenix badge-phoenix-${roleStatus[role.status || '']}">${capitalizeWords(role.status)}</span>
                            </td>
                            <td><p class="mb-0">${role.description || ''}</p></td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <a href="javascript:void(0)" onclick="fetchRolesDetailsForEdit(${role.id})" class="text-secondary app-fs-md" title="Edit role"><i class="fa-solid fa-file-pen fs-9"></i></a>
                                    <a href="javascript:void(0)" onclick="deleteRole(${role.id})" class="text-danger app-fs-md" title="Delete role"><i class="fa-solid fa-trash-can fs-9"></i></a>
                                </div>
                            </td>
                        </tr>`
        });
        rolesTbody.innerHTML = '';
        rolesTbody.innerHTML = content;

    } else {
        rolesTbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
    }

}

async function fetchRolesList() {
    try {
        // Check token exist
        const authToken = validateUserAuthToken();
        if (!authToken) return;

        // Set loader to the screen 
        const skeletonLoaderContent = commonSkeletonContent(numberOfHeaders);
        repeatAndAppendSkeletonContent(tableId, skeletonLoaderContent, paginate.pageLimit || 0);

        const url = `${apiURL}roles`;
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
            throw new Error('Failed to fetch data');
        }

        const data = await response.json();
        paginate.totalPages = parseFloat(data?.pagination?.total_pages) || 0;
        paginate.totalRecords = parseFloat(data?.pagination?.total_records) || 0;

        renderRolesList(data.roles || []);

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
        console.error(error);
    }
}

async function deleteRole(roleID) {

    if (!roleID) {
        throw new Error("Invalid role ID, Please try Again");
    }

    try {

        // Show a confirmation alert
        const confirmation = await Swal.fire({
            title: "Are you sure?",
            text: "Do you really want to delete role? This action cannot be undone.",
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
            title: "Deleting role ...",
            text: "Please wait while the role is being deleted.",
            icon: "info",
            showConfirmButton: false,
            allowOutsideClick: false,
            customClass: {
                popup: 'small-swal',
            },
        });

        const url = `${apiURL}/roles/delete/${roleID}`;

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
            throw new Error(data.error || 'Failed to delete users details');
        }

        if (data.status) {
            // Here, we directly handle the deletion without checking data.status
            toasterNotification({ type: 'success', message: data?.message || "Record Deleted Successfully" });

            fetchRolesList();
        } else {
            throw new Error(data.message || 'Failed to delete users details');
        }

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        Swal.close();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Fetch initial User data
    fetchRolesList();
});
function handlePagination(action) {
    paginate.paginate(action); // Update current page based on the action
    fetchRolesList(); // Fetch records
}

function filterContacts() {
    paginate.currentPage = 1;
    fetchRolesList();
}

const tableId = "sports-table";
const table = document.getElementById(tableId);
const tbody = document.querySelector(`#${tableId} tbody`);
const numberOfHeaders = document.querySelectorAll(`#${tableId} thead th`).length || 0;

function renderNoResponseCode(option, isAdmin = false) {
    return `<tr>
                        <td colspan="9">
                            <div class="row justify-content-center">
                                <div class="col-md-5 text-center">
                                    <img src="assets/images/sport_not_found.png" class="w-70" alt="">
                                    <div class="my-4">
                                        <h3 class="">No Sports   found</h3>
                                        <p> Start by adding your first Sport to streamline your processes.</p>
                                        <a href="sports/add" class="btn btn-sm btn-primary"> <i class="fa fa-plus"></i> Add New Sport</a>
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

function renderSportsList(sports) {
    const sportsTbody = document.querySelector(`#${tableId} tbody`);

    if (!sports) {
        throw new Error("Sports details not found");
    }
    if (sports && sports.length > 0) {
        let content = ''
        let counter = 1;
        sports.forEach(sport => {
            content += `<tr data-sport-id="${sport?.id}">
                            <td class="text-center">${counter++}</td>
                            <td class="text-center">${sport?.sport_name}</td>
                            <td class="text-center">${sport?.type}</td>
                            <td class="text-center">${sport?.sport_order}</td>
                         
                     
                            <td class="text-end">
                                <div class="hstack gap-2 fs-15">
                                    <a href="sports/add/${sport.id}?action=edit" class="btn btn-icon btn-sm text-success" title="Edit">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                    <a href="javascript:void(0);" onclick="deleteSport(${sport.id})"  class="btn btn-icon btn-sm text-danger" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>`
        });
        sportsTbody.innerHTML = '';
        sportsTbody.innerHTML = content;

    } else {
        sportsTbody.innerHTML = renderNoResponseCode();
    }

}

async function fetchSportList() {
    try {
        // Check token exist
        const authToken = validateUserAuthToken();
        if (!authToken) return; 

        // Set loader to the screen 
        const skeletonLoaderContent = usersListSkeleton();
        repeatAndAppendSkeletonContent(tableId, skeletonLoaderContent, paginate.pageLimit || 0);

        const url = `${ApiUrl}sports`;
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
            throw new Error('Failed to fetch Sport data');
        }

        const data = await response.json();
        paginate.totalPages = parseFloat(data?.pagination?.total_pages) || 0;
        paginate.totalRecords = parseFloat(data?.pagination?.total_records) || 0;

        renderSportsList(data.sports || []);

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        tbody.innerHTML = renderNoResponseCode();
        console.error(error);
    }
}

async function deleteSport(sportID) {

    if (!sportID) {
        throw new Error("Invalid Sport ID, Please try Again");
    }

    try {

        // Show a confirmation alert
        const confirmation = await Swal.fire({
            title: "Are you sure?",
            text: "Do you really want to delete this Sport? This action cannot be undone.",
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
            title: "Deleting Product ...",
            text: "Please wait while the product is being deleted.",
            icon: "info",
            showConfirmButton: false,
            allowOutsideClick: false,
            customClass: {
                popup: 'small-swal',
            },
        });

        const url = `${ApiUrl}sports/delete/${sportID}`;

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
            throw new Error(data.error || 'Failed to delete sport details');
        }

        if (data.status) {
            // Here, we directly handle the deletion without checking data.status
            toasterNotification({ type: 'success', message: data?.message || "Record Deleted Successfully" });
            fetchSportList();
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
    fetchSportList();

});
function handlePagination(action) {
    paginate.paginate(action); // Update current page based on the action
    fetchSportList(); // Fetch records
}
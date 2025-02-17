const tableId = "projects-table";
const table = document.getElementById(tableId);
const tbody = document.querySelector(`#${tableId} tbody`);
const numberOfHeaders = document.querySelectorAll(`#${tableId} thead th`).length || 0;

function renderNoResponseCode(option, isAdmin = false) {
    return `<tr>
                            <td colspan="${option.colspan}" class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-8">
                                    <img src="assets/images/no-projects.png" class="" style="width: 300px" alt="Projects">
                                    <div>
                                        <h4 class="">No projects available at the moment.</h4>
                                        <p>Looks like you don't have any projects yet. Start by creating one!</p>
                                        <a href="projects/new" class="btn btn-sm btn-primary my-2"> <i class="fa-solid fa-plus"></i> Click to add new project</a>
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

const projectStatus = {
    Planned: "secondary",   // Planned: Neutral status, ready to start
    "In Progress": "warning",  // In Progress: Ongoing work
    Completed: "success",   // Completed: Task finished successfully
    "On Hold": "info",         // On Hold: Work paused temporarily
};

const priorityStatus = {
    Low: "success",    // Low priority: No urgency
    Medium: "warning", // Medium priority: Moderate urgency
    High: "danger",    // High priority: Urgent and needs attention
};

function renderProjectsList(projects) {
    const projectsTbody = document.querySelector(`#${tableId} tbody`);

    if (!projects) {
        throw new Error("Projects list not found");
    }


    if (projects && projects.length > 0) {
        let content = ''
        let counter = 0;
        projects.forEach(project => {

            content += `<tr data-project-id=${project.id}>
                            <td>${++counter}</td>
                            <td><p class="mb-0">${project.project_code}</p></td>
                            <td>
                                <a href="projects/overview/${project.id}/${project?.project_name?.toLowerCase()?.replaceAll(" ", "-")}" class="text-primary mb-0">${project.project_name}</a>
                            </td>
                            <td>${formatAppDate(project.start_date)}</td>
                            <td>${formatAppDate(project.end_date)}</td>
                            <td>
                                <span class="badge badge-phoenix badge-phoenix-${priorityStatus[project.priority || '']}">${capitalizeWords(project.priority)}</span>
                            </td>
                            <td>${project.budget}</td>
                            <td>
                                <span class="badge badge-phoenix badge-phoenix-${projectStatus[project.status || '']}">${capitalizeWords(project.status)}</span>
                            </td>
                            <td>${formatAppDate(project.created_at)}</td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-3">
                                    <a href="projects/overview/${project.id}/${project?.project_name?.toLowerCase()?.replaceAll(" ", "-")}" class="text-info app-fs-md" title="View project"><i class="fa-solid fa-up-right-from-square"></i></a>
                                    <a href="projects/new/${project.id}?action=edit" class="text-primary app-fs-md" title="Edit project"><i class="fa-solid fa-file-pen"></i></a>
                                    <a href="javascript:void(0)" onclick="deleteProject(${project.id})" class="text-danger app-fs-md" title="Delete project"><i class="fa-solid fa-trash-can"></i></a>
                                </div>
                            </td>
                        </tr>`
        });
        projectsTbody.innerHTML = '';
        projectsTbody.innerHTML = content;

    } else {
        projectsTbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
    }

}

async function fetchProjectsList() {
    try {
        // Check token exist
        const authToken = validateUserAuthToken();
        if (!authToken) return;

        // Set loader to the screen 
        const skeletonLoaderContent = commonSkeletonContent(numberOfHeaders);
        repeatAndAppendSkeletonContent(tableId, skeletonLoaderContent, paginate.pageLimit || 0);

        const url = `${apiURL}projects`;
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

        renderProjectsList(data.projects || []);

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
        console.error(error);
    }
}

async function deleteProject(projectID) {

    if (!projectID) {
        throw new Error("Invalid project ID, Please try Again");
    }

    try {

        // Show a confirmation alert
        const confirmation = await Swal.fire({
            title: "Are you sure?",
            text: "Do you really want to delete project? This action cannot be undone.",
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
            title: "Deleting project ...",
            text: "Please wait while the project is being deleted.",
            icon: "info",
            showConfirmButton: false,
            allowOutsideClick: false,
            customClass: {
                popup: 'small-swal',
            },
        });

        const url = `${apiURL}/projects/delete/${projectID}`;

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
            throw new Error(data.error || 'Failed to delete projects details');
        }

        if (data.status) {
            // Here, we directly handle the deletion without checking data.status
            toasterNotification({ type: 'success', message: data?.message || "Record Deleted Successfully" });

            fetchProjectsList();
        } else {
            throw new Error(data.message || 'Failed to delete projects details');
        }

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        Swal.close();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Fetch initial User data
    fetchProjectsList();
});
function handlePagination(action) {
    paginate.paginate(action); // Update current page based on the action
    fetchProjectsList(); // Fetch records
}

function filterContacts() {
    paginate.currentPage = 1;
    fetchProjectsList();
}

const tableId = "tasks-table";
const table = document.getElementById(tableId);
const tbody = document.querySelector(`#${tableId} tbody`);
const numberOfHeaders = document.querySelectorAll(`#${tableId} thead th`).length || 0;

function renderNoResponseCode(option, isAdmin = false) {
    return `<tr>
                            <td colspan="${option.colspan}" class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-8">
                                    <div>
                                        <h4 class="">No tasks to tackle yet!</h4>
                                        <p>You're all caught up for now. Ready to plan something amazing?</p>
                                        <a href="tasks/new" class="btn btn-sm btn-primary my-2"> <i class="fa-solid fa-plus"></i> Create Your New Task</a>
                                    </div>
                                    <img src="assets/images/new-task.png" class="" style="width: 300px" alt="Taskss">
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

const priorityColors = {
    low: "success",       // Green for low priority
    medium: "warning",    // Yellow/Amber for medium priority
    high: "danger"        // Red for high priority
};

const statusColors = {
    open: "primary",          // Blue for open
    inprogress: "warning",    // Yellow/Amber for in-progress
    completed: "success",     // Green for completed
    assigned: "info",         // Cyan/Teal for assigned
    hold: "secondary"         // Gray for on-hold
};

function renderTasksList(tasks) {
    const tasksTbody = document.querySelector(`#${tableId} tbody`);

    if (!tasks) {
        throw new Error("tasks list not found");
    }

    if (tasks && tasks.length > 0) {
        let content = ''
        let counter = 0;
        tasks.forEach(task => {

            content += `<tr data-task-id=${task.id}>
                            <td>${++counter}</td>
                            <td class=""> <p class="mb-0 line-clamp-1">${task.task_title}</p> </td>
                            <td class=""> <p class="mb-0 line-clamp-1">${task.description}</p> </td>
                            <td class=""> <p class="mb-0 line-clamp-1">${task.project_name}</p> </td>
                            <td>${formatAppDate(task.due_date)}</td>
                            <td>
                                <span class="badge badge-phoenix badge-phoenix-${priorityColors[task.priority || '']}">${capitalizeWords(task.priority)}</span>
                            </td>
                            <td>${task.full_name}</td>
                            <td>
                                <span class="badge badge-phoenix badge-phoenix-${statusColors[task.status || '']}">${capitalizeWords(task.status)}</span>
                            </td>
                            <td>${formatAppDate(task.created_at)}</td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <a href="tasks/overview/${task.id}/${task?.task_title?.toLowerCase()?.replaceAll(" ", "-")}" class="app-fs-sm text-secondary" title="View Tasks"><i class="fa-solid fa-up-right-from-square"></i></a>
                                    <a href="tasks/new/${task.id}?action=edit" class="app-fs-sm text-secondary" title="Edit Task"><i class="fa-solid fa-file-pen"></i></a>
                                    <a href="javascript:void(0)" onclick="deleteTask(${task.id})" class="text-danger app-fs-sm" title="Delete Task"><i class="fa-solid fa-trash-can"></i></a>
                                </div>
                            </td>
                        </tr>`
        });
        tasksTbody.innerHTML = '';
        tasksTbody.innerHTML = content;

    } else {
        tasksTbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
    }

}

async function fetchTasksList() {
    try {
        // Check token exist
        const authToken = validateUserAuthToken();
        if (!authToken) return;

        // Set loader to the screen 
        const skeletonLoaderContent = commonSkeletonContent(numberOfHeaders);
        repeatAndAppendSkeletonContent(tableId, skeletonLoaderContent, paginate.pageLimit || 0);

        const url = `${apiURL}tasks`;
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

        renderTasksList(data.tasks || []);

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        tbody.innerHTML = renderNoResponseCode({ colspan: numberOfHeaders });
        console.error(error);
    }
}

async function deleteTask(taskID) {

    if (!taskID) {
        throw new Error("Invalid task ID, Please try Again");
    }

    try {

        // Show a confirmation alert
        const confirmation = await Swal.fire({
            title: "Are you sure?",
            text: "Do you really want to delete task? This action cannot be undone.",
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
            title: "Deleting task ...",
            text: "Please wait while the task is being deleted.",
            icon: "info",
            showConfirmButton: false,
            allowOutsideClick: false,
            customClass: {
                popup: 'small-swal',
            },
        });

        const url = `${apiURL}/tasks/delete/${taskID}`;

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

            fetchTasksList();
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
    fetchTasksList();
});
function handlePagination(action) {
    paginate.paginate(action); // Update current page based on the action
    fetchTasksList(); // Fetch records
}

function filterContacts() {
    paginate.currentPage = 1;
    fetchTasksList();
}

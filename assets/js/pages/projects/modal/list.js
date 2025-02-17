// Set Up Paginate
const projectModalListPagination = new Pagination({
    currentPageId: 'project-listing-modal-current-page',
    totalPagesId: 'project-listing-modal-total-pages',
    pageOfPageId: 'project-listing-modal-page-of-pages',
    recordsRangeId: 'project-listing-modal-range-of-records'
});
projectModalListPagination.pageLimit = 10;

var projectListingModal = new bootstrap.Modal(document.getElementById("projectListingModal"), {
    keyboard: false,        // Disable closing on escape key
    backdrop: 'static'      // Disable closing when clicking outside the modal
});

const listContainer = document.getElementById("project-list-container");

function renderNoDataCode() {
    return ``;
}

function openProjectListingModal() {
    projectListingModal.show();
    fetchProjectsForModals();
}

function closeProjectListingModal() {

}
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
function renderProjectListItem(project) {
    const details = {
        id: project?.id,
        title: project?.project_name,
    };

    return `
        <div class="col-md-12 cursor-pointer mb-3" 
             onclick='setProject(${JSON.stringify(details)})'>
            <div class="border-bottom border-light">
                <div class="w-100 d-flex align-items-center justify-content-between mb-2">
                    <div class="flex-1">
                        <h5>${project?.project_name}</h5>
                        <p class="mb-0 fs-9 fw-bold text-primary">
                            <small>${project?.project_code}</small>
                        </p>
                    </div>
                    <div class="d-flex gap-1">
                        <small class="badge border border-${projectStatus[project?.status]} text-${projectStatus[project?.status]}">${project?.status}</small>
                        <small class="badge border border-${priorityStatus[project?.priority]} text-${priorityStatus[project?.priority]}">${project?.priority}</small>
                    </div>
                </div>
                <p class="fs-9 mb-1 line-clamp-2">
                    ${project?.description}
                </p>
                <p>
                    <small class="fw-bold">
                        Duration: ${formatAppDate(project?.start_date)} - ${formatAppDate(project?.end_date)}
                    </small>
                </p>
            </div>
        </div>`;
}



function renderProjectsList(projects) {
    if (!projects) {
        listContainer.innerHTML = renderNoDataCode();
        return;
    }
    let content = '';
    if (projects && projects.length > 0) {
        projects.forEach(project => content += renderProjectListItem(project));
        listContainer.innerHTML = content;
    } else {
        listContainer.innerHTML = renderNoDataCode();
    }


}

async function fetchProjectsForModals() {
    try {
        // Check token exist
        const authToken = validateUserAuthToken();
        if (!authToken) return;

        // Set loader to the screen 
        // const skeletonLoaderContent = commonSkeletonContent(numberOfHeaders);
        // repeatAndAppendSkeletonContent(tableId, skeletonLoaderContent, projectModalListPagination.pageLimit || 0);

        const url = `${apiURL}projects`;
        const filters = filterCriterias([]);

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                limit: projectModalListPagination.pageLimit,
                currentPage: projectModalListPagination.currentPage,
                filters: filters
            })
        });

        if (!response.ok) {
            throw new Error('Failed to fetch data');
        }

        const data = await response.json();
        projectModalListPagination.totalPages = parseFloat(data?.pagination?.total_pages) || 0;
        projectModalListPagination.totalRecords = parseFloat(data?.pagination?.total_records) || 0;

        renderProjectsList(data?.projects || {});


    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        console.error(error);
    }
}
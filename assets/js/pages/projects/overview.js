
async function fetchProjectDetail(projectID) {

    if (!projectID) {
        toasterNotification({ type: 'error', message: 'Invalid Project ID' });
        return;
    }


    const url = `${apiURL}projects/${projectID}`;

    const authToken = validateUserAuthToken();
    if (!authToken) return;


    try {

        fullPageLoader.classList.toggle("d-none");
        // Fetch product data from the API
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },

        });

        // Parse the JSON response
        const data = await response.json();

        // Check if the API response contains an error
        if (!response.ok || data.status === 'error') {
            const errorMessage = data.message || `Error: ${response.status} ${response.statusText}`;
            throw new Error(errorMessage);
        }

        displayProjectDetails(data.data);
    } catch (error) {
        // Show error notification
        toasterNotification({ type: 'error', message: 'Error: ' + error.message });
        console.error(error);

    } finally {
        fullPageLoader.classList.toggle("d-none");
    }
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
function displayProjectDetails(details) {
    if (!details) return;

    // Show Project Details
    document.getElementById("project_name").innerHTML = `${details?.project_name}`;
    let priorityContainer = document.getElementById("status-priority-container");
    if (priorityContainer) {
        priorityContainer.innerHTML = `
        <p class="mb-0"><small class="px-4 py-1 rounded fw-bold border border-${projectStatus[details.status]} text-${projectStatus[details.status]}">${capitalizeWords(details.status)}</small>
        </p>
        <p class="mb-0"><small class="px-4 py-1 rounded fw-bold border border-${priorityStatus[details.priority]} text-${priorityStatus[details.priority]}">${capitalizeWords(details.priority)}</small>
        `;
    }

    showProjectDuration(details);

}

function showProjectDuration(details) {
    const startDateElement = document.getElementById("start_date");
    const endDateElement = document.getElementById("end_date");

    const currentDate = new Date();
    const startDate = new Date(details?.start_date);
    const endDate = new Date(details?.end_date);

    // Start Date Logic
    if (startDate > currentDate) {
        startDateElement.innerHTML = `Start Date: ${formatAppDate(details?.start_date)}`;
    } else {
        startDateElement.innerHTML = `Started On: ${formatAppDate(details?.start_date)}`;
    }

    // End Date Logic
    if (endDate < currentDate && details?.status !== "completed") {
        endDateElement.innerHTML = `End Date: <span class="text-danger">${formatAppDate(details?.end_date)}</span>`;
    } else {
        endDateElement.innerHTML = `End Date: ${formatAppDate(details?.end_date)}`;
        endDateElement.classList.remove("text-danger");
    }
}

// Edit Action code goes here
document.addEventListener('DOMContentLoaded', () => {
    const url = new URL(window.location.href);
    const searchParams = new URLSearchParams(url.search);
    const urlSegments = url.pathname.split('/').filter(segment => segment);
    const projectID = urlSegments[urlSegments.length - 2];
    fetchProjectDetail(projectID);
});
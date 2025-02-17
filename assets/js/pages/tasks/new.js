var taskAttachmentsModal = new bootstrap.Modal(document.getElementById("taskAttachmentsModal"), {
    keyboard: false,        // Disable closing on escape key
    backdrop: 'static'      // Disable closing when clicking outside the modal
});

const fileCountContainer = document.getElementById("see-attached-files");
const fileListContainer = document.getElementById("attachment-list-container");

async function submitForm(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);


    // Set Loading Animation on button
    const submitBtn = document.getElementById("submit-btn");
    let buttonText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = `Saving ...`;

    // Hide Error
    hideErrors();
    try {
        // Retrieve the auth_token from cookies
        const authToken = validateUserAuthToken();
        if (!authToken) return;

        const userid = document.getElementById("id").value;
        let url = `${apiURL}tasks/`;
        if (userid)
            url += `update/${userid}`
        else
            url += 'new'
        // Fetch API with Bearer token in Authorization header
        const response = await fetch(url, {
            method: 'POST', // or POST, depending on the API endpoint
            headers: {
                'Authorization': `Bearer ${authToken}`
            },
            body: formData
        });


        // Check if the response is OK (status 200-299)
        if (response.ok) {
            const data = await response.json();
            toasterNotification({ type: 'success', message: data?.message || 'Record Saved Successfully' });
            if (data?.type === 'insert')
                clearForm();
        } else {
            const errorData = await response.json();
            if (errorData.status === 422) {
                showErrors(errorData.validation_errors ?? []);
            } else {
                toasterNotification({ type: 'error', message: errorData.message ?? 'Internal Server Error' });
            }
        }
    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed:' + error });
        console.error(error);

    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = buttonText;
    }
}

function openFileListModal() {
    taskAttachmentsModal.show();
}



function clearForm() {
    const form = document.getElementById("form")
    form?.reset();
    document.getElementById("id").value = '';
    document.getElementById("project_id").value = '';
    document.getElementById("assigned_to").value = '';

    // file hide codes
    fileCountContainer.innerHTML = '';
    fileListContainer.innerHTML = '';
}

async function fetchTasksDetailsForEdit(taskID) {
    const url = `${apiURL}tasks/${taskID}`;

    const authToken = validateUserAuthToken();
    if (!authToken) return;

    try {

        fullPageLoader.classList.toggle("d-none");
        // Fetch product data from the API
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`
            }
        });

        // Parse the JSON response
        const data = await response.json();

        // Check if the API response contains an error
        if (!response.ok || data.status === 'error') {
            const errorMessage = data.message || `Error: ${response.status} ${response.statusText}`;
            throw new Error(errorMessage);
        }

        displayUserInfo(data.data);
        // Fetch tasks and set it to selected role
    } catch (error) {
        // Show error notification
        toasterNotification({ type: 'error', message: 'Error: ' + error.message });
        console.error(error);

    } finally {
        fullPageLoader.classList.toggle("d-none");
    }
}
function displayUserInfo(data) {
    if (!data) return;

    if (Object.keys(data).length > 0) {
        populateFormFields(data);
    }

    // Show uploaded files

    const attachedFiles = data?.attachments || '';
    if (attachedFiles) {
        const files = JSON.parse(attachedFiles);
        fileCountContainer.innerHTML = `${files?.length || 0} file(s) attached. <span class="text-primary" >Click to see attached files</span>`;

        let content = `<h6 class="fw-normal mb-4">Total <span class="fw-bold">${files?.length || 0} File(s)</span> has been attached so far. <span class="text-warning">Click on file name to open file.</span></h6>
                                    <div class="col-md-12">
                                        <ul>`;
        if (files?.length > 0) {
            files.forEach(file => {
                content += `<li class="mb-2">
                                <a target="_blank" href="${baseURL}uploads/attachments/${file}" class="fs-9">
                                    <i class="fa-regular fa-file-lines"></i> ${file}
                                </a>
                            </li>`;
            });
        } else {
            content += `<li class="mb-2">No files has been attached under this task.</li>`;
        }

        content += `</ul>
                </div>`;

        fileListContainer.innerHTML = content;
    }
}


async function startOverNew() {
    try {

        // Show a confirmation alert
        const confirmation = await Swal.fire({
            title: "Are you sure?",
            text: "Do you really want to start new action? This action cannot be undone.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, Start New",
            cancelButtonText: "Cancel",
            customClass: {
                popup: 'small-swal',
                confirmButton: 'swal-confirm-btn',
                cancelButton: 'swal-cancel-btn',
            },
        });

        if (!confirmation.isConfirmed) return;

        Swal.close();
        window.location = 'tasks/new';

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        Swal.close();
    }
}

function clearProjectDetails() {
    document.getElementById("project_id").value = '';
    document.getElementById("project_name").value = '';
}

function setProject(details) {
    // Close Modal
    projectListingModal.hide();
    document.getElementById("project_id").value = details?.id;
    document.getElementById("project_name").value = details?.title;
}


function setUser(details) {
    // Close Modal
    userListingModal.hide();
    document.getElementById("assigned_to").value = details?.id;
    document.getElementById("assigned_to_name").value = details?.full_name;
}
function clearUserDetails() {
    document.getElementById("assigned_to").value = '';
    document.getElementById("assigned_to_name").value = '';
}

function assignToSelf() {
    let userDetails = null;
    if (window.loggedInUser)
        userDetails = JSON.parse(atob(window.loggedInUser));

    document.getElementById("assigned_to").value = userDetails?.userid || '';
    document.getElementById("assigned_to_name").value = userDetails?.username || '';

}


// Fetch Assigned To
function openUserListingModalFromTask() {
    openUserListingModal({ role_name: 'Developer' });
}

// Edit Action code goes here
document.addEventListener('DOMContentLoaded', () => {
    const url = new URL(window.location.href);
    const searchParams = new URLSearchParams(url.search);
    const urlSegments = url.pathname.split('/').filter(segment => segment);
    const taskID = urlSegments[urlSegments.length - 1];
    if (searchParams.get('action') === 'edit') {
        fetchTasksDetailsForEdit(taskID);
    }

    // const descriptionEditor = new CKEditorInstance('description', { placeholder: 'Write your task description here ...' });
    // descriptionEditor.initialize();


});
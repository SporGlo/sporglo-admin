async function logoutAction() {

    const authToken = getCookie("token_auth");
    
    if (!authToken) return window.location = `${BaseUrl}login`;

    // Show the SweetAlert confirmation dialog
    const result = await Swal.fire({
        title: "Are you sure?",
        text: "Do you want to log out?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, log me out",
        cancelButtonText: "No, stay logged in",
        reverseButtons: true,
        buttonsStyling: false,
        customClass: {
            confirmButton: "btn btn-danger",
            cancelButton: "btn btn-secondary me-2"
        }
    });

    // If user confirms the logout
    if (result.isConfirmed) {
        // Show loading animation
        Swal.fire({
            title: "Signing out...",
            html: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
            allowOutsideClick: false,
            showConfirmButton: false
        });

        try {
            // Send a logout request to the backend with the auth token
            const response = await fetch("api/v1/auth/logout", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": `Bearer ${authToken}` // Include the auth token
                },
                body: JSON.stringify({ action: "logout" })
            });

            const result = await response.json();

            // If the session is successfully destroyed
            if (response.ok && result.success) {
                // Redirect to login page
                window.location.href = `${BaseUrl}login`;
            } else {
                // Handle failure (optional)
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Something went wrong! Could not log you out.",
                    confirmButtonText: "OK"
                });
            }
        } catch (error) {
            // Handle error if the request fails
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "There was an issue connecting to the server.",
                confirmButtonText: "OK"
            });
        }
    }
}

// async function fetchProjectsListForSidebar() {
//     const sidebarProjectListContainer = document.getElementById("sidebarProjectListContainer");
//     try {
//         // Check token exist
//         const authToken = validateUserAuthToken();
//         if (!authToken) return;

//         // Set loader to the screen 
//         sidebarProjectListContainer.innerHTML = `<div class="text-center my-2">
//                                                     <i class="fa-solid fa-spinner fs-8 fa-spin"></i>
//                                                 </div>`;
//         const url = `${apiURL}projects`;
//         const filters = filterCriterias([]);

//         const response = await fetch(url, {
//             method: 'POST',
//             headers: {
//                 'Authorization': `Bearer ${authToken}`,
//                 'Content-Type': 'application/json'
//             },
//             body: JSON.stringify({
//                 filters: filters
//             })
//         });

//         if (!response.ok) {
//             throw new Error('Failed to fetch data');
//         }

//         const data = await response.json();
//         let content = '';
//         if (data?.projects && data?.projects?.length > 0) {
//             data?.projects?.forEach((project) => {
//                 content += `<div class="nav-link  rounded-0 py-0">
//                             <a href="" class="line-clamp-1 mb-0 text-muted">
//                                 <i class="fa-solid fa-paperclip me-1"></i>
//                                 <span class="fw-normal fs-19">${project?.project_name}</span>
//                             </a>
//                         </div>`
//             });
//         } else {

//         }
//         sidebarProjectListContainer.innerHTML = content;

//     } catch (error) {
//         toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
//         console.error(error);
//         sidebarProjectListContainer.innerHTML = '';
//     }
// }

// fetchProjectsListForSidebar();

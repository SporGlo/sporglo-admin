async function SubmitStaffUserType(e) {
    e.preventDefault();
    // Get Target Form
    const form = document.getElementById('staffusertypeForm');
    ;
    const formData = new FormData(form);
    const redirectUrl = `${BaseUrl}staff`;


    // Set Loading Animation on button
    const submitBtn = document.getElementById("submit-btn");
    let buttonText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = `Adding User Type ...`;

// console.log(formData);
// return;

    // Hide Error
    hideErrors();
    try {
        // Check token exist
        const authToken = validateUserAuthToken();
        if (!authToken) return;

        const sportID = document.getElementById("id").value;
        let url = getSubmitAPI({ resource: "staff", id: sportID })

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
            toasterNotification({ type: 'success', message: data?.message });
            window.location.href = redirectUrl; // Replace 'redirectUrl' with the desired URL.
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
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = buttonText;
    }
}

async function fetchStaffUserType(positionID) {
    const apiUrl = `${BaseUrl}staff/details/${positionID}`;
    const authToken = getCookie('token_auth');
    if (!authToken) {
        toasterNotification({
            type: 'error',
            message: "Authorization token is missing. Please login again to make an API request."
        });
        return;
    }
    // Show loader


    try {

        // fullPageLoader.classList.toggle("d-none");
        // Fetch product data from the API
        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ positionID })
        });

        // Parse the JSON response
        const data = await response.json();

        // Check if the API response contains an error
        if (!response.ok || data.status === 'error') {
            const errorMessage = data.message || `Error: ${response.status} ${response.statusText}`;
            throw new Error(errorMessage);
        }

        displayStaffInfo(data.data);


    } catch (error) {
        // Show error notification
        toasterNotification({ type: 'error', message: 'Error: ' + error.message });
    } finally {
        // fullPageLoader.classList.toggle("d-none");
    }
}



function displayStaffInfo(data) {
    
    if (!data) return;
    
    const {...userTypeDetail } = data;

    if (Object.keys(userTypeDetail).length > 0) {
        populateFormFields(userTypeDetail);
    }

   
}

document.addEventListener('DOMContentLoaded', () => {
    fetchAndPopulateSportList();
    const url = new URL(window.location.href);
    // Get all search parameters
    const searchParams = new URLSearchParams(url.search);
    // Get all URL segments
    const urlSegments = url.pathname.split('/').filter(segment => segment);
    const positionID = urlSegments[urlSegments.length - 1];
    // Fetch product details if action is edit and id is available
    if (searchParams.get('action') === 'edit') {
        // Your code to fetch product details
        fetchStaffUserType(positionID);
    } 
    // Fetch categories
});




async function fetchAndPopulateSportList() {
    try {
        // Validate the authentication token
        const authToken = validateUserAuthToken();
        if (!authToken) return;

        // Define the API URL
        const url = `${ApiUrl}sports`;
        const filters = filterCriterias([]); // Update filters as needed

        // Fetch product data
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
            
                filters: filters
            })
        });

        if (!response.ok) {
            throw new Error('Failed to fetch Sports data');
        }

        const data = await response.json();

        // Get the select element by ID
        const selectId = 'sport_id';

        const selectElement = document.getElementById(selectId);

        if (!selectElement) {
            throw new Error(`Select element with ID '${selectId}' not found.`);
        }

        // Clear existing options
        selectElement.innerHTML = '<option value="">Select Sport Name</option>';

        // Populate the select element with product options
        (data.sports || []).forEach(sport => {
            const option = document.createElement('option');
            option.value = sport.id; // Assuming `id` is the Sport's unique identifier
            option.textContent = sport.sport_name; // Assuming `name` is the Sport's display text
            selectElement.appendChild(option);
        });

    } catch (error) {
        toasterNotification({ type: 'error', message: 'Request failed: ' + error.message });
        console.error(error);
    }
}
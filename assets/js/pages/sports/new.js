async function SubmitSport(e) {
    e.preventDefault();
    // Get Target Form
    const form = document.getElementById('sportsForm');
    ;
    const formData = new FormData(form);
    const redirectUrl = `${BaseUrl}/sports/index`;


    // Set Loading Animation on button
    const submitBtn = document.getElementById("submit-btn");
    let buttonText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = `Adding Sports ...`;

// console.log(formData);
// return;

    // Hide Error
    hideErrors();
    try {
        // Check token exist
        const authToken = validateUserAuthToken();
        if (!authToken) return;

        const sportID = document.getElementById("id").value;
        let url = getSubmitAPI({ resource: "sports", id: sportID })

        // Fetch API with Bearer token in Authorization header
        const response = await fetch(url, {
            method: 'POST', // or POST, depending on the API endpoint
            headers: {
                'Authorization': `Bearer ${authToken}`,

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

async function fetchSports(sportID) {
    const apiUrl = `${BaseUrl}sports/details/${sportID}`;
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
            body: JSON.stringify({ sportID })
        });

        // Parse the JSON response
        const data = await response.json();

        // Check if the API response contains an error
        if (!response.ok || data.status === 'error') {
            const errorMessage = data.message || `Error: ${response.status} ${response.statusText}`;
            throw new Error(errorMessage);
        }

    
        displaySportsInfo(data.data);

        // Show Product Files attached
        if (data?.data?.sports?.cover_image) {
            uploadedFiles = JSON.parse(data?.data?.sports?.cover_image) || []
            console.log('uploadedFiles',uploadedFiles);
            

        }

    } catch (error) {
        // Show error notification
        toasterNotification({ type: 'error', message: 'Error: ' + error.message });
    } finally {
        // fullPageLoader.classList.toggle("d-none");
    }
}



function displaySportsInfo(data) {
    
    if (!data) return;
    
    const {...sportsDetail } = data;

    if (Object.keys(sportsDetail).length > 0) {
        populateFormFields(sportsDetail);
    }

   
}

document.addEventListener('DOMContentLoaded', () => {

    const url = new URL(window.location.href);
    // Get all search parameters
    const searchParams = new URLSearchParams(url.search);
    // Get all URL segments
    const urlSegments = url.pathname.split('/').filter(segment => segment);
    const sportID = urlSegments[urlSegments.length - 1];
    // Fetch product details if action is edit and id is available
    if (searchParams.get('action') === 'edit') {
        // Your code to fetch product details
        fetchSports(sportID);
    } 
    // Fetch categories
});


function previewImage(input, previewId) {
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById(previewId);
            preview.src = e.target.result;
            preview.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }
}

document.getElementById('cover_image').addEventListener('change', function() {
    previewImage(this, 'coverPreview');
});

document.getElementById('category_image').addEventListener('change', function() {
    previewImage(this, 'categoryPreview');
});
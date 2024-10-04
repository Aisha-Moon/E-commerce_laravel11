<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-10 center-screen">
            <div class="card animated fadeIn w-100 p-3">
                <div class="card-body">
                    <h4>Sign Up</h4>
                    <hr/>
                    <div class="container-fluid m-0 p-0">
                        <div class="row m-0 p-0">
                            <div class="col-md-4 p-2">
                                <label>Email Address</label>
                                <input id="email" placeholder="User Email" class="form-control" type="email"/>
                            </div>
                            <div class="col-md-4 p-2">
                                <label>First Name</label>
                                <input id="firstName" placeholder="First Name" class="form-control" type="text"/>
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Last Name</label>
                                <input id="lastName" placeholder="Last Name" class="form-control" type="text"/>
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Mobile Number</label>
                                <input id="mobile" placeholder="Mobile" class="form-control" type="mobile"/>
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Password</label>
                                <input id="password" placeholder="User Password" class="form-control" type="password"/>
                            </div>
                        </div>
                        <div class="row m-0 p-0">
                            <div class="col-md-4 p-2">
                                <button onclick="onRegistration()" class="btn mt-3 w-100  bg-gradient-primary">Complete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    async function onRegistration() {
        // Retrieve the input values
        let email = document.getElementById('email').value;
        let firstName = document.getElementById('firstName').value;
        let lastName = document.getElementById('lastName').value;
        let mobile = document.getElementById('mobile').value;
        let password = document.getElementById('password').value;

        // Input validation checks with error messages
        if (!email) {
            errorToast('Email is required');
            return; // Stop execution if validation fails
        }
        if (!firstName) {
            errorToast('First Name is required');
            return;
        }
        if (!lastName) {
            errorToast('Last Name is required');
            return;
        }
        if (!mobile) {
            errorToast('Mobile is required');
            return;
        }
        if (!password) {
            errorToast('Password is required');
            return;
        }

        // Show the loader before sending the request
        showLoader();
        try {
            // Send the POST request to register the user
            let res = await axios.post("/user-registration", {
                email: email,
                firstName: firstName,
                lastName: lastName,
                mobile: mobile,
                password: password
            });

            // Hide the loader after receiving the response
            hideLoader();

            // Check if the response is successful and display appropriate message
            if (res.status === 200 && res.data['status'] === 'success') {
                successToast(res.data['message']);
                setTimeout(() => {
                    window.location.href = "/userLogin"; // Redirect to login page
                }, 1000);
            } else {
                errorToast(res.data['message']);
            }
        } catch (err) {
            hideLoader(); // Hide loader in case of an error

            // Log the error and display an appropriate message
            console.error(err);
            if (err.response && err.response.data && err.response.data.message) {
                errorToast(err.response.data.message);
            } else {
                errorToast("An error occurred while registering. Please try again.");
            }
        }
    }
</script>

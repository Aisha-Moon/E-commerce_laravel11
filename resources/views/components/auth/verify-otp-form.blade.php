<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6 center-screen">
            <div class="card animated fadeIn w-90  p-4">
                <div class="card-body">
                    <h4>ENTER OTP CODE</h4>
                    <br/>
                    <label>4 Digit Code Here</label>
                    <input id="otp" placeholder="Code" class="form-control" type="text"/>
                    <br/>
                    <button onclick="VerifyOtp()"  class="btn w-100 float-end bg-gradient-primary">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function VerifyOtp() {
        let otp = document.getElementById('otp').value;
        if (otp.length === 0) {
            errorToast('OTP is required');
            return;
        } else if (otp.length !== 4) {
            errorToast('Invalid OTP');
            return;
        }

        let email = sessionStorage.getItem('email');
        if (!email) {
            errorToast('Email is not available. Please go back and enter your email again.');
            return;
        }

        try {
            showLoader();
            let res = await axios.post("/verify-otp", {
                'otp': otp,
                'email': email
            });
            hideLoader();

            if (res.status === 200 && res.data['status'] === 'success') {
                successToast(res.data['message']);
                sessionStorage.removeItem('email');
                setTimeout(() => {
                    window.location.href = "/resetPassword";
                }, 1000);
            } else {
                errorToast(res.data['message']);
            }
        } catch (error) {
            hideLoader();
            errorToast('An error occurred while verifying the OTP.');
            console.error('Error during OTP verification:', error);
        }
    }
</script>

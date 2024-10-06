<div class="modal animated zoomIn" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Customer</h5>
                </div>
                <div class="modal-body">
                    <form id="save-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <label class="form-label">Customer Name *</label>
                                <input type="text" class="form-control" id="customerName">
                                <label class="form-label">Customer Email *</label>
                                <input type="text" class="form-control" id="customerEmail">
                                <label class="form-label">Customer Mobile *</label>
                                <input type="text" class="form-control" id="customerMobile">
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                    <button onclick="Save()" id="save-btn" class="btn bg-gradient-success" >Save</button>
                </div>
            </div>
    </div>
</div>

<script>
    async function Save() {
        let Cname = document.getElementById('customerName').value;
        let Cemail = document.getElementById('customerEmail').value;
        let Cmobile = document.getElementById('customerMobile').value;

        if (Cname.length == 0) {
            errorToast('Customer Name is required');
            return;
        } else if (Cemail.length == 0) {
            errorToast('Customer Email is required');
            return;  // Add a return statement here to prevent further execution
        } else if (Cmobile.length == 0) {
            errorToast('Customer Mobile is required');
            return;  // Add a return statement here to prevent further execution
        } else {
            document.getElementById('modal-close').click();
            showLoader();

            let res = await axios.post('/customers', {
                name: Cname,
                email: Cemail,
                mobile: Cmobile
            });

            hideLoader();

            if (res.status === 201) {
                successToast(res.data['message']);
                document.getElementById('save-form').reset();
                await getList();  // Make sure getList() is defined elsewhere in your script
            } else {
                errorToast(res.data['message']);
            }
        }
    }
</script>


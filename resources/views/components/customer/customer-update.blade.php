<div class="modal animated zoomIn" id="update-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Customer</h5>
            </div>
            <div class="modal-body">
                <form id="update-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <label class="form-label">Customer Name *</label>
                                <input type="text" class="form-control" id="customerNameUpdate">

                                <label class="form-label mt-3">Customer Email *</label>
                                <input type="text" class="form-control" id="customerEmailUpdate">

                                <label class="form-label mt-3">Customer Mobile *</label>
                                <input type="text" class="form-control" id="customerMobileUpdate">

                                <input type="text" class="d-" id="updateID">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="update-modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                <button onclick="Update()" id="update-btn" class="btn bg-gradient-success" >Update</button>
            </div>
        </div>
    </div>
</div>


<script>
    async function fillUpdateForm(){
        let id= document.getElementById('updateID').value;
      
        
        showLoader();

        let res = await axios.request({
            url: `customers/${id}`,  
            method: 'GET',          
            data: { id: id }           
        });
        hideLoader();
     
        if(res.status==200){
               
            document.getElementById('customerNameUpdate').value=res.data.customer.name;
            document.getElementById('customerEmailUpdate').value=res.data.customer.email;
            document.getElementById('customerMobileUpdate').value=res.data.customer.mobile;

        }
    
    
       
    }
    async function Update() {


        let Cname = document.getElementById('customerNameUpdate').value;
        let Cemail = document.getElementById('customerEmailUpdate').value;
        let Cmobile = document.getElementById('customerMobileUpdate').value;


        if (Cname.length == 0) {
            errorToast('Customer Name is required');
            return;
        } else if (Cemail.length == 0) {
            errorToast('Customer Email is required');
            return;  
        } else if (Cmobile.length == 0) {
            errorToast('Customer Mobile is required');
            return;  
        } else {
            document.getElementById('update-modal-close').click();
            showLoader();
            let id = document.getElementById('updateID').value;
            let res = await axios.request({
                url: `customers/${id}`,  
                method: 'PUT',       
                data: {
                    name: Cname,
                    email: Cemail,
                    mobile: Cmobile
                }
            });
            hideLoader();
            if (res.status === 200) {
                successToast(res.data['message']);
                await getList();
            } else {
                errorToast('Request Failed');
            }
        }


    }
</script>
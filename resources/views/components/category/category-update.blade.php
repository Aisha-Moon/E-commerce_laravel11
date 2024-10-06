<div class="modal animated zoomIn" id="update-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-md modal-dialog-centered">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">Update Category</h5>
             </div>
             <div class="modal-body">
                 <form id="update-form">
                     <div class="container">
                         <div class="row">
                             <div class="col-12 p-1">
                                 <label class="form-label">Category Name *</label>
                                 <input type="text" class="form-control" id="categoryNameUpdate">
                                 <input class="d-none" id="updateID">
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
     async function FillUpUpdateForm(id){
         
         $('#updateID').val(id);

       showLoader();
     
        let res = await axios.request({
            url: `categories/${id}`,  
            method: 'GET',          
            data: { id: id }           
        });
        hideLoader();
        document.getElementById('categoryNameUpdate').value=res.data['name'];

        
     }

     async function Update() {
    let categoryNameUpdate = document.getElementById('categoryNameUpdate').value;
    let updateID = document.getElementById('updateID').value;

    if (categoryNameUpdate.length === 0) {
        errorToast('Category Name is required');
    } else {
        document.getElementById('update-modal-close').click();
        showLoader();

        try {
            let res = await axios.request({
                url: `categories/${updateID}`,  
                method: 'PUT',
                data: { name: categoryNameUpdate } 
            });

            hideLoader();

            if (res.status === 200) {
                successToast(res.data['message']); 
                await getList();
            } else {
                errorToast('Request Failed');
            }
        } catch (error) {
            hideLoader();
            console.error('Update Error:', error);
            errorToast('An error occurred while updating the category.');
        }
    }
}

 </script>
<div class="modal animated zoomIn" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Product</h5>
                </div>
                <div class="modal-body">
                    <form id="save-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">

                                <label class="form-label">Category</label>
                                <select type="text" class="form-control form-select" id="productCategory">
                                    <option value="">Select Category</option>
                                </select>

                                <label class="form-label mt-2">Name</label>
                                <input type="text" class="form-control" id="productName">

                                <label class="form-label mt-2">Price</label>
                                <input type="text" class="form-control" id="productPrice">

                                <label class="form-label mt-2">Unit</label>
                                <input type="text" class="form-control" id="productUnit">

                                <br/>
                                <img class="w-15" id="newImg" src="{{asset('images/default.jpg')}}"/>
                                <br/>

                                <label class="form-label">Image</label>
                                <input oninput="newImg.src=window.URL.createObjectURL(this.files[0])" type="file" class="form-control" id="productImg">

                            </div>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="modal-close" class="btn bg-gradient-primary mx-2" data-bs-dismiss="modal" aria-label="Close">Close</button>
                    <button onclick="Save()" id="save-btn" class="btn bg-gradient-success" >Save</button>
                </div>
            </div>
    </div>
</div>


<script>
    FillUpCategory();
    async function FillUpCategory(){
        let res=await axios.get('/categories');

        res.data.forEach((item,index)=>{
            let option=`<option value="${item['id']}">${item['name']}</option>`;
            // document.getElementById('productCategory').innerHTML+=option;
            $('#productCategory').append(option);
        })
    }
    async function Save(){
    let category = document.getElementById('productCategory').value;
    let name = document.getElementById('productName').value;
    let price = document.getElementById('productPrice').value;
    let unit = document.getElementById('productUnit').value;
    let img = document.getElementById('productImg').files[0];

    if (category.length === 0) {
        errorToast('Category is required');
        return;
    } else if (name.length === 0) {
        errorToast('Name is required');
        return;
    } else if (price.length === 0) {
        errorToast('Price is required');
        return;
    } else if (unit.length === 0) {
        errorToast('Unit is required');
        return;
    } else if (!img) {
        errorToast('Image is required');
        return;
    } else {
        document.getElementById('modal-close').click();

        // Corrected this line
        let formData = new FormData();
        formData.append('category_id', category);
        formData.append('name', name);
        formData.append('price', price);
        formData.append('unit', unit);
        formData.append('img_url', img);
        
        let config={
            headers:{
                'content-type':'multipart/form-data'
            }
        }

        showLoader();
        let res = await axios.post('/products', formData,config);
        hideLoader();

        if (res.status === 201) {
            successToast(res.data['message']);
            document.getElementById('save-form').reset();
            await getList();
        } else {
            errorToast(res.data['message']);
        }
    }
}

    
</script>
<div class="modal animated zoomIn" id="update-modal" aria-labelledby="exampleModalLabel" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Product</h5>
            </div>
            <div class="modal-body">
                <form id="update-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">

                                <label class="form-label">Category</label>
                                <select class="form-control form-select" id="productCategoryUpdate" type="text">
                                    <option value="">Select Category</option>
                                </select>

                                <label class="form-label mt-2">Name</label>
                                <input class="form-control" id="productNameUpdate" type="text">

                                <label class="form-label mt-2">Price</label>
                                <input class="form-control" id="productPriceUpdate" type="text">

                                <label class="form-label mt-2">Unit</label>
                                <input class="form-control" id="productUnitUpdate" type="text">
                                <br />
                                <img class="w-15" id="oldImg" src="{{ asset('images/default.jpg') }}" />
                                <br />
                                <label class="form-label mt-2">Image</label>
                                <input class="form-control" id="productImgUpdate" type="file" oninput="oldImg.src=window.URL.createObjectURL(this.files[0])">

                                <input class="d-none" id="updateID" type="text">
                                <input class="d-none" id="filePath" type="text">

                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button class="btn bg-gradient-primary" id="update-modal-close" data-bs-dismiss="modal" aria-label="Close">Close</button>
                <button class="btn bg-gradient-success" id="update-btn" onclick="update()">Update</button>
            </div>

        </div>
    </div>
</div>
<script>
    FillUpCategory();
    async function FillUpCategory() {
        let res = await axios.get("/categories")
        res.data.forEach(function(item, i) {
            let option = `<option value="${item['id']}">${item['name']}</option>`
            $("#productCategoryUpdate").append(option);
        })
    }

    async function FillUpUpdateForm(id, path) {
        document.getElementById('updateID').value = id;
        document.getElementById('filePath').value = path;
        document.getElementById('oldImg').src = path;

        showLoader();
        await FillUpCategory();

        let res = await axios.request({
            url: `products/${id}`,
            method: 'GET',

        });


        hideLoader();
        if (res.status == 200) {
            document.getElementById('productNameUpdate').value = res.data.name;
            document.getElementById('productPriceUpdate').value = res.data.price;
            document.getElementById('productUnitUpdate').value = res.data.unit;
            document.getElementById('productCategoryUpdate').value = res.data.category_id;
        }
    }
    async function update() {

        let productCategoryUpdate = document.getElementById('productCategoryUpdate').value;
        let productNameUpdate = document.getElementById('productNameUpdate').value;
        let productPriceUpdate = document.getElementById('productPriceUpdate').value;
        let productUnitUpdate = document.getElementById('productUnitUpdate').value;
        let updateID = document.getElementById('updateID').value;
        let filePath = document.getElementById('filePath').value;
        let productImgUpdate = document.getElementById('productImgUpdate').files[0];


        if (productCategoryUpdate.length === 0) {
            errorToast("Product Category Required !");
            return;
        } else if (productNameUpdate.length === 0) {
            errorToast("Product Name Required !");
            return;
        } else if (productPriceUpdate.length === 0) {
            errorToast("Product Price Required !");
            return;
        } else if (productUnitUpdate.length === 0) {
            errorToast("Product Unit Required !");
            return;
        }

        document.getElementById('update-modal-close').click();

        let formData = new FormData();

        if (productImgUpdate) {
            formData.append('img_url', productImgUpdate);
        }

        formData.append('_method', "PUT");
        formData.append('id', updateID);
        formData.append('name', productNameUpdate);
        formData.append('price', productPriceUpdate);
        formData.append('unit', productUnitUpdate);
        formData.append('category_id', productCategoryUpdate);
        formData.append('file_path', filePath);

        showLoader();

        let res = await axios.post(`/products/${updateID}`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

     

        hideLoader();

        if (res.status === 200) {
            successToast('Request completed');
            document.getElementById("update-form").reset();
            await getList();
        } else {
            errorToast("Request failed !");
        }
    }
</script>

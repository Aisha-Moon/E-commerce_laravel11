<div class="container-fluid">
    <div class="row">
    <div class="col-md-12 col-sm-12 col-lg-12">
        <div class="card px-5 py-5">
            <div class="row justify-content-between ">
                <div class="align-items-center col">
                    <h4>Product</h4>
                </div>
                <div class="align-items-center col">
                    <button data-bs-toggle="modal" data-bs-target="#create-modal" class="float-end btn m-0  bg-gradient-primary">Create</button>
                </div>
            </div>
            <hr class="bg-dark "/>
            <table class="table" id="tableData">
                <thead>
                <tr class="bg-light">
                    <th>SL</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Unit</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody id="tableList">

                </tbody>
            </table>
        </div>
    </div>
</div>
</div>



<script>
    getList();

    async function getList() {
        showLoader();
        try {
            let res = await axios.get('/products');
            hideLoader();

            let tableList = $('#tableList');
            let tableData = $('#tableData');

            tableData.DataTable().destroy();
            tableList.empty();

            res.data.forEach((item, index) => {
                let row = `<tr>
                    <td>${index + 1}</td>
                    <td><img src="${item['img_url']}" width="50" height="50"></td>
                    <td>${item['name']}</td>
                    <td>${item['price']}</td>
                    <td>${item['unit']}</td>
                    <td>
                        <button data-path="${item['img_url']}" data-id="${item['id']}" data-name="${item['name']}" class="btn bg-gradient-primary editBtn">Update</button>
                        <button data-path="${item['img_url']}" data-id="${item['id']}" data-bs-toggle="modal" class="btn bg-gradient-danger deleteBtn">Delete</button>
                    </td>
                </tr>`;
                tableList.append(row);
            });

            $('.editBtn').on('click', async function() {
                let id = $(this).data('id');
                let path = $(this).data('path');
                $('#updateID').val(id); // Ensure the ID matches your input
                await FillUpUpdateForm(id, path);
                $('#update-modal').modal('show');
            });

            $('.deleteBtn').on('click', function() {
                let id = $(this).data('id');
                let path = $(this).data('path');
               $('#delete-modal').modal('show');
                $('#deleteID').val(id);
                $('#deletePath').val(path);
            });

            new DataTable('#tableData', {
                order: [[0, "desc"]],
                lengthMenu: [5, 10, 15, 20]
            });

        } catch (error) {
            hideLoader();
            console.error('Error fetching categories:', error);
            alert('Failed to load categories.');
        }
    }
</script>

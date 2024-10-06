<div class="container-fluid">
    <div class="row">
    <div class="col-md-12 col-sm-12 col-lg-12">
        <div class="card px-5 py-5">
            <div class="row justify-content-between ">
                <div class="align-items-center col">
                    <h4>Customer</h4>
                </div>
                <div class="align-items-center col">
                    <button data-bs-toggle="modal" data-bs-target="#create-modal" class="float-end btn m-0 bg-gradient-primary">Create</button>
                </div>
            </div>
            <hr class="bg-dark "/>
            <table class="table" id="tableData">
                <thead>
                <tr class="bg-light">
                    <th>No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
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
    // Call the function when the page loads
    getList();

    // Define an asynchronous function to fetch the list of customers
    async function getList() {
        showLoader(); // Assuming this is a custom function to show a loading indicator

        try {
            let res = await axios.get('/customers');
            hideLoader(); // Assuming this hides the loading indicator

            let tableList = $('#tableList');
            let tableData = $('#tableData');

            // Destroy DataTable if it is already initialized
            if ($.fn.DataTable.isDataTable('#tableData')) {
                tableData.DataTable().destroy();
            }

            tableList.empty(); // Clear the existing table content

            // Loop through the response data and append each customer row
            res.data.forEach((item, index) => {
                let row = `<tr>
                    <td>${index + 1}</td>
                    <td>${item['name']}</td>
                    <td>${item['email']}</td>
                    <td>${item['mobile']}</td>
                    <td>
                        <button data-id="${item['id']}" data-name="${item['name']}" data-bs-toggle="modal"  class="btn bg-gradient-primary editBtn">Update</button>
                        <button data-id="${item['id']}" data-bs-toggle="modal" data-bs-target="#delete-modal" class="btn bg-gradient-danger deleteBtn">Delete</button>
                    </td>
                </tr>`;

                tableList.append(row);
            });

            // Add click events to edit and delete buttons
            $('.editBtn').on('click', async function () {
                let id = $(this).data('id');
                $('#updateID').val(id);
                await fillUpdateForm();  // Ensure `fillUpdateForm()` is defined and async
                $('#update-modal').modal('show');

            });

            $('.deleteBtn').on('click', function () {
                let id = $(this).data('id');
                $('#deleteId').val(id);  
            });

            // Initialize or reinitialize the DataTable
            new DataTable('#tableData', {
                order: [[0, "desc"]],
                lengthMenu: [5, 10, 15, 20]
            });

        } catch (error) {
            console.error("Error fetching data:", error);
            hideLoader(); // Hide the loader even if there is an error
        }
    }
</script>

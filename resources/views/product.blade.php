@extends('templates.app')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row d-flex justify-content-between">
            <div class="col">
                <h3 class="card-title">Data Barang</h3>
            </div>
            <div class="col">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addModal">
                    Tambah Produk
                </button>
                <a href="/download" target="_blank" class="btn btn-info float-right mr-2">Download Excel</a>
            </div>
        </div>

    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="tb_barang" class="table table-bordered table-hover display nowrap" style="width: 100%">
            <thead>
                <tr>
                    <th rowspan="2">#</th>
                    <th rowspan="2">Kode Produk</th>
                    <th rowspan="2">Gambar</th>
                    <th rowspan="2">Nama Produk</th>
                    <th rowspan="2">Harga</th>
                    <th colspan="2">Action</th>
                </tr>
                <tr>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th rowspan="2">#</th>
                    <th rowspan="2">Kode Produk</th>
                    <th rowspan="2">Gambar</th>
                    <th rowspan="2">Nama Produk</th>
                    <th rowspan="2">Harga</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
                <tr>
                    <th colspan="2">Action</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->

<!-- Add Modal -->
<!-- Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addForm" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="add_kode_produk">Kode Produk</label>
                        <input type="text" class="form-control" name="kode_produk" id="add_kode_produk">
                    </div>
                    <div class="form-group">
                        <label for="add_nama_produk">Nama Produk</label>
                        <input type="text" class="form-control" name="nama_produk" id="add_nama_produk">
                    </div>
                    <div class="form-group">
                        <label for="add_harga">Harga</label>
                        <input type="text" class="form-control" name="harga" id="add_harga">
                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="add_image" aria-describedby="add_image" data-image="add_image" onchange="showImage(this);">
                        <label class="custom-file-label" name="image" for="add_image">Choose file</label>
                        <img id="add_image_thumb" src="#" width="100" height="100" style="object-fit: contain;" />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitForm('addForm')">Submit</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="edit_kode_produk">Kode Produk</label>
                        <input type="text" class="form-control" name="kode_produk" id="edit_kode_produk" readonly>
                        <input type="hidden" name="produk_id" id="produk_id">
                    </div>
                    <div class="form-group">
                        <label for="edit_nama_produk">Nama Produk</label>
                        <input type="text" class="form-control" name="nama_produk" id="edit_nama_produk">
                    </div>
                    <div class="form-group">
                        <label for="edit_harga">Harga</label>
                        <input type="text" class="form-control" name="harga" id="edit_harga">
                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="edit_image" aria-describedby="edit_image" data-image="edit_image" onchange="showImage(this);">
                        <label class="custom-file-label" name="image" for="edit_image">Choose file</label>
                        <img id="edit_image_thumb" src="#" width="100" height="100" style="object-fit: contain;" />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitForm('editForm')">Update</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- DataTables  & Plugins -->
<script src="{{asset('adminlte/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
{{-- <script src="{{asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script> --}}

<script>
    const editUrl = (id) => {
        axios.get(`/api/product/${id}`)
            .then((response) => {
                let data = response.data.data;

                $('#produk_id').val(id)
                $('#edit_nama_produk').val(data.product_name)
                $('#edit_kode_produk').val(data.product_code)
                $('#edit_harga').val(data.price)
            });

        $('#editModal').modal();
    }

    const deleteUrl = (id) => {
        Swal.fire({
            title: 'Hapus Data',
            text: "Data yang terhapus tidak akan bisa dikembalikan",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Hapus!'
            }).then((result) => {
            if (result.isConfirmed) {
                axios.get('/api/product/delete/' + id)
                .then((response) => {
                    Swal.fire(
                    'Sukses',
                    'Berhasil Menghapus Data',
                    'success'
                    );

                    $('#tb_barang').dataTable().fnDestroy();
                    initDataTables();
                })
                .catch((error) => {
                    Swal.fire(
                        'Gagal',
                        'Gagal Hapus Data',
                        'warning'
                    )
                })
            }
        })
    }

    const submitForm = (form) => {
        if (form === 'addForm') {
            let data = {};
            let formData = new FormData();
            let image = document.getElementById("add_image").files[0]

            formData.append('image', image, 'gambarProduk');

            $.each($(`#${form}`).serializeArray(), (i, field) => {
                formData.append(field.name, field.value);
            });

            console.log(formData)
            console.log(data);

            axios.post('/api/product/add', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then((response) => {
                $(`#${form}`).closest('form').find("input").val("");

                $('#addModal').modal('hide');

                Swal.fire(
                    'Sukses',
                    response.data.message,
                    'success'
                );

                $("#tb_barang").dataTable().fnDestroy();
                initDataTables();
            }).catch((error) => {
                console.log(error);
                Swal.fire(
                    'Gagal',
                    'Gagal Tambah Produk. Error: ' + error.message,
                    'warning'
                );
            })
        } else if (form === 'editForm') {
            let data = {};
            let formData = new FormData();
            let image = document.getElementById("edit_image").files[0]
            let id = document.getElementById("produk_id").value;

            formData.append('image', image, 'gambarProduk');

            $.each($(`#${form}`).serializeArray(), (i, field) => {
                formData.append(field.name, field.value);
            });

            axios.post('/api/product/edit/' + id, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then((response) => {
                $(`#${form}`).closest('form').find("input").val("");

                $('#editModal').modal('hide');

                Swal.fire(
                    'Sukses',
                    'Berhasil Edit Produk',
                    'success'
                );

                $('#tb_barang').dataTable().fnDestroy();
                initDataTables();
            })
            .catch((error) => {
                Swal.fire(
                    'Gagal',
                    'Gagal Update Data. Error: ' + error.message,
                    'warning'
                );
            })
        }
    }

    const initDataTables = () => {

        $(function () {
            $('#tb_barang').DataTable({
                processing: true,
                serverSide: true,
                ajax: "/",
                scrollY: "500px",
                scrollX: true,
                scrollCollapse: true,
                fixedColumns: {
                    leftColumns: 1
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'product_code',
                        name: 'product_code'
                    },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'product_name',
                        name: 'product_name'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'btnEdit',
                        name: 'btnEdit',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'btnDelete',
                        name: 'btnDelete',
                        orderable: false,
                        searchable: false
                    }
                ]
            })
        })
    }

    const showImage = (input) => {
        var target = $(input).attr("data-image");
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(`#${target}_thumb`)
                    .attr('src', e.target.result)
                    .width(100)
                    .height(100);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    initDataTables();
    bsCustomFileInput.init();
</script>
@endpush

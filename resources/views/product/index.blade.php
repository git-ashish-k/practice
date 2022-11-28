@extends('layouts.app')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-12 table-responsive">
            @if(session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
            @endif
            <a href="{{ route('products.create') }}" class="btn btn-primary float-right mb-2">
                Add Product
            </a>
            <table class="table table-bordered product_datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Brand</th>
                        <th width="100px">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
</body>
@stop
<script type="text/javascript" src="https://code.jquery.com/jquery-1.7.1.min.js"></script>
<script type="text/javascript">
    $(function() {
        var table = $('.product_datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('products.index') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'price',
                    name: 'price'
                },
                {
                    data: 'brand',
                    name: 'Brand'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
        $(document).on('click', '.delete', function() {
            var id = $(this).data('id');
            var deleteConfirm = confirm("Are you sure?");
            if (deleteConfirm == true) {
                // AJAX request
                $.ajax({
                    url: "/product/" + id,
                    type: 'DELETE',
                    data: {
                        'brand': id,
                        '_token': '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        table.ajax.reload();
                    },
                    error: function(response) {
                        alert('Error Occured');
                    }
                });
            }

        });

    });
</script>
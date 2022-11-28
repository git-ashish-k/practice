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
            <a href="{{ route('users.create') }}" class="btn btn-primary float-right mb-2">
                Add User
            </a>
            <table class="table table-bordered user_datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
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
        var table = $('.user_datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('users.index') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'rolename',
                    name: 'Role'
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
                    url: "/user/" + id,
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
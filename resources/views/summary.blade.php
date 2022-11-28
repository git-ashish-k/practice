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
            <div onclick="show_data('products')" class="btn btn-primary float-right mb-2 ">
                Show Product
            </div>
            
            <div onclick="show_data('brands')" class="btn btn-primary float-right mb-2 mr-2">
                Show Brand 
            </div>
            <table class="table table-bordered datatable">
                <thead>
                    <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Brand<th>
                    <th>Action<th>
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
   $( document ).ready(function() {
        show_data('brands');
    });
    function show_data(type = "brands"){
       
        var products_arr = [{
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
                },];
                var brands_arr = [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'id',
                    name: 'price',
                    visible: false,
                },
                {
                    data: 'name',
                    name: 'Brand',
                    visible: false,
                },];
        if(type == "brands"){
            var columns = brands_arr;
            var url = "{{ route('brands.index')}}";
        }
        else{
            var columns = products_arr;
            var url = "{{ route('products.index')}}";
        }
        
        var table = $('.datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: url,
            columns: columns,
            destroy: true,
        });
        if(type == 'brands'){
            $('.datatable tr:first').html('<th>ID</th><th>Name</th>');
        }
        else{
            $('.datatable tr:first').html('<th>ID</th><th>Name</th><th>Price</th><th>Brand</th>');
        }
       

    }
    
    </script>


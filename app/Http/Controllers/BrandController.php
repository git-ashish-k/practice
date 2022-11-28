<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use DataTables;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $validator = null;
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Brand::select('id', 'name')->get();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<a href="/brand/' . $row->id . '/edit" class="edit btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>   <a href="javascript:void(0)" data-id="' . $row->id . '" class="edit btn btn-danger btn-sm delete"><i class="fa fa-trash-o"></i></a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('brand.index');
    }
    public function check($name)
    {
        $data = Brand::select('id', 'name')->where('name', $name)->get()->count();
        if ($data > 0) {
            return response()->json(['msg' => "The name already has been taken."], 500);
        } else {
            return response()->json(['msg' => ""], 200);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brand = new Brand();
        $action = 'brands.store';
        return view('brand.create', ['brand' => $brand, 'action' => $action, 'type' => 'post']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'  => 'required|string|unique:brands',
        ]);

        $data = $request->all();
        Brand::create($data);
        return redirect()->route('brands.index')
            ->with('success', 'Brand created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
        $action = 'brands.update';
        $action_id = $brand->id;
        return view('brand.create', ['brand' => $brand, 'action' => $action, 'type' => 'edit', 'action_id' => $action_id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|unique:brands,name,' . $brand->id . ',id'
        ]);
        $data = $request->all();
        $brand->update($data);
        session()->flash('success', 'Brand Updated Sucessfully');
        return true;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy($brand)
    {
        try {
            Product::where('brand_id', $brand)->delete();
            Brand::where('id', $brand)->delete();
            return true;
        } catch (Exception $e) {
            return response($e->getMessage(), 500);
        }
    }
}

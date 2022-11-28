<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Brand;
use DB;
use Illuminate\Support\Facades\Storage;
use DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::select('id', 'name', 'price', 'brand_id')->with(['Brand'])->get();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('brand', function ($row) {
                    return $row->Brand->name;
                })
                ->addColumn('action', function ($row) {
                    return '<a href="/product/' . $row->id . '/edit" class="edit btn btn-primary btn-sm"><i class="fa fa-edit"></i></a><a href="javascript:void(0)" data-id="' . $row->id . '" class="edit btn btn-danger btn-sm delete"><i class="fa fa-trash-o"></i></a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('product.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $product = new Product();
        $action = 'products.store';
        $brands     = new Brand();
        $brandslist = $brands->select("id", 'name')->pluck("name", "id");
        return view('product.create', ['product' => $product, 'brandlist' => $brandslist, 'action' => $action, 'type' => 'post']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'price' => 'required|integer|not_in:0|gt:0',
            'brand_id' => ['required'],
            'images' => ['required'],

        ]);
        $product = Product::create($request->all());
        if ($request->file('images')) {

            foreach ($request->file('images') as $photo) {
                $product->addMedia($photo)->toMediaCollection('product');
            }
        }
        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $product = Product::with(['media'])->find($product->id);
        $action = 'products.update';
        $action_id = $product->id;
        $brands     = new Brand();
        $brandslist = $brands->select("id", 'name')->pluck("name", "id");
        return view('product.create', ['product' => $product, 'brandlist' => $brandslist, 'action' => $action, 'type' => 'edit', 'action_id' => $action_id]);
    }
    public function destroyMedia($media)
    {
        try {
            $photo = DB::table('media')->where('id', $media)->first();
            Storage::disk('public')->delete($media . '/' . $photo->file_name);
            DB::table('media')->where('id', $media)->delete();
            return true;
        } catch (Exception $e) {
            return response($e->getMessage(), 500);
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'price' => 'required|numeric|between:0,9999.99',
            'brand_id' => ['required'],
        ]);

        $product = Product::with(['media'])->find($product->id);
        if (!$product->media) {
            $request->validate([
                'images' => ['required']
            ]);
        }
        if ($request->file('images')) {

            foreach ($request->file('images') as $photo) {
                $product->addMedia($photo)->toMediaCollection('product');
            }
        }
        $product->update($request->all());

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($product)
    {
        try {
            $product =  Product::with(['media'])->find($product);

            foreach ($product->media as $photo) {
                unlink($photo->getPath());
            }
            $product->media()->delete();
            $product->delete();
            return true;
        } catch (Exception $e) {
            return response($e->getMessage(), 500);
        }
    }
}

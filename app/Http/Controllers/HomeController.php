<?php

namespace App\Http\Controllers;

use App\Exports\ProdukExport;
use Illuminate\Http\Request;
use DataTables;
use Excel;
use DB;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            $data = DB::table('master.products')->get();

            return DataTables::of($data)
            ->addColumn('image', function($data) {
                // $img = ;

                return "<img src='". asset($data->image) ."' class='product-image' width='100px' height='100px'>";
            })
            ->addColumn('action', function($data){
                $btnEdit = "<button onclick='editUrl(" . $data->id  . ")' class='btn btn-warning btn-sm mx-2'>Edit</button>";
                $btnDelete = "<button onclick='deleteUrl(" . $data->id  . ")' class='btn btn-danger btn-sm mx-2'>Delete</button>";

                return $btnEdit . $btnDelete;
            })
            ->rawColumns(['image', 'action'])
            ->make(true);
        }

        return view('product');
    }

    public function dataProvide(Request $request) {
        $data = DB::table('master.products')->get();

        return DataTables::of($data)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            if ($request->hasFile('image')) {
                $file_name = 'thumbnail/thumbnail-' . $request->input('kode_produk') . '.' . $request->image->extension();
                $path = 'storage/' . $file_name;
                $upload = $request->image->storeAs('public', $file_name);
            } else {
                $path = 'image/Thumbnail.jpg';
            }

            $data = [
                'product_name' => $request->input('nama_produk'),
                'product_code' => $request->input('kode_produk'),
                'price' => $request->input('harga'),
                'image' => $path
            ];

            $insertData = DB::table('master.products')->insertGetId($data);

            return response()->json([
                'message' => 'Success insert data',
                'id' => $insertData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $data = DB::table('master.products')->where('id', $id)->first();

            return response()->json([
                'message' => 'Success',
                'data' => $data
            ], 200);
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Server Error',
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            if ($request->hasFile('image')) {
                $file_name = 'thumbnail/thumbnail-' . $request->input('kode_produk') . '.' . $request->image->extension();
                $path = 'storage/' . $file_name;
                $upload = $request->image->storeAs('public', $file_name);
            } else {
                $path = 'image/Thumbnail.jpg';
            }

            $dataUpdate = [
                'product_name' => $request->input('nama_produk'),
                'price' => $request->input('harga'),
                'image' => $path
            ];

            $updateData = DB::table('master.products')->where('id', $id)->update($dataUpdate);

            return response()->json([
                'message' => 'Berhasil Update Data'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal Update Data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $deleteData = DB::table('master.products')->where('id', $id)->delete();

            return response()->json([
                'message' => 'Berhasil Hapus Data'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal Hapus Data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function downloadExcel() {
        return Excel::download(new ProdukExport, 'produk.xlsx');
    }
}

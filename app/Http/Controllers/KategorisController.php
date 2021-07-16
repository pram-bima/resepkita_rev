<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Kategori;
use PhpOption\None;

class KategorisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $kategoris = Kategori::all();
        return view('kategori.index', compact('kategoris'));
    }

    public function list(){    	
    	return Kategori::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        //$kategoris = Kategori::all();
        //return view('kategori.create', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        //
        $request->validate([
            'nama_kategori' => 'required',
            'gambar_kategori' => 'required|mimes:jpeg,png,jpg|max:2048',
            'deskripsi_kategori' => 'required',
        ]);

        $response =  Response()->json([
            "success" => false,
            "data" => ''
        ]);

        if ($request->gambar_kategori) {
            $destinationPath = 'gambar/'; // upload path
            $imageName = date('YmdHis') . "." . $files->getClientOriginalExtension();
            $files->move($destinationPath, $imageName);
            $save = $request->all();
            $save['title'] = "$imageName";
        }

        $result = Kategori::insertGetId($save);
        return $result;

        $response =  Response()->json([
            "success" => true,
            "data" => $result
        ]);

        // $file = $request->file('gambar_kategori');
        // $nama_file = $file->getClientOriginalName();
        // $tujuan_upload = 'data_gambar_kategori';
        // $file->move($tujuan_upload, $nama_file);


        //$nama_kategori = $request->nama_kategori;
        //$gambar_kategori = $request->gambar_kategori->getClientOriginalName().'-'.time().'.'.$request->gambar_kategori->extension();
        //$deskripsi_kategori = $request->deskripsi_kategori;
        //$input['gambar_kategori'] = $request->gambar_kategori->getClientOriginalName().'-'.time().'.'.$request->gambar_kategori->extension();
        //$request->gambar_kategori->move(public_path('gambar'), $gambar_kategori);

        //$kategoris = model::create($nama_kategori, $gambar_kategori, $deskripsi_kategori);
        //return $kategoris;

        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Kategori $result)
    {
        //
        return view('kategori.detail', compact('result'));
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
        //return view('kategori.edit', compact('kategori'));
        $result = Kategori::find($id);

    	return $result;
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
        //
        $request->validate([
            'nama_kategori' => 'required',
            // 'gambar_kategori' => 'required|mimes:jpeg,png,jpg|max:2048',
            'deskripsi_kategori' => 'required',
        ]);

        // $kategori->update($request->all());

        $imgName = null;

        if ($request->gambar_kategori) {
            $imgName = $request->gambar_kategori->getClientOriginalName() . '-' . time() . '.' . $request->gambar_kategori->extension();

            $request->gambar_kategori->move(public_path('gambar_kategori'), $imgName);
        }


        // $file = $request->file('gambar_kategori');
        // $nama_file = $file->getClientOriginalName();
        // $tujuan_upload = 'data_gambar_kategori';
        // $file->move($tujuan_upload, $nama_file);

        Kategori::where('id', $kategori->id)
            ->update([
                'nama_kategori' => $request->nama_kategori,
                'gambar_kategori' => $imgName,
                'deskripsi_kategori' => $request->deskripsi_kategori,
            ]);

        return 'sukses';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kategori $kategori, $id)
    {
        //
        Kategori::destroy($id);
        //$kategori->delete(); //Fungsi untuk menghapus data sesuai dengan ID yang dipilih

        return 'sukses';
        //return redirect('/kategori')
        //    ->with('status', 'Data kategori berhasil dihapus!'); //Redirect ke halaman books/index.blade.php dengan pesan success
    }
}

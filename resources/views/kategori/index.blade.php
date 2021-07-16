@extends('layouts.app')

@section('content')

<div class="container">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/admin/home">Welcome</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                {{-- <a class="nav-link active" aria-current="page" href="/reseps">Dashboard</a> --}}
                <a class="nav-link" href="/users">Users</a>
                <a class="nav-link" href="/reseps/admin">Request</a>
                <a class="nav-link" href="/arsip">Arsip</a>
                <a class="nav-link" href="/kategori">Kategori</a>
                <a class="nav-link" href="/pesan">Pesan</a>

            </div>
            </div>
        </div>
    </nav>


    {{-- <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">KATEGORI RESEP</div>

            </div>
        </div>
    </div> --}}





    <div class="row justify-content-center mt-4">
        <div class="col-md-8">

                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

            <div class="card">
                <div class="card-header">DATA RESEP MAKANAN</div>
                <div class="card-body">
                    {{-- You are Admin. --}}
                    {{-- <h5>Resep Masakan</h5> --}}


                    <a class="btn btn-success btn-sm" href="javascript:;" data-toggle="modal" data-target="#formKategori">
                            Tambah Data
                    </a>
                    <table class="table mt-2">
                        <thead>
                            <tr>
                            {{-- <th scope="col">#</th> --}}
                            <th scope="col">Nama Kategori</th>
                            <th scope="col">Dibuat Sejak</th>
                            <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


<div id="formKategori" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Form Kategori</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group col-xs-12 col-lg-12">
                    <label class="control-label">Nama Kategori Resep</label>
                    {{ Form::text('nama_kategori', null, ['class' => 'form-control']) }}
                </div>
                <div class="form-group col-xs-12 col-lg-12">
                    <label class="form-label">Gambar Kategori</label>
                    {{ Form::file('gambar_kategori', null, ['class' => 'form-control']) }}
                </div>
                <div class="form-group col-xs-12 col-lg-12">
                    <label class="form-label">Deskripsi Kategori</label>
                    {{ Form::text('deskripsi_kategori', null, ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="modal-footer">
                <a class="btn btn-secondary btn-sm mb-2" data-dismiss="modal">Close</a>
				<button type="submit" href="javascript:;" class="btn btn-primary mb-2 btn-sm" onclick="sendData()">Kirim</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var idEdit = 0;
    $(document).ready(function(){        
        getIndex();
    });
    function getIndex(){
        $.ajax({
            type : 'get',
            url : 'http://127.0.0.1:8000/kategori/list',
            success : function(data){  
                $('tbody').html('');              
                $(data).each(function(x,y){
                    result = 
                    '<tr>'
                        + '<td>'+y.nama_kategori+'</td>'
                        + '<td>'+y.created_at+'</td>'
                        + '<td>'
                            + '<a href="javascript:;" onclick="editKategori('+y.id+')"><i class="fas fa-edit"></i></a>'
                            + '<a href="javascript:;" onclick="destroyKategori(this.parentNode.parentNode, '+y.id+')"><i class="fas fa-trash-alt"></i></a>'
                        + '</td>'
                    + '</tr>';
                    $('tbody').append(result);
                });            
            },
        });
    }
    function sendData(){
        var link;
        var requestType;
        var result;
        if( idEdit != 0 ){
            link = 'http://127.0.0.1:8000/kategori/'+idEdit;
            requestType = 'put';
            enctype = 'multipart/form-data';
        }else{
            link = 'http://127.0.0.1:8000/kategori';
            requestType = 'post';
            enctype = 'multipart/form-data';
        }
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url : link,
            type : requestType,
            dataType : JSON,
            data : {
                nama_kategori : $('[name=nama_kategori]').val(),
                gambar_kategori : $('[name=gambar_kategori]').val(),
                deskripsi_kategori : $('[name=deskripsi_kategori]').val(),
            },
            success : function(data, status, xhr){   
                idEdit = 0;   
                $('[name=nama_kategori]').val('');
                $('[name=gambar_kategori]').val('');
                $('[name=deskripsi_kategori]').val('');
                $('#formKategori').modal('hide');
                getIndex();
            },
            error: function (jqXHR, textStatus, errorMsg) {
				alert('Error : ' + errorMsg);
			}
        });
    }
    function editKategori(id){        
        $.ajax({
            type : 'get',
            url : 'http://127.0.0.1:8000/kategori/edit/'+id,
            success : function(data){
                idEdit = data.id;                
                $('#formKategori').modal('show');
                $('[name=nama_kategori]').val(data.nama_kategori);
                $('[name=gambar_kategori]').val(data.gambar_kategori);
                $('[name=deskripsi_kategori]').val(data.deskripsi_kategori);
                                            
            },
        });
    }
    function destroyKategori(kategori, id){        
        $.ajax({
            type : 'get',
            url : 'http://127.0.0.1:8000/kategori/destroy/'+id,
            success : function(data){
                if( data == 'sukses' ){
                    kategori.remove();
                }                                
            },
        });
    }
</script>

























    {{-- <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Resep Minuman</div>
                <div class="card-body">
                    You are Admin.
                    <h5>Resep Masakan</h5>
                    <table class="table">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nama Masakan</th>
                            <th scope="col">Gambar</th>
                            <th scope="col">Keterangan</th>
                            <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reseps as $r)
                            @if ($r->kategori_id == 2)

                            <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $r->judul }}</td>
                            <td><img class="img-fluid" src="<?= URL::to('/data_gambar'); ?>/{{$r->gambar}}" width="50px"></td>
                            <td>{{ $r->keterangan }}</td>
                            <td>
                                <a href="/users/destroy/{{ $r->id }}" class="badge badge-danger" onclick="return confirm('Yakin dihapus?')">delete</a>
                            </td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Resep Cemilan</div>
                <div class="card-body">
                    You are Admin.
                    <h5>Resep Masakan</h5>
                    <table class="table">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nama Masakan</th>
                            <th scope="col">Gambar</th>
                            <th scope="col">Keterangan</th>
                            <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reseps as $r)
                            @if ($r->kategori_id == 3)

                            <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $r->judul }}</td>
                            <td><img class="img-fluid" src="<?= URL::to('/data_gambar'); ?>/{{$r->gambar}}" width="50px"></td>
                            <td>{{ $r->keterangan }}</td>
                            <td>
                                <a href="/users/destroy/{{ $r->id }}" class="badge badge-danger" onclick="return confirm('Yakin dihapus?')">delete</a>
                            </td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> --}}
</div>
@endsection

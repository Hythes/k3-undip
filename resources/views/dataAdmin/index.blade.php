@extends('template')
@section('title','Data Admin')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-nav-tabs">
            <div class="card-header card-header-primary">
                <div class="nav-tabs-navigation">
                    <div class="nav-tabs-wrapper">
                        <ul class="nav nav-tabs" data-tabs="tabs">
                            <li class="nav-item">
                                <a class="nav-link active" href="#home" data-toggle="tab">Data Admin</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#updates" data-toggle="tab">Buat Admin</a>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body ">
                <div class="tab-content text-center">
                    <div class="tab-pane active" id="home">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="text-primary">
                                    <th>Nomor</th>
                                    <th>Username</th>
                                    <th>Nama</th>
                                    <th>Aksi</th>
                                </thead>
                                <tbody>
                                    @foreach($dataAdmin as $d)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$d->username}}</td>
                                        <td>{{$d->nama}}</td>
                                        <td>
                                            <button type="button" onclick="edit('{{ $d->toJson() }}')"
                                                class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                                                Edit
                                            </button>
                                            <a href="{{url('admin/dataAdmin/delete/'.$d->id)}} "
                                                onclick="return confirm('Anda yakin ingin menghapus?')"
                                                class="btn btn-danger">Hapus</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="text-xs-center">
                                {{ $dataAdmin->links() }}
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="updates">
                        <form action="" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <input type="text" class="form-control" name="username" placeholder="Username">
                                </div>
                                <div class="col-12 mb-2">
                                    <input type="text" class="form-control" name="nama" placeholder="Nama">
                                </div>
                                <div class="col-12 mb-2">
                                    <input type="password" class="form-control" name="password" placeholder="Password">
                                </div>
                                <div class="col-md-12 mb-2 ">
                                    <button type="submit" class="btn btn-primary pull-left">submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{url('admin/dataAdmin/edit')}}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id_edit">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <input type="text" class="form-control text-dark" id="username_edit" name="username"
                                placeholder="Username" required>
                        </div>
                        <div class="col-12 mb-2">
                            <input type="text" class="form-control text-dark" required id="nama_edit" name="nama"
                                placeholder="Nama">
                        </div>
                        <div class="col-12 mb-2">
                            <input type="password" required class="form-control text-dark" id="password_edit"
                                name="password" placeholder="Password">
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
        </form>
    </div>
</div>
@endsection
@section('script')
<script>
    function edit(json){
        json = JSON.parse(json)
        $('#id_edit').val(json.id)
        $('#username_edit').val(json.username)
        $('#nama_edit').val(json.nama)

    }
</script>
@endsection
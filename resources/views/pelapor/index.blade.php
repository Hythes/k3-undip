@extends('template')
@section('title','Data Pelapor')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title">Data Pelapor</h4>
                <p class="card-category"></p>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead class="text-primary">
                            <th>Nomor</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </thead>
                        <tbody>
                            @foreach($pelapor as $d)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{$d->nik}}</td>
                                <td>{{$d->nama}}</td>
                                <td
                                    onclick="ss(this,'{{$d->alamat}}','{{$s = \Illuminate\Support\Str::limit($d->alamat, $limit = 30, $end = '...')}}')">
                                    {{$s}}</td>
                                <td>
                                    <a href="{{url('admin/pelapor/delete/'.$d->id)}} "
                                        onclick="return confirm('Anda yakin ingin menghapus?')"
                                        class="btn btn-danger">Hapus</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="text-xs-center">
                        {{ $pelapor->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    function ss(ss,msg,msgShort){
        if(ss.innerHTML.length <= 60){
            ss.innerHTML = msg
        }else{
            ss.innerHTML = msgShort
        }
    }
 
</script>
@endsection
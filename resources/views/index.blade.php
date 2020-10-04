@extends('template')
@section('title','Dashboard')
@section('content')
<div class="row">
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
        <div class="card card-stats">
            <div class="card-header card-header-primary card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">add_alert</i>
                </div>
                <p class="card-category">K3 Baru</p>
                <h3 class="card-title" id="k3Baru">{{$jumlahData['k3Baru']}}
                </h3>
            </div>
            <div class="card-footer">
                <div class="stats">
                    <i class="material-icons">update</i> Real Time
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
        <div class="card card-stats">
            <div class="card-header card-header-success card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">check</i>
                </div>
                <p class="card-category">K3 Diterima</p>
                <h3 class="card-title" id="k3Diterima">{{$jumlahData['k3Diterima']}} </h3>
            </div>
            <div class="card-footer">
                <div class="stats">
                    <i class="material-icons">update</i> Real Time
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
        <div class="card card-stats">
            <div class="card-header card-header-danger card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">close</i>
                </div>
                <p class="card-category">K3 Ditolak</p>
                <h3 class="card-title" id="k3Ditolak">{{$jumlahData['k3Ditolak']}} </h3>
            </div>
            <div class="card-footer">
                <div class="stats">
                    <i class="material-icons">update</i> Real Time
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
        <div class="card card-stats">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">person_add</i>
                </div>
                <p class="card-category">Pelapor</p>
                <h3 class="card-title" id="pelapor">{{$jumlahData['pelapor']}} </h3>
</div>
<div class="card-footer">
    <div class="stats">
        <i class="material-icons">update</i> Real Time
    </div>
</div>
</div>
</div> --}}
<div class="col-lg-6 col-md-12">
    <div class="card">
        <div class="card-header card-header-warning">
            <h4 class="card-title">Data Pelapor</h4>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-hover">
                <thead class="text-warning">
                    <th>ID</th>
                    <th>NIK</th>
                    <th>Nama</th>
                </thead>
                <tbody>
                    @foreach($pelapor as $d)
                    <tr>
                        <td>{{$iP++}}</td>
                        <td>{{$d->nik}}</td>
                        <td>{{$d->nama}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-12">
    <div class="card">
        <div class="card-header card-header-info">
            <h4 class="card-title">Data Admin</h4>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-hover">
                <thead class="text-warning">
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Username</th>
                </thead>
                <tbody>
                    @foreach($dataAdmin as $d)
                    <tr>
                        <td>{{$iA++}}</td>
                        <td>{{$d->username}}</td>
                        <td>{{$d->nama}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
@endsection
@section('script')
<script>
    function runCode(){
        var url = '{{url('admin/sdsd')}}'
        $.post(url, function(res){
        if(res.status == 200){
            $('#k3Baru').html(res.data.k3Baru)
            $('#k3Diterima').html(res.data.k3Diterima)
            $('#k3Ditolak').html(res.data.k3Ditolak)
        }else{
        alert(res.message)
        }
        })
}

    Echo.channel("admin").listen(".k3-monitor", e => {
        runCode()
    }).listen('.pelapor-monitor',e => {
        runCode()
    })
</script>
@endsection
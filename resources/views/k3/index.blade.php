@extends('template')
@section('header')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection
@section('title','Data K3')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-nav-tabs">
            <div class="card-header card-header-primary">
                <div class="nav-tabs-navigation">
                    <div class="nav-tabs-wrapper">
                        <ul class="nav nav-tabs" data-tabs="tabs">
                            <li class="nav-item">
                                <a class="nav-link active" href="#home" data-toggle="tab">Data K3 Baru</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#updates" data-toggle="tab">Data K3 Diterima</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#new" data-toggle="tab">Data K3 Ditolak</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body ">
                <div class="tab-content text-center">
                    <div class="tab-pane active" id="home">
                        @include('k3.k3Baru')
                    </div>
                    <div class="tab-pane" id="updates">
                        @include('k3.k3Terima')
                    </div>
                    <div class="tab-pane" id="new">
                        @include('k3.k3Tolak')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Data K3 Lengkap</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="text-white" aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal-body">
                <a id="urlFoto" href="{{asset('storage/1601622304_5f76d12033656.png')}}" target="_blank">
                    <img class="card-img-top" id="foto" src="{{asset('storage/1601622304_5f76d12033656.png')}}"
                        height="200px">
                </a>
                <div class="card-body text-white">
                    <h4>Pelapor : <span id="namaPelapor" class="text-secondary">Yoo</span> </h4>
                    <h4>Lokasi : <a class="btn btn-primary btn-sm" id="urlLokasi" target="_blank"
                            href="https://www.google.com/maps?q=-34.397,150.644">
                            Klik Disini
                        </a></h4>
                    <h4>Keterangan</h4>
                    <p class="card-description" id="keterangan"></p>
                    <input type="hidden" id="idK3">
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="tolak()" class="btn btn-danger">Tolak</button>
                    <button type="button" onclick="terima()" class="btn btn-primary">Terima</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
{{-- <script>
        let map;
        function initMap() {
        map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: -34.397, lng: 150.644 },
                zoom: 17,
            });
        }
    </script> --}}
<script>
    Echo.channel("admin").listen(".k3-monitor", e => {
        var jenis;
        if (e.jenis == "baru") { jenis = $('#k3Baru') } if(e.jenis == 'diterima'){ jenis = $('#k3Diterima') } if(e.jenis == 'ditolak'){ jenis = $('#k3Ditolak') }
        jenis.prepend(
            ` <tr>
                <td>${e.data.keterangan}</td>
                <td>${e.dataPelapor.nama}</td>
                <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"
                        onclick="runCode(${e.data.id})">Lebih Lengkap </button> </td>
            </tr> `
            );
    });
    function tolak(){
        var id = $('#idK3').val()
        var url = `{{url("admin/dataAdmin/tolak")}}/${id}`
        $.post(url,function(e){
            if(e.status == 200){
                location.reload()
            }else{
                alert(e.message)
            }
        })
    }
    function terima(){
        var id = $('#idK3').val()
        var url = `{{url("admin/dataAdmin/terima")}}/${id}`
        $.post(url,function(e){
            if(e.status == 200){
                location.reload()
            }else{
                alert(e.message)
            }
        })
    }
    function runCode(id){
        $('#idK3').val(id)
        var url = `{{url("admin/dataAdmin/getDataSatu")}}/${id}`
        $.post(url,function(res){
            if(res.status == 200){
                $('#urlFoto').attr('href',`{{asset('storage')}}/${res.data.foto}`)
                $('#foto').attr('src',`{{asset('storage')}}/${res.data.foto}`)
                $('#namaPelapor').html(res.data.nama)
                $('#urlLokasi').attr('href',`https://www.google.com/maps?q=${res.data.lat},${res.data.long}`)
                $('#keterangan').html(res.data.keterangan)
            }else{
                alert(res.message)
            }
        })

    }
</script>
@endsection
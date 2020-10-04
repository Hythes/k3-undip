<div class="table-responsive">
    <table class="table">
        <thead class="text-primary">
            <th>Keterangan</th>
            <th>Nama Pelapor</th>
            <th>Aksi</th>
        </thead>
        <tbody id="k3Baru">
            @foreach($k3 as $d)
            <tr>
                <td>{{\Illuminate\Support\Str::limit($d->keterangan, $limit = 20, $end = '...')}}</td>
                <td>{{$d->nama}}</td>
                <!-- Button trigger modal -->
                <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"
                        onclick="runCode({{$d->id}})">
                        Lebih Lengkap
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-xs-center">
        {{ $k3->links() }}
    </div>
</div>
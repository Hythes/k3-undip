@extends('template')
@section('title','Login K3')

@section('content')
<style>
    .Absolute-Center {
        margin: auto;
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
    }

    .Absolute-Center.is-Responsive {
        width: 50%;
        height: 50%;
        min-width: 200px;
        max-width: 400px;
        padding: 40px;
    }
</style>
<form action="" method="POST">
    @csrf
    <div class="card Absolute-Center is-Responsive ">
        <div class="card-body">
            <h3 class="card-title text-white text-center mb-3">Login</h3>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="material-icons">account_circle</i>
                    </span>
                </div>
                <input type="text" class="form-control" name="username" placeholder="Username">
            </div>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="material-icons">lock</i>
                    </span>
                </div>
                <input type="password" class="form-control" name="password" placeholder="Password">
            </div>
        </div>
        <div class="card-footer">
            <button type="submit"" class=" btn-block btn btn-primary btn-sm">Login</button>
        </div>
        {{-- <div class="col-md-12">
            <a href="register" class="text-muted pull-right">Klik disini untuk Register</a>
        </div> --}}
    </div>
</form>
@endsection
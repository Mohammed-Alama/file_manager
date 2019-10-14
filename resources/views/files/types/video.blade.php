@extends('layouts.app')


@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{$file->name}}
                    </div>

                    <div class="card-body">
                        <video src="{{asset('storage').'/'.$file->path}}"></video>
                    </div>
                </div>
                <div class=" card">
                    <div class="card-header" id="form_U_D">
                        <form action="{{route('files.download',$file->id)}}" method="GET" role="form">
                            @csrf
                            <button type="submit" class="btn btn-primary">Download</button>
                        </form>
                        <form action="{{route('files.delete',$file->id)}}" method="POST" role="form">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


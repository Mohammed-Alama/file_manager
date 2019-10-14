@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Upload Files</div>
                    <form id="upload_form" action="{{route('files.upload')}}" method="POST"
                          enctype="multipart/form-data" role="form">
                        @csrf
                        <div class="form-group">
                            {{--                            <label for="file">Upload File</label>--}}
                            <input class="form-control-file" type="file" name="files[]" id="files" multiple>
                        </div>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                </div>
                @if (session()->has('ErrorMassage'))

                    <div class="alert alert-danger" role="alert">{{session('ErrorMassage')}}</div>

                @endif
                @if($errors->any())
                    <ul id="errors" class="alert alert-danger" style="list-style: none">
                        @foreach ($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
@endsection


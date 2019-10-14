@extends('layouts.app')


@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    @foreach($files as $file)
                    <div class="card-header">
                        {{$file->name}}
                    </div>

                    <div class="card-body">
                        <a href="{{route('files.show',$file->id)}}">
                           Show File
                        </a>
                    </div>
                     @endforeach
                </div>
                @if (session()->has('ErrorMassage'))
                    <div class="alert alert-danger" role="alert">{{session('ErrorMassage')}}</div>
                @elseif(session()->has('SuccessMassage'))
                    <div class="alert alert-success" role="alert">{{session('SuccessMassage')}}</div>
                @endif

            </div>
        </div>
    </div>
@endsection
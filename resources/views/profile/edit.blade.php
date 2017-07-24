@extends('layouts.app')

@section('content')

    <div class="container">

        <div class="row">
            <h1>{{ $user->name }}</h1>
        </div>

        <form method="POST" action="{{ route('profile.update') }}">

            {!! method_field('patch') !!}

            {{ csrf_field() }}

            <h1>Google Drive</h1>

            Follow this <a href="https://gist.github.com/ivanvermeyen/cc7c59c185daad9d4e7cb8c661d7b89b#setup-a-laravel-storage-driver-with-google-drive-api" target="_blank">tutorial</a>

            <div class="form-group">
                <input type="text" name="clientId" class="form-control" placeholder="Client ID" value="{{ old('clientId') }}" required>
            </div>

            <div class="form-group">
                <input type="text" name="clientSecret" class="form-control" placeholder="Client Secret" value="{{ old('clientSecret') }}" required>
            </div>

            <div class="form-group">
                <input type="text" name="refreshToken" class="form-control" placeholder="Refresh Token" value="{{ old('refreshToken') }}" required>
            </div>

            <div class="form-group">
                <input type="text" name="folderId" class="form-control" placeholder="Folder ID" value="{{ old('folderId') }}" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>

            @if(count($errors))
                <ul class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

        </form>

    </div>

@endsection
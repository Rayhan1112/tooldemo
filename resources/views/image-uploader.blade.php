@extends('layouts.app')

@section('content')
<h1>Image Uploader</h1>

<form action="/upload-image" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="image" required onchange="previewImage(event)" accept="image/*">
    <button type="submit">Upload</button>
    <img id="preview" style="display:none; max-width:200px; margin-top:10px;">
</form>

@if($errors->any())
<div class="alert alert-danger">
    <ul>
    @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
    @endforeach
    </ul>
</div>
@endif

@if(session('success'))
    <p>{{ session('success') }}</p>
@endif

@php
$files = \Illuminate\Support\Facades\File::files(public_path('uploads'));
@endphp

@if(count($files))
<h2>Uploaded Images</h2>
<div>
@foreach($files as $file)
<img src="{{ asset('uploads/' . basename($file)) }}" alt="Uploaded Image" style="width:400px; margin:10px;">
@endforeach
</div>
@endif
@endsection
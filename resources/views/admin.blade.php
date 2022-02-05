@extends('layouts/master')
@section('title', 'Dashboard')
@section('content')
    Welcome to dashboard
@endsection
@include('components/button', ['text' => 'See just how great it is'])
@include('components/button', ['text' => 'New text'])
@section('footerScript')
    @parent
    <script src="/js/admin.js" ></script>
    <link rel="stylesheet" href="/css/style.css" />
@endsection
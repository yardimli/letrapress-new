@extends('layouts.app')
@section('title', 'Outlets')
@section('page', 'directory')
@section('content')
@include('partials.directory', ['kind' => 'outlet', 'endpoint' => route('ajax.outlets'), 'heading' => 'Outlets that shape the conversation', 'description' => 'Find publications, broadcasts, and digital channels with the audience and editorial focus your story needs.'])
@endsection

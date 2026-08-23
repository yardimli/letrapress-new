@extends('layouts.app')
@section('title', 'Journalists')
@section('page', 'directory')
@section('content')
@include('partials.directory', ['kind' => 'journalist', 'endpoint' => route('ajax.journalists'), 'heading' => 'Journalists worth knowing', 'description' => 'Search the media database by name, beat, language, country, or format, then save the right people to an outreach list.'])
@endsection

@extends('layouts.app')

@section("content")
@include('partial.slider')
@if (count($categories) > 0)
@foreach ($categories as $category)
@if (count($category) > 0)
@include('partial.book', ['books' => $category])
@endif
@endforeach
@endif
@include('partial.newsletter')
@include('partial.instagram')
@endsection
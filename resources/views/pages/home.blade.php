@extends('layouts.app')

@section("content")
@include('partial.slider')
@if (count($categories) > 0)
@foreach ($categories as $category)
@if (count($category) > 0)
@if ($loop->index == 2)
@include('components.banner')
@endif
@include('partial.book', ['books' => $category])
@endif

@endforeach
@endif
@include('partial.newsletter')
@include('partial.instagram')
@include('layouts.footer')
@include('layouts.sub_footer')
@endsection
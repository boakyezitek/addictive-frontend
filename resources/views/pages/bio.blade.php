@extends('layouts.app')

@section("content")
<section>
    <div class="add__custom__container">
       @include('components.breadcrumb')
       <div class="page__line"></div>

        @include('partial.bio')
  </div>
</section>

@endsection

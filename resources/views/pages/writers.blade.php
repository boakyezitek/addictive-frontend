@extends('layouts.app')

@section("content")
<section>
    <div class="add__custom__container">
        <span class="mobile__catalog__title">Nos livres</span>
        <div class="page__line"></div>
         @include("partial.writers")
  </div>
</section>

@endsection

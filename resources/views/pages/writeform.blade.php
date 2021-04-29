@extends('layouts.app')


@section("content")
<section>
  <div class="write__form__container">
    <div class="write__form__sidebar">
      @include("partial.writeform.sidebar")
    </div>
    <div class="write__form__content">
      @include("partial.writeform.content")
    </div>
  </div>
</section>
@endsection
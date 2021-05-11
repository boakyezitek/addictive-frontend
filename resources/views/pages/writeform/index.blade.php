@extends('layouts.app')


@section("content")
<section>
  <div class="write__form__container">
      @include("partial.writeform.sidebar")
      @include("partial.writeform.index")
  </div>
</section>
@endsection
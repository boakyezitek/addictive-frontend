@extends('layouts.app')

@section("content")
<section>
    <div class="add__custom__container">
       @include('components.breadcrumb')
       <div class="page__line"></div>

      <div class="book__details__main__box">
       @include('partial.books.book_details');
      </div>
  </div>
</section>

@endsection

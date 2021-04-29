@extends('layouts.app')

@section("content")
<section>
    <div class="add__custom__container">
       <div class="row__box">
           <div class="col-md-2">
                <div class="page__outline__input">Réinitialiser les filtres</div>
           </div>
           <div class="col-md-10">
            <div class="catalog__search__input">
                <img src="img/icons/ic-search.svg" class="ic-search" />
                <input type="text" placeholder="Rechercher un livre…" />
            </div>
           </div>
       </div>
  </div>
</section>

@endsection

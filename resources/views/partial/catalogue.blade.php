<div class="row__box">
    <div class="col-md-2">
         <div class="page__outline__input">Réinitialiser les filtres</div>
         <div class="tag__box">
             <h5 class="c__title">Format disponibles</h5>
              @include('components.catalogue.tags')
         </div>

         <div class="page__line"></div>

         <div class="filter__Box">
              <h5 class="c__title mt-4">Genres</h5>
               @include('components.catalogue.checks')
            </div>
    </div>
    <div class="col-md-10">
      @include('components.catalogue.searchbar')

     <div class="dropdown__box mt-4 mb-5">
         @include('components.catalogue.dropdown')
     </div>

     <div class="card__list news_content">
        @include('components.catalogue.books')
     </div>
    </div>
</div>

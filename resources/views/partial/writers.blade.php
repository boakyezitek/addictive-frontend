<div class="row__box">
    <div class="col-md-2">
        <div class="page__outline__input">Réinitialiser les filtres</div>
         <div class="filter__Box">
              <h5 class="c__title mt-4 mb-4">Sélection alphabétique</h5>
               @include('components.writers.alphabets_buttons')
        </div>
        <div class="page__line"></div>

        <div class="tag__box">
            <h5 class="c__title">Format des livres de l’auteure</h5>
             @include('components.writers.tags')
        </div>

    </div>
    <div class="col-md-10">
      @include('components.writers.searchbar')

     <div class="dropdown__box mt-4 mb-5">
         @include('components.writers.dropdown')
     </div>

     <div class="card__list news_content">
        @include('components.writers.users')
     </div>
    </div>
</div>

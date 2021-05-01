<div class="row__box">
    <div class="col-md-2">
     @include('components.bio.profile')
    </div>
    <div class="col-md-10">
   @include('components.bio.content')
      <h5 class="c__title mt-4">Livre(s) de l’auteur·e</h5>
     <div class="card__list news_content">
        @include('components.bio.books')
     </div>

     @include('components.bio.tags')
    </div>
</div>

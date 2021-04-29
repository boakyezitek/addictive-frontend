<section class="add__content__section">
    <div class="add__custom__container mt-5">
        <div class="heading__with__button">
            <h2 class="add__page__title mb-3">
                {{$books[0]['title']}}
            </h2>
            <div class="add__page__btn small">
                Voir toutes
            </div>
        </div>

       <div class="add__content__box">
       @foreach ($books as $book)
            @include('components.book')
       @endforeach
       </div>

       <div class="add__page__btn mt-5">
        Voir toutes les nouveautés
       </div>
    </div>
</section>
<div class="book__details__container">
 @include('components.books.book_details.mask')
    <div class="details__content__box">
       @include('components.books.book_details.info')

        <div class="page__line__bold"></div>

        <div class="format_box">
           <h5 class="small__heading">
              Formats disponibles
           </h5>

           <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
              <li class="nav-item" role="presentation">

                <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">
                  <img src="img/news/ic-ebook.svg" />
                  <span>E-book</span>
                  </a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">
                  <img src="img/news/ic-book.svg" />
                  <span>Broché</span>
                  </a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">
                  <img src="img/news/ic-listening.svg" />
                  <span>Audiobook</span>
                  </a>
              </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
              <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
               @include('components.books.book_details.dropdown')
              </div>
              <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">No data available !</div>
              <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">No data available !</div>
            </div>
        </div>

        <div class="page__line"></div>
        @include('components.books.book_details.content')
        <h5 class="small__heading">Voir plus</h5>
        @include('components.books.book_details.tags')
        <div class="page__line"></div>
       @include('components.books.book_details.other_info')
        @include('components.books.book_details.other_books')


    </div>


</div>

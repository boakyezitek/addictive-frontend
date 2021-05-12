@extends('layouts.app')


@section("content")
<section>
  <div class="add__custom__container mt-5">
      <div class="row">
        <div class="col-md-4 sidebar">
            @include("partial.writeform.sidebar")
        </div>
        <div class="col-md-8">
          <div class="tab-content" id="v-pills-tabContent">
            <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                @include("partial.writeform.index")
            </div>
            <div class="tab-pane fade" id="v-pills-profile" role="tabpane2" aria-labelledby="v-pills-profile-tab">
                @include("partial.writeform.condition")
            </div>
            <div class="tab-pane fade" id="v-pills-messages" role="tabpane3" aria-labelledby="v-pills-messages-tab">
                @include("partial.writeform.manuscript")
            </div>
            <div class="tab-pane fade" id="v-pills-settings" role="tabpane4" aria-labelledby="v-pills-settings-tab">
                @include("partial.writeform.manusetting")
            </div>
          </div>
        </div>
      </div>
  </div>
</section>
@endsection

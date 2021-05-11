<div class="mobile-nav">
    <div class="menu-icons">
        <div>
            <img src="img/icons/ic-menu.svg" class="mobile_main_menu" id="toggle-menu" />
            <img src="img/icons/ic-close.png" class="mobile_main_menu_1" id="hide-menu" style="display:none;" />
        </div>
        <div>
            <img src="img/logo/logo.svg" class="mobile_main_logo" />
            <img src="img/logo/logo-addictives-black.png" class="mobile_main_logo_toggle" style="display:none;" />
        </div>
    </div>
    <div class="mobile-search-box" style="display:none">
        <img src="img/icons/ic-search.svg" class="mobile-ic-search" />
        <input id='mobile-searchBar' name='search' type='text' placeholder='Recherche…'>
        <img src="img/icons/ic-close.png" id='mobile-cancel-search' style="display:block;" />
        <div class="mobile__search__drop__down__box" style="display:none;">
            <h5 class="titles">Auteure</h5>
            <div class="line"></div>
            <div class="title__list__box">
                <h4>Emma Green</h4>
                <div>6 livres disponibles</div>
            </div>
            <h5 class="titles">Livres</h5>
            <div class="line"></div>
        </div>
    </div>
    <div class="mobile-search-icon">
        <img src="img/icons/ic-search.svg" class="mobile_search_icon" />
        <img src="img/icons/ic-close.svg" class="mobile_close_icon" style="display:none;" />
    </div>
</div>
@include('partial.mobile_dropdown_menu')
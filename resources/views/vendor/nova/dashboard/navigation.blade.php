<router-link exact tag="h3"
             :to="{
        name: 'dashboard.custom',
        params: {
            name: 'main'
        }
    }"
             class="cursor-pointer flex items-center font-normal dim text-white mb-8 text-base no-underline">
    <svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 20 20">
        <defs>
            <path id="b" d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
            <filter id="a" width="135%" height="135%" x="-17.5%" y="-12.5%" filterUnits="objectBoundingBox">
                <feOffset dy="1" in="SourceAlpha" result="shadowOffsetOuter1"/>
                <feGaussianBlur in="shadowOffsetOuter1" result="shadowBlurOuter1" stdDeviation="1"/>
                <feColorMatrix in="shadowBlurOuter1" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.166610054 0"/>
            </filter>
        </defs>
        <g fill="none" fill-rule="evenodd">
            <use fill="#000" filter="url(#a)" xlink:href="#b"/>
            <use fill="var(--sidebar-icon)" xlink:href="#b"/>
        </g>
    </svg>
    <span class="text-white sidebar-label">{{ __('Statistiques') }}</span>
</router-link>
@if (\Laravel\Nova\Nova::availableDashboards(request()))
    <ul class="list-reset mb-8">
        @foreach (\Laravel\Nova\Nova::availableDashboards(request()) as $dashboard)
            <li class="leading-wide mb-4 ml-8 text-sm">
                <router-link :to='{
                    name: "dashboard.custom",
                    params: {
                    name: "{{ $dashboard::uriKey() }}",
                    },
                    query: @json($dashboard->meta()),
                    }'
                             exact
                             class="text-white no-underline dim">
                    {{ $dashboard::label() }}
                </router-link>
            </li>
        @endforeach
    </ul>
@endif

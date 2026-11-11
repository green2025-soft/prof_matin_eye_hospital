@if (($moduleMenu['permissions']['pharmacy.view'] ?? false)
    && (($moduleMenu['permissions']['pharmacy-products.view'] ?? false)
        || ($moduleMenu['permissions']['pharmacy-categories.view'] ?? false)
        || ($moduleMenu['permissions']['pharmacy-shelf.view'] ?? false)))
    @php
        $catalogSelected = ($moduleMenu['productType'] ?? null) === $catalogType;
        $catalogActive = $catalogSelected && request()->routeIs(
            'pharmacy.products.*', 'pharmacy.categories.*', 'pharmacy.shelf.*',
            'pharmacy.variants.*', 'pharmacy.product-units.*',
        );
    @endphp
    <details class="app-nav-group" @if ($catalogActive) open @endif>
        <summary @class(['app-nav-link app-nav-group-toggle', 'active' => $catalogActive]) title="{{ $catalogTitle }}">
            <span class="app-nav-icon"><i class="fa-solid fa-list" aria-hidden="true"></i></span>
            <span class="app-nav-copy">{{ $catalogLabel }}</span>
            <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
        </summary>
        <div class="app-nav-submenu ps-3">
            @if ($moduleMenu['permissions']['pharmacy-products.view'] ?? false)
                @include('core::partials.navigation-link', [
                    'label' => $productLabel, 'icon' => $productIcon,
                    'url' => route('pharmacy.products.index', ['type' => $catalogType] + ($catalogParameters ?? [])),
                    'active' => $catalogSelected && request()->routeIs('pharmacy.products.*'),
                ])
            @endif
            @if ($moduleMenu['permissions']['pharmacy-categories.view'] ?? false)
                @include('core::partials.navigation-link', [
                    'label' => $categoryLabel, 'icon' => 'layer-group',
                    'url' => route('pharmacy.categories.index', ['type' => $catalogType] + ($catalogParameters ?? [])),
                    'active' => $catalogSelected && request()->routeIs('pharmacy.categories.*'),
                ])
            @endif
            @if ($moduleMenu['permissions']['pharmacy-shelf.view'] ?? false)
                @include('core::partials.navigation-link', [
                    'label' => $shelfLabel, 'icon' => 'warehouse',
                    'url' => route('pharmacy.shelf.index', ['type' => $catalogType] + ($catalogParameters ?? [])),
                    'active' => $catalogSelected && request()->routeIs('pharmacy.shelf.*'),
                ])
            @endif
        </div>
    </details>
@endif

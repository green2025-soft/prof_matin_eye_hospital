<?php if(($moduleMenu['permissions']['pharmacy.view'] ?? false)
    && (($moduleMenu['permissions']['pharmacy-products.view'] ?? false)
        || ($moduleMenu['permissions']['pharmacy-categories.view'] ?? false)
        || ($moduleMenu['permissions']['pharmacy-shelf.view'] ?? false))): ?>
    <?php
        $catalogSelected = ($moduleMenu['productType'] ?? null) === $catalogType;
        $catalogActive = $catalogSelected && request()->routeIs(
            'pharmacy.products.*', 'pharmacy.categories.*', 'pharmacy.shelf.*',
            'pharmacy.variants.*', 'pharmacy.product-units.*',
        );
    ?>
    <details class="app-nav-group" <?php if($catalogActive): ?> open <?php endif; ?>>
        <summary class="<?php echo \Illuminate\Support\Arr::toCssClasses(['app-nav-link app-nav-group-toggle', 'active' => $catalogActive]); ?>" title="<?php echo e($catalogTitle); ?>">
            <span class="app-nav-icon"><i class="fa-solid fa-list" aria-hidden="true"></i></span>
            <span class="app-nav-copy"><?php echo e($catalogLabel); ?></span>
            <i class="fa-solid fa-chevron-down app-nav-group-caret ms-auto" aria-hidden="true"></i>
        </summary>
        <div class="app-nav-submenu ps-3">
            <?php if($moduleMenu['permissions']['pharmacy-products.view'] ?? false): ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => $productLabel, 'icon' => $productIcon,
                    'url' => route('pharmacy.products.index', ['type' => $catalogType] + ($catalogParameters ?? [])),
                    'active' => $catalogSelected && request()->routeIs('pharmacy.products.*'),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
            <?php if($moduleMenu['permissions']['pharmacy-categories.view'] ?? false): ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => $categoryLabel, 'icon' => 'layer-group',
                    'url' => route('pharmacy.categories.index', ['type' => $catalogType] + ($catalogParameters ?? [])),
                    'active' => $catalogSelected && request()->routeIs('pharmacy.categories.*'),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
            <?php if($moduleMenu['permissions']['pharmacy-shelf.view'] ?? false): ?>
                <?php echo $__env->make('core::partials.navigation-link', [
                    'label' => $shelfLabel, 'icon' => 'warehouse',
                    'url' => route('pharmacy.shelf.index', ['type' => $catalogType] + ($catalogParameters ?? [])),
                    'active' => $catalogSelected && request()->routeIs('pharmacy.shelf.*'),
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
        </div>
    </details>
<?php endif; ?>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Pharmacy\resources\views\partials\catalog-menu.blade.php ENDPATH**/ ?>
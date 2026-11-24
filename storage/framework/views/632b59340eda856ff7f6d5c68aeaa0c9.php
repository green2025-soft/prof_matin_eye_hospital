<?php ($moduleMenu = $contextMenu ?? $moduleMenu ?? []); ?>
<!doctype html>
<html lang="<?php echo e($layout['locale']); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $__env->yieldContent('title', $pageTitle ?? 'Dashboard'); ?> | <?php echo e($layout['appName']); ?></title>
        <?php if($layout['faviconUrl']): ?>
            <link rel="icon" href="<?php echo e($layout['faviconUrl']); ?>">
        <?php endif; ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    </head>
    <body class="<?php echo \Illuminate\Support\Arr::toCssClasses(['app-shell', 'app-auth-shell d-flex align-items-center justify-content-center p-3 p-sm-4' => ! $layout['user']]); ?>">
        <a class="app-skip-link visually-hidden-focusable position-fixed p-3" href="#main-content">Skip to content</a>
        <?php if($layout['user']): ?>
            <aside class="app-sidebar offcanvas-lg offcanvas-start" id="appSidebar" tabindex="-1" aria-labelledby="sidebar-brand">
                <div class="app-sidebar-head">
                    <a class="app-brand" href="<?php echo e($layout['homeUrl']); ?>" id="sidebar-brand">
                        <?php echo $__env->make('core::partials.brand', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </a>
                    <button class="btn-close btn-close-white d-lg-none" type="button" data-bs-dismiss="offcanvas" data-bs-target="#appSidebar" aria-label="Close navigation"></button>
                </div>
                <div class="offcanvas-body">
                    <nav class="app-nav" aria-label="Main navigation">
                        
                        <?php if($moduleMenu['homeAllowed'] ?? $layout['navigation']['dashboard']['allowed']): ?>
                            <?php echo $__env->make('core::partials.navigation-link', [
                                'label' => $moduleMenu['homeLabel'] ?? 'Dashboard',
                                'icon' => 'table-cells-large',
                                'url' => $moduleMenu['homeUrl'] ?? $layout['navigation']['dashboard']['url'],
                                'active' => $moduleMenu['homeActive'] ?? $layout['navigation']['dashboard']['active'],
                            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php endif; ?>

                        <?php if($layout['navigation']['modules']['allowed']): ?>
                            <?php echo $__env->make('core::partials.navigation-link', [
                                'label' => 'Modules', 'icon' => 'layer-group',
                                'url' => $layout['navigation']['modules']['url'],
                                'active' => $layout['navigation']['modules']['active'],
                            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php endif; ?>
                        <?php echo $__env->make($moduleMenu['view'] ?? $foundationMenuView ?? 'core::partials.menu', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </nav>
                </div>
            </aside>

            <div class="app-page">
                <header class="app-header">
                    <div class="app-header-inner">
                        <button class="app-toolbar-button" type="button" data-sidebar-toggle aria-controls="appSidebar" aria-expanded="true" aria-label="Toggle navigation" title="Toggle navigation">
                            <i class="fa-solid fa-bars" aria-hidden="true"></i>
                        </button>
                        <nav class="app-breadcrumb" aria-label="Breadcrumb">
                            <a href="<?php echo e($layout['homeUrl']); ?>">Home</a>
                            <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                            <span aria-current="page"><?php echo $__env->yieldContent('title', $pageTitle ?? 'Dashboard'); ?></span>
                        </nav>
                        <?php if(filled($layout['branchName'])): ?>
                            <div class="app-branch-context">
                                <span class="app-branch-badge" title="Active branch: <?php echo e($layout['branchName']); ?>">
                                    <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                                    <span class="visually-hidden">Active branch:</span>
                                    <span class="app-branch-context-name"><?php echo e($layout['branchName']); ?></span>
                                </span>
                                <?php if($layout['canSwitchBranch']): ?>
                                    <form action="<?php echo e($layout['switchBranchUrl']); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button class="app-branch-switch" type="submit" aria-label="Switch branch" title="Choose a different branch">
                                            <i class="fa-solid fa-arrow-right-arrow-left" aria-hidden="true"></i>
                                            <span>Switch Branch</span>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <div class="d-flex align-items-center gap-2 ms-auto">
                            
                            <button class="app-toolbar-button d-none d-md-inline-flex" type="button" data-fullscreen-toggle aria-pressed="false" aria-label="Enter fullscreen" title="Enter fullscreen">
                                <i class="fa-solid fa-expand" aria-hidden="true"></i>
                            </button>
                            <div class="dropdown">
                                <button class="app-user-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Account menu">
                                    <span class="app-avatar"><?php echo e($layout['user']['initial']); ?></span>
                                    <span class="app-user-copy"><strong><?php echo e($layout['user']['name']); ?></strong></span>
                                    <i class="fa-solid fa-chevron-down small" aria-hidden="true"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end app-account-menu">
                                    <div class="px-3 py-2"><strong class="d-block small"><?php echo e($layout['user']['name']); ?></strong><span class="text-body-secondary small text-break"><?php echo e($layout['user']['email']); ?></span></div>
                                    <hr class="dropdown-divider">
                                    <?php if($layout['user']['editUrl']): ?>
                                        <a class="dropdown-item" href="<?php echo e($layout['user']['editUrl']); ?>"><i class="fa-solid fa-user-gear me-2" aria-hidden="true"></i>My account</a>
                                    <?php endif; ?>
                                    <form action="<?php echo e($layout['logoutUrl']); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <button class="dropdown-item text-danger" type="submit"><i class="fa-solid fa-arrow-right-from-bracket me-2" aria-hidden="true"></i>Sign out</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                <main class="app-content" id="main-content">
                    <?php echo $__env->make('core::partials.feedback', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php echo $__env->yieldContent('content'); ?>
                </main>
                <footer class="app-page-footer"><span>&copy; <?php echo e($layout['year']); ?> <a href="https://greensoftwarebd.com" target="_blank">Green Software & Technology. All Rights Reserved</a></span></footer>
            </div>
        <?php else: ?>
            <main class="app-auth-layout d-grid w-100" id="main-content">
                <header class="app-auth-branding">
                    <a  class="d-flex justify-content-center align-items-center gap-2 text-decoration-none" href="<?php echo e($layout['loginUrl']); ?>">
                         <?php if($layout['logoUrl']): ?>
                            <img class="app-auth-logo flex-shrink-0 object-fit-contain" src="<?php echo e($layout['logoUrl']); ?>" width="50" height="50" alt="">
                        <?php else: ?>
                            <span class="app-auth-monogram d-inline-flex flex-shrink-0 align-items-center justify-content-center" aria-hidden="true"><?php echo e($layout['appInitial']); ?></span>
                        <?php endif; ?>
                       <span class="app-auth-name"><?php echo e($layout['appName']); ?></span>
                    </a>

                    
                </header>
                <section class="app-auth-form" aria-label="Sign in">
                    <div class="w-100">
                        <?php echo $__env->make('core::partials.feedback', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php echo $__env->yieldContent('content'); ?>
                    </div>
                </section>
                <footer class="app-auth-copyright text-center m-0">
                    &copy; <?php echo e($layout['year']); ?> <a href="https://greensoftwarebd.com" target="_blank">Green Software & Technology. All Rights Reserved</a>
                </footer>
            </main>
        <?php endif; ?>
    </body>
</html>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\app\Providers/../../resources/views/layouts/app.blade.php ENDPATH**/ ?>
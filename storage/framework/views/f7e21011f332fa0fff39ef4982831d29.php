<?php if($paginator->total() > 0): ?>
    <?php
        $startPage = max(1, min($paginator->currentPage() - 2, $paginator->lastPage() - 4));
        $endPage = min($paginator->lastPage(), $startPage + 4);
    ?>
    <nav aria-label="Table pagination">
        <ul class="pagination pagination-sm flex-wrap mb-0">
            <?php if($paginator->onFirstPage()): ?>
                <li class="page-item disabled" aria-disabled="true"><span class="page-link" aria-label="First page">&laquo;</span></li>
                <li class="page-item disabled" aria-disabled="true"><span class="page-link" aria-label="Previous page">&lsaquo;</span></li>
            <?php else: ?>
                <li class="page-item"><a class="page-link" href="<?php echo e($paginator->url(1)); ?>" aria-label="First page">&laquo;</a></li>
                <li class="page-item"><a class="page-link" href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev" aria-label="Previous page">&lsaquo;</a></li>
            <?php endif; ?>
            <?php $__currentLoopData = $paginator->getUrlRange($startPage, $endPage); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($page === $paginator->currentPage()): ?>
                    <li class="page-item active" aria-current="page"><span class="page-link"><?php echo e($page); ?></span></li>
                <?php else: ?>
                    <li class="page-item"><a class="page-link" href="<?php echo e($url); ?>" aria-label="Page <?php echo e($page); ?>"><?php echo e($page); ?></a></li>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php if($paginator->hasMorePages()): ?>
                <li class="page-item"><a class="page-link" href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next" aria-label="Next page">&rsaquo;</a></li>
                <li class="page-item"><a class="page-link" href="<?php echo e($paginator->url($paginator->lastPage())); ?>" aria-label="Last page">&raquo;</a></li>
            <?php else: ?>
                <li class="page-item disabled" aria-disabled="true"><span class="page-link" aria-label="Next page">&rsaquo;</span></li>
                <li class="page-item disabled" aria-disabled="true"><span class="page-link" aria-label="Last page">&raquo;</span></li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Core\resources\views\partials\page-links.blade.php ENDPATH**/ ?>


<?php $__env->startSection('content'); ?>
    <faculty-load prop-acad-years='<?php echo json_encode($acadYears, 15, 512) ?>'>
    </faculty-load>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\wayne\Desktop\GIthub\decision_support\resources\views/cpanel/faculty/faculty-load.blade.php ENDPATH**/ ?>
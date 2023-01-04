

<?php $__env->startSection('content'); ?>
    <enrolment prop-acad-years='<?php echo json_encode($acadyears, 15, 512) ?>'></enrolment>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\eshen\Desktop\Github\decision_support\resources\views/cpanel/enrolment/enrolment.blade.php ENDPATH**/ ?>


<?php $__env->startSection('content'); ?>
    <?php if(isset($data)): ?>
        <enrolment-create-edit 
            prop-data='<?php echo json_encode($data, 15, 512) ?>'
            prop-acad-years='<?php echo json_encode($acadYears, 15, 512) ?>' 
            prop-programs='<?php echo json_encode($programs, 15, 512) ?>'>
        </enrolment-create-edit>
    <?php else: ?>
        <enrolment-create-edit
             prop-acad-years='<?php echo json_encode($acadYears, 15, 512) ?>'
            prop-programs='<?php echo json_encode($programs, 15, 512) ?>'>
        </enrolment-create-edit>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\eshen\Desktop\Github\decision_support\resources\views/cpanel/enrolment/enrolment-create-edit.blade.php ENDPATH**/ ?>
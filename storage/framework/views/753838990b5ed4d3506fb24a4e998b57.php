<?php
    $examination = $prescription->examination_details;
    $pulse = $examination?->pulse ?? null;
    $pulseRhythm = collect([
        'regular' => 'Regular',
        'irregular' => 'Irregular',
        'drop_beat' => 'Drop-beat',
    ])->get($examination?->pulse_rhythm ?? '', '');
    if (filled($pulse) && filled($pulseRhythm)) {
        $pulse .= ' ('.$pulseRhythm.')';
    }

    $temperature = $examination?->temperature ?? null;
    if ($examination?->temperature_normal ?? false) {
        $temperature = 'Normal';
    } elseif (filled($temperature)) {
        $temperature .= ' °'.strtoupper($examination?->temperature_unit ?? 'c');
    }

    $heightVisible = $examination?->height_visible ?? true;
    $odWithoutGlasses = data_get($examination, 'visual_acuity.od_without_glasses');
    $odWithGlasses = data_get($examination, 'visual_acuity.od_with_glasses');
    $osWithoutGlasses = data_get($examination, 'visual_acuity.os_without_glasses');
    $osWithGlasses = data_get($examination, 'visual_acuity.os_with_glasses');
    $measurements = collect([
        'V/A (Without / With Glasses)' => collect([
            'OD' => filled($odWithoutGlasses) || filled($odWithGlasses)
                ? (filled($odWithoutGlasses) ? $odWithoutGlasses : '—').' / '.(filled($odWithGlasses) ? $odWithGlasses : '—')
                : null,
            'OS' => filled($osWithoutGlasses) || filled($osWithGlasses)
                ? (filled($osWithoutGlasses) ? $osWithoutGlasses : '—').' / '.(filled($osWithGlasses) ? $osWithGlasses : '—')
                : null,
        ])->filter('filled'),
        'A/S' => collect([
            'OD (Right Eye)' => data_get($examination, 'anterior_segment.od'),
            'OS (Left Eye)' => data_get($examination, 'anterior_segment.os'),
        ])->filter('filled'),
        'IOP (mmHg)' => collect([
            'OD' => data_get($examination, 'iop.od'),
            'OS' => data_get($examination, 'iop.os'),
        ])->filter('filled'),
        'FL Stain' => collect([
            'OD' => data_get($examination, 'fluorescein_stain.od'),
            'OS' => data_get($examination, 'fluorescein_stain.os'),
        ])->filter('filled'),
        "Schirmer's Test (mm)" => collect([
            'OD' => data_get($examination, 'schirmer_test.od'),
            'OS' => data_get($examination, 'schirmer_test.os'),
        ])->filter('filled'),
        'Fundus Oculi' => collect([
            'OD' => data_get($examination, 'fundus_oculi.od'),
            'OS' => data_get($examination, 'fundus_oculi.os'),
        ])->filter('filled'),
        'General' => collect([
            'BP systolic' => $examination?->bp_systolic ?? null,
            'BP diastolic' => $examination?->bp_diastolic ?? null,
            'Pulse' => $pulse,
            'Temperature' => $temperature,
            'Weight' => ($examination?->weight_visible ?? true) ? ($examination?->weight ?? null) : null,
            'Height (feet)' => $heightVisible ? ($examination?->height_feet ?? null) : null,
            'Height (inches)' => $heightVisible ? ($examination?->height_inches ?? null) : null,
            'SpO2' => $examination?->spo2 ?? null,
            'BMI' => $examination?->bmi ?? null,
            'BSA' => $examination?->bsa ?? null,
            'BMR' => $examination?->bmr ?? null,
            'TDEE' => $examination?->tdee ?? null,
            'Activity' => $examination?->activity ?? null,
            'Ideal weight (kg)' => $examination?->ideal_weight ?? null,
            'Ideal BMI' => $examination?->ideal_bmi ?? null,
            'Extra calories' => $examination?->extra_calories ?? null,
            'Target daily calories' => $examination?->target_calories ?? null,
        ])->filter('filled'),
    ]);
    $hasMeasurements = $measurements->contains(fn ($group) => $group->isNotEmpty());
?>

<?php if(filled($prescription->examination) || $hasMeasurements): ?>
    <section class="min-height100">
        <h3>O/E :</h3>
        <div class="mb-1">
            <?php if(filled($prescription->examination)): ?>
                <?php echo $__env->make('prescription::prints.clinical-lines', [
                    'text' => $prescription->examination,
                    'details' => $examinationNotes,
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
            <?php $__currentLoopData = $measurements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $heading => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($group->isNotEmpty()): ?>
                    <div class="app-prescription-preview-group mt-2">
                        <h4><?php echo e($heading); ?></h4>
                        <?php $__currentLoopData = $group; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <p class="app-prescription-text mb-0"><?php echo e($label); ?>: <?php echo e($value); ?></p>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </section>
<?php endif; ?>
<?php /**PATH D:\laragon\www\laravel_project\prof_matin_eye_hospital\Modules\Prescription\resources\views\prints\examination.blade.php ENDPATH**/ ?>
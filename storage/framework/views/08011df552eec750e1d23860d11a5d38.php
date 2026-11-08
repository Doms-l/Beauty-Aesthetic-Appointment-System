<?php
    $mapUrl = config('clinic.map_embed_url');
    $mapUrl = $mapUrl ?: 'https://www.google.com/maps?q=' . urlencode(config('clinic.map_query')) . '&output=embed';
    $q = urlencode(config('clinic.map_query'));
    $openUrl = 'https://www.google.com/maps/search/?api=1&query=' . $q;
    $dirUrl = 'https://www.google.com/maps/dir/?api=1&destination=' . $q;
?>

<style>
    .clinic-map-section { padding: 55px 0; background: var(--surface-soft, #fff8f0); }
    .clinic-map-section .clinic-map-head { text-align: center; margin-bottom: 22px; }
    .clinic-map-section h2 { margin: 8px 0; color: var(--heading, #4a2f38); }
    .clinic-map-section p { margin: 0; color: var(--muted, #7a5b64); }
    .clinic-map-frame {
        max-width: 1000px; margin: 0 auto; border-radius: 18px; overflow: hidden;
        border: 1px solid var(--line, #ecd9d2); box-shadow: var(--shadow, 0 10px 30px rgba(0,0,0,.08));
    }
    .clinic-map-frame iframe { display: block; width: 100%; height: 420px; border: 0; }
    .clinic-address-link { color: inherit; text-decoration: none; }
    .clinic-address-link:hover { text-decoration: underline; }
    .clinic-map-link { text-align: center; margin-top: 16px; display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; }
    .clinic-map-btn { padding: 10px 22px; border-radius: 30px; border: 2px solid var(--mauve, #b5577a); color: var(--mauve, #b5577a); font-weight: 700; text-decoration: none; }
    .clinic-map-btn-solid { background: var(--mauve, #b5577a); color: #fff; }
    @media (max-width: 600px) { .clinic-map-frame iframe { height: 320px; } }
</style>

<section class="clinic-map-section" id="find-us">
    <div class="container">
        <div class="clinic-map-head">
            <span class="eyebrow">FIND US</span>
            <h2><?php echo e(config('clinic.name')); ?></h2>
            <p><a class="clinic-address-link" href="<?php echo e($openUrl); ?>" target="_blank" rel="noopener noreferrer">📍 <?php echo e(config('clinic.address')); ?></a></p>
        </div>
        <div class="clinic-map-frame">
            <iframe src="<?php echo e($mapUrl); ?>" title="Clinic location map" loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <div class="clinic-map-link">
            <a class="clinic-map-btn" href="<?php echo e($openUrl); ?>" target="_blank" rel="noopener noreferrer">Open in Google Maps</a>
            <a class="clinic-map-btn clinic-map-btn-solid" href="<?php echo e($dirUrl); ?>" target="_blank" rel="noopener noreferrer">Get Directions</a>
        </div>
    </div>
</section>
<?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/partials/clinic-map.blade.php ENDPATH**/ ?>
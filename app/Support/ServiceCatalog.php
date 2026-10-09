<?php

namespace App\Support;

use Illuminate\Support\Facades\File;

/**
 * Descriptions and photos for the clinic's services.
 *
 * Used by BOTH the public Services page and the admin Services page,
 * so they always show the same picture and description.
 *
 * - description: the text typed in the admin (database) wins;
 *   if it is empty, the text below (matched by service name) is used.
 * - photo: a photo uploaded in the admin wins; otherwise the photo in
 *   public/images/<category folder>/ with the same name as the service.
 */
class ServiceCatalog
{
    public const CATEGORIES = [
        'Facial Services',
        'Lash and Brows Services',
        'Other Services',
    ];

    public static function norm(string $text): string
    {
        return preg_replace('/[^a-z0-9]/', '', strtolower($text));
    }

    /**
     * Built-in descriptions, keyed by the normalized service name.
     */
    public static function descriptions(): array
    {
        return [
            'basicfacial' => 'A Basic Facial is a gentle skincare treatment designed to cleanse and refresh the skin. It typically includes cleansing, exfoliation, and removal of surface impurities. The treatment helps remove dead skin cells and excess oil that can make the skin look dull. It leaves the skin feeling cleaner, smoother, and more refreshed.',

            'microdermabrasiondiamondpeel' => 'Microdermabrasion or Diamond Peel is a non-invasive exfoliation treatment that removes dead skin cells from the surface of the skin. It uses a specialized diamond-tipped device to gently exfoliate the skin. The treatment can help improve skin texture, smoothness, and overall appearance. It may also help make the skin look brighter and more even.',

            'acnetreatment' => 'Acne Treatment is a specialized facial treatment designed for acne-prone skin. It focuses on managing excess oil, clogged pores, blackheads, and blemishes. The treatment may include cleansing, exfoliation, extraction, and the application of products suitable for acne-prone skin. It helps promote a cleaner and healthier-looking complexion.',

            'hydrafacial' => 'A Hydra Facial is a multi-step facial treatment that cleanses, exfoliates, extracts impurities, and hydrates the skin. It uses specialized equipment to remove surface buildup and impurities from the pores. The treatment also delivers hydrating and nourishing ingredients to the skin. It leaves the skin looking smoother, fresher, and more hydrated.',

            'antiagingfacial' => 'An Anti-Aging Facial is designed to improve the appearance of visible signs of skin aging. It commonly focuses on concerns such as fine lines, dullness, uneven texture, and loss of firmness. The treatment may involve cleansing, exfoliation, massage, and the application of skincare products. It helps the skin appear smoother, brighter, and more refreshed.',

            'melasmatreatment' => 'Melasma Treatment is a skincare procedure focused on improving the appearance of uneven pigmentation and dark patches. It is commonly used for areas of the face affected by melasma. Depending on the clinic protocol, the treatment may involve topical products, exfoliation, or specialized procedures. The goal is to promote a more even-looking complexion and improve the appearance of pigmentation.',

            'picocarbonlasertreatment' => 'Pico Carbon Laser Treatment is a laser-based cosmetic procedure that uses short pulses of laser energy. It is commonly used to improve the appearance of skin tone, texture, and pigmentation. The treatment may also help remove surface impurities and promote a smoother-looking complexion. Results and the number of treatments needed may vary depending on the individual skin condition.',

            'oxygeneofacial' => 'Oxygeneo Facial is a cosmetic facial treatment designed to exfoliate and refresh the skin. It combines exfoliation with the delivery of nourishing ingredients to the skin surface. The treatment helps remove dead skin cells and improve the appearance of skin texture. It can leave the skin looking smoother, cleaner, and more refreshed.',

            'koreanbbglowbbblush' => 'Korean BB Glow + BB Blush is a cosmetic facial treatment designed to create a more even-looking and radiant complexion. It uses specialized products to give the skin a naturally tinted and glowing appearance. BB Glow focuses on improving the overall appearance of the complexion, while BB Blush adds a subtle blush-like effect. The treatment is intended to create a fresh and polished appearance.',

            'freestemcellfacial' => 'Free Stemcell Facial is a complimentary facial treatment focused on cleansing, refreshing, and nourishing the skin. It may include basic facial steps such as cleansing, exfoliation, and product application. The treatment is intended to provide a relaxing skincare experience while improving the skin overall appearance. Specific products and procedures may vary according to the clinic protocol.',

            'lashextension' => 'Lash Extension is a beauty treatment in which individual artificial lash extensions are attached to the natural eyelashes. It is designed to make the lashes appear longer, fuller, and more defined. Different lengths, thicknesses, and styles may be selected depending on the desired look. Proper application and maintenance help achieve a natural and polished appearance.',

            'lashlift' => 'Lashlift is a treatment that curls and lifts the natural eyelashes. It creates a more open and defined appearance around the eyes without adding artificial lashes. The treatment can make the natural lashes appear longer and more noticeable. Results are temporary and gradually fade as the natural lashes grow and shed.',

            'browtint' => 'Brow Tint is a semi-permanent cosmetic treatment that adds color to the eyebrow hairs. It helps enhance the natural color and definition of the brows. The tint can make the eyebrows appear fuller and more noticeable. The intensity and shade of the tint may be selected based on the client desired appearance.',

            'browlaminationwithtint' => 'Brow Lamination with Tint is a treatment that shapes and sets the eyebrow hairs while adding color. It helps create a fuller, more structured, and defined brow appearance. The brow hairs are positioned in a desired direction and treated with specialized products. The addition of tint enhances the color and overall definition of the eyebrows.',

            'microblading' => 'Microblading is a semi-permanent cosmetic eyebrow technique that creates fine, hair-like strokes. A specialized tool is used to deposit pigment into the superficial layers of the skin. The technique is designed to enhance the shape and appearance of the eyebrows. The final result can create a more defined and naturally styled brow appearance.',

            'microbrowsretouch' => 'Micro Brows Retouch is a follow-up treatment for previously completed microbladed eyebrows. It is performed to refresh areas where the pigment has faded or become less visible. The procedure can help maintain the shape, color, and definition of the brows. The need for retouching varies depending on factors such as skin type and pigment retention.',

            'lipblush' => 'Lip Blush is a semi-permanent cosmetic treatment that adds a soft tint of color to the lips. It is designed to enhance the natural appearance and definition of the lips. The procedure can help create a more even-looking lip color and improve the appearance of the lip shape. The resulting color gradually fades over time and may require maintenance.',

            'liptattoo' => 'Lip Tattoo is a cosmetic tattooing procedure that adds longer-lasting color and definition to the lips. Pigment is carefully placed into the skin to create the desired lip color or appearance. It can help enhance the visual definition of the lips and create a more consistent color. The longevity and final appearance may vary depending on skin type and aftercare.',

            'microshading' => 'Microshading is a semi-permanent eyebrow technique that creates a soft, shaded makeup effect. It uses small deposits of pigment to create a more filled-in appearance. The technique is suitable for clients who prefer brows with a makeup-inspired finish. The resulting appearance can range from soft and natural to more defined, depending on the desired style.',

            'ombreshading' => 'Ombre Shading is an eyebrow shading technique that creates a gradual color transition from lighter to darker areas. The front portion of the brows is generally designed to appear softer, while the tail is more defined. This creates a smooth and polished gradient effect. It provides the appearance of professionally applied brow makeup with a semi-permanent result.',

            'eyelinertattoo' => 'Eyeliner Tattoo is a cosmetic tattoo technique that places pigment along the lash line. It is designed to create the appearance of eyeliner without requiring daily application. The treatment can make the eyes appear more defined and enhance the appearance of the lashes. The intensity and style of the eyeliner may depend on the client preference and the clinic technique.',

            'uawaxing' => 'UA Waxing is a hair-removal treatment designed specifically for the underarm area. Warm wax is applied to the skin and removed to pull unwanted hair from the root. The treatment leaves the underarm area feeling smoother and cleaner. Regular waxing may help maintain a hair-free appearance for a period of time.',

            'legwaxing' => 'Leg Waxing is a hair-removal treatment that removes unwanted hair from the legs. Wax is applied to the skin and removed along with the hair from the root. It provides a smoother appearance compared with shaving because the hair is removed from the root. The results can vary depending on individual hair growth patterns.',

            'upperlipwax' => 'Upper Lip Wax is a quick hair-removal treatment designed for unwanted hair around the upper-lip area. Wax is carefully applied to the targeted area and removed to pull out the hair from the root. The treatment provides a clean and smoother appearance. Proper aftercare can help minimize temporary skin sensitivity after treatment.',

            'hifuface' => 'HIFU Face is a focused ultrasound treatment designed to support a firmer and tighter-looking facial appearance. It delivers focused ultrasound energy to targeted layers beneath the skin. The treatment is non-surgical and is commonly used as part of facial skin-firming procedures. The number of treatments and results can vary depending on the individual skin condition and treatment goals.',

            'gelpolish' => 'Gel Polish is a nail service that applies gel-based polish to the natural nails. The polish is cured under a special lamp to create a smooth and durable finish. It generally provides a longer-lasting appearance compared with regular nail polish. Proper application and removal help maintain the condition of the natural nails.',

            'nailextension' => 'Nail Extension is a nail enhancement service that adds length and shape to the natural fingernails. Extensions are applied using specialized nail enhancement materials and are shaped according to the client preference. The service can create a longer and more polished nail appearance. Regular maintenance may be required as the natural nails grow.',

            'toenailextension' => 'Toe Nail Extension is a nail enhancement service that adds length and shape to the toenails. It is designed to improve the appearance of short, uneven, or damaged-looking toenails. Specialized nail materials are carefully applied to create the desired shape and finish. Proper maintenance is recommended to keep the extensions looking neat and attractive.',

            'toegelpolish' => 'Toe Gel Polish is a gel polish application specifically designed for the toenails. The polish is applied and cured under a special lamp to create a smooth and durable finish. It provides the toenails with a polished and well-groomed appearance. The service is suitable for clients who want longer-lasting color on their toenails.',

            'barbiearms' => 'Barbie Arms is a beauty treatment focused on improving the appearance and smoothness of the arms. The treatment may involve specialized skincare products or cosmetic procedures depending on the clinic protocol. It is intended to help the arms look smoother, cleaner, and more polished. The specific process and expected results may vary depending on the treatment used.',

            'facebotox' => 'Face Botox is a cosmetic injectable treatment used to temporarily reduce the appearance of certain facial wrinkles. It works by relaxing selected muscles responsible for repeated facial movements. The treatment is commonly used in areas where expression lines are visible. Results are temporary and should be performed by an appropriately qualified healthcare professional.',

            'wartsremoval' => 'Warts Removal is a treatment designed to remove or reduce the appearance of unwanted warts. The procedure used may depend on the size, location, and type of wart. Specialized techniques may be used to target the affected area while protecting surrounding skin. Professional assessment is recommended before treatment to determine the appropriate approach.',

            'miliaremoval' => 'Milia Removal is a cosmetic treatment designed to remove small, keratin-filled bumps that commonly appear on the skin. The treatment focuses on carefully removing the buildup without unnecessarily damaging the surrounding skin. It can help create a smoother-looking complexion. Proper assessment and aftercare are important to reduce the risk of irritation or complications.',

            'syringomaremoval' => 'Syringoma Removal is a cosmetic treatment intended to reduce or remove small benign bumps that commonly occur around the eyes. The procedure uses a technique selected according to the location and characteristics of the bumps. The goal is to improve the appearance of the affected skin. Professional assessment is recommended because similar-looking skin growths may require different treatment approaches.',
        ];
    }

    /**
     * Different spellings of the same service.
     */
    public static function descriptionAliases(): array
    {
        return [
            'browlaminationwtint' => 'browlaminationwithtint',
            'microdermabrasiondiamondpeel' => 'microdermabrasiondiamondpeel',
            'melanooutmelasmameso' => 'melasmatreatment',
        ];
    }

    public static function descriptionFor(string $name): ?string
    {
        $key = self::norm($name);
        $key = self::descriptionAliases()[$key] ?? $key;

        return self::descriptions()[$key] ?? null;
    }

    /**
     * Finds the photo in public/images/<folder>/ that matches a service name.
     * Returns a path like "images/Facial Services/Basic Facial.jpg" or null.
     */
    public static function imageFor(string $name): ?string
    {
        static $index = null;

        if ($index === null) {

            $index = [];

            foreach (self::CATEGORIES as $folder) {

                $dir = public_path('images/' . $folder);

                if (!is_dir($dir)) {
                    continue;
                }

                foreach (File::files($dir) as $file) {
                    $index[self::norm($file->getFilenameWithoutExtension())] =
                        'images/' . $folder . '/' . $file->getFilename();
                }
            }
        }

        $aliases = [
            self::norm('Brow Lamination W/Tint') => self::norm('Brow Lamination with Tint'),
            self::norm('Upper Lip Wax') => self::norm('Upper Lip Removal'),
            self::norm('Melano Out Melasma Meso') => self::norm('Melasma Treatment'),
        ];

        $key = self::norm($name);

        if (isset($aliases[$key], $index[$aliases[$key]])) {
            return $index[$aliases[$key]];
        }

        if (isset($index[$key])) {
            return $index[$key];
        }

        $best = null;
        $bestLen = 0;

        foreach ($index as $k => $path) {

            $match = (strlen($k) >= 5 && str_starts_with($key, $k))
                  || (strlen($key) >= 6 && str_starts_with($k, $key));

            if ($match && strlen($k) > $bestLen) {
                $best = $path;
                $bestLen = strlen($k);
            }
        }

        return $best;
    }

    /**
     * Turns "images/Facial Services/Basic Facial.jpg" into a safe URL.
     */
    public static function url(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        return asset(implode('/', array_map('rawurlencode', explode('/', $path))));
    }
}

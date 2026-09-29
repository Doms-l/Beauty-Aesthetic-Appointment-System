<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Official M. Cares services and prices
     * based on the provided clinic pricelist.
     */
    public function run(): void
    {
        $services = [

            /*
            |--------------------------------------------------------------------------
            | FACIAL SERVICES
            |--------------------------------------------------------------------------
            */

            [
                'category' => 'Facial Services',
                'name' => 'Basic Facial',
                'price' => 350,
                'price_display' => '₱350',
            ],

            [
                'category' => 'Facial Services',
                'name' => 'Microdermabrasion / Diamond Peel',
                'price' => 499,
                'price_display' => '₱499',
            ],

            [
                'category' => 'Facial Services',
                'name' => 'Acne Treatment',
                'price' => 799,
                'price_display' => '₱799',
            ],

            [
                'category' => 'Facial Services',
                'name' => 'Hydra Facial',
                'price' => 999,
                'price_display' => '₱999',
            ],

            [
                'category' => 'Facial Services',
                'name' => 'Anti Aging Facial',
                'price' => 699,
                'price_display' => '₱699',
            ],

            [
                'category' => 'Facial Services',
                'name' => 'Melasma Treatment',
                'price' => 599,
                'price_display' => '₱599',
            ],

            [
                'category' => 'Facial Services',
                'name' => 'Pico Carbon Laser Treatment',
                'price' => 1999,
                'price_display' => '₱1,999',
            ],

            [
                'category' => 'Facial Services',
                'name' => 'Oxygeneo Facial',
                'price' => 1999,
                'price_display' => '₱1,999',
            ],

            [
                'category' => 'Facial Services',
                'name' => 'Korean BB Glow + BB Blush',
                'price' => 1499,
                'price_display' => '₱1,499',
            ],

            [
                'category' => 'Facial Services',
                'name' => 'Free Stemcell Facial',
                'price' => 3999,
                'price_display' => '₱3,999 for 4 sessions',
            ],


            /*
            |--------------------------------------------------------------------------
            | LASH AND BROWS SERVICES
            |--------------------------------------------------------------------------
            */

            [
                'category' => 'Lash and Brows Services',
                'name' => 'Lash Extension',
                'price' => 199,
                'price_display' => '₱199–₱350',
            ],

            [
                'category' => 'Lash and Brows Services',
                'name' => 'Lashlift',
                'price' => 350,
                'price_display' => '₱350',
            ],

            [
                'category' => 'Lash and Brows Services',
                'name' => 'Brow Tint',
                'price' => 150,
                'price_display' => '₱150',
            ],

            [
                'category' => 'Lash and Brows Services',
                'name' => 'Brow Lamination W/Tint',
                'price' => 350,
                'price_display' => '₱350',
            ],

            [
                'category' => 'Lash and Brows Services',
                'name' => 'Microblading',
                'price' => 2500,
                'price_display' => '₱2,500',
            ],

            [
                'category' => 'Lash and Brows Services',
                'name' => 'Micro Brows Retouch',
                'price' => 1500,
                'price_display' => '₱1,500',
            ],

            [
                'category' => 'Lash and Brows Services',
                'name' => 'Lip Blush',
                'price' => 2500,
                'price_display' => '₱2,500',
            ],

            [
                'category' => 'Lash and Brows Services',
                'name' => 'Lip Tattoo',
                'price' => 3500,
                'price_display' => '₱3,500',
            ],

            [
                'category' => 'Lash and Brows Services',
                'name' => 'Microshading',
                'price' => 3000,
                'price_display' => '₱3,000',
            ],

            [
                'category' => 'Lash and Brows Services',
                'name' => 'Ombre Shading',
                'price' => 3500,
                'price_display' => '₱3,500',
            ],

            [
                'category' => 'Lash and Brows Services',
                'name' => 'Eyeliner Tattoo',
                'price' => 1999,
                'price_display' => '₱1,999',
            ],


            /*
            |--------------------------------------------------------------------------
            | OTHER SERVICES
            |--------------------------------------------------------------------------
            */

            [
                'category' => 'Other Services',
                'name' => 'UA Waxing',
                'price' => 250,
                'price_display' => '₱250',
            ],

            [
                'category' => 'Other Services',
                'name' => 'Leg Waxing',
                'price' => 500,
                'price_display' => '₱500',
            ],

            [
                'category' => 'Other Services',
                'name' => 'Upper Lip Wax',
                'price' => 250,
                'price_display' => '₱250',
            ],

            [
                'category' => 'Other Services',
                'name' => 'UA IPL Laser Hair Removal',
                'price' => 500,
                'price_display' => '₱500',
            ],

            [
                'category' => 'Other Services',
                'name' => 'Body IPL Laser Hair Removal',
                'price' => 999,
                'price_display' => '₱999',
            ],

            [
                'category' => 'Other Services',
                'name' => 'UA Whitening',
                'price' => 350,
                'price_display' => '₱350',
            ],

            [
                'category' => 'Other Services',
                'name' => 'RF Face',
                'price' => 350,
                'price_display' => '₱350',
            ],

            [
                'category' => 'Other Services',
                'name' => 'RF Body',
                'price' => 550,
                'price_display' => '₱550',
            ],

            [
                'category' => 'Other Services',
                'name' => 'HIFU Face',
                'price' => 999,
                'price_display' => '₱999',
            ],

            [
                'category' => 'Other Services',
                'name' => 'Gel Polish',
                'price' => 350,
                'price_display' => '₱350',
            ],

            [
                'category' => 'Other Services',
                'name' => 'Nail Extension',
                'price' => 550,
                'price_display' => '₱550',
            ],

            [
                'category' => 'Other Services',
                'name' => 'Toe Nail Extension',
                'price' => 650,
                'price_display' => '₱650',
            ],

            [
                'category' => 'Other Services',
                'name' => 'Toe Gel Polish',
                'price' => 499,
                'price_display' => '₱499',
            ],

            [
                'category' => 'Other Services',
                'name' => 'Barbie Arms',
                'price' => 2499,
                'price_display' => '₱2,499',
            ],

            [
                'category' => 'Other Services',
                'name' => 'Face Botox',
                'price' => 7999,
                'price_display' => '₱7,999',
            ],

            [
                'category' => 'Other Services',
                'name' => 'Warts Removal',
                'price' => 599,
                'price_display' => '₱599',
            ],

            [
                'category' => 'Other Services',
                'name' => 'Milia Removal',
                'price' => 799,
                'price_display' => '₱799',
            ],

            [
                'category' => 'Other Services',
                'name' => 'Syringoma Removal',
                'price' => 899,
                'price_display' => '₱899',
            ],

            [
                'category' => 'Other Services',
                'name' => 'Skin Tag Removal',
                'price' => 699,
                'price_display' => '₱699',
            ],

            [
                'category' => 'Other Services',
                'name' => 'Tattoo Removal',
                'price' => 500,
                'price_display' => '₱500–₱2,000',
            ],

            [
                'category' => 'Other Services',
                'name' => 'Scar Camouflage',
                'price' => 3500,
                'price_display' => '₱3,500',
            ],

            [
                'category' => 'Other Services',
                'name' => 'Glutadrip',
                'price' => 1999,
                'price_display' => '₱1,999',
            ],

            [
                'category' => 'Other Services',
                'name' => 'Thermage',
                'price' => 4999,
                'price_display' => '₱4,999',
            ],

            [
                'category' => 'Other Services',
                'name' => 'Melano Out Melasma Meso',
                'price' => 4999,
                'price_display' => '₱4,999',
            ],

            [
                'category' => 'Other Services',
                'name' => 'Vitamin A (Acne Breakouts)',
                'price' => 499,
                'price_display' => '₱499',
            ],

            [
                'category' => 'Other Services',
                'name' => 'Hair Treatments',
                'price' => 300,
                'price_display' => '₱300–₱600',
            ],

            [
                'category' => 'Other Services',
                'name' => 'Rebond',
                'price' => 999,
                'price_display' => '₱999',
            ],

            [
                'category' => 'Other Services',
                'name' => 'Footspa',
                'price' => 300,
                'price_display' => '₱300',
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                [
                    'name' => $service['name'],
                ],
                [
                    'category' => $service['category'],
                    'description' => null,
                    'price' => $service['price'],
                    'price_display' => $service['price_display'],
                    'duration_minutes' => 60,
                    'is_available' => true,
                ]
            );
        }
    }
}
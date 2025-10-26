<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Trainer;
use App\Models\Gym;

class TrainerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all gyms
        $gyms = Gym::all();
        
        if ($gyms->isEmpty()) {
            $this->command->warn('No gyms found. Please create gyms first.');
            return;
        }

        $trainers = [
            [
                'name' => 'John "Thunder" Smith',
                'specialization' => 'Strength & Conditioning',
                'email' => 'john.smith@gym.com',
                'phone' => '+250788123456',
                'bio' => 'Former Olympic weightlifter with 15 years of experience in strength training. Specializes in powerlifting, Olympic lifts, and athletic performance. Certified Sports Nutritionist.',
                'certifications' => 'NSCA-CSCS, USAW Level 2, Precision Nutrition Level 1',
                'salary' => 800000,
            ],
            [
                'name' => 'Sarah Martinez',
                'specialization' => 'Yoga & Pilates',
                'email' => 'sarah.martinez@gym.com',
                'phone' => '+250788234567',
                'bio' => 'RYT-500 certified yoga instructor with a passion for holistic wellness. Combines traditional yoga practices with modern Pilates techniques for complete mind-body transformation.',
                'certifications' => 'RYT-500, Pilates Mat Certification, Prenatal Yoga Certified',
                'salary' => 600000,
            ],
            [
                'name' => 'Mike "The Beast" Johnson',
                'specialization' => 'CrossFit & HIIT',
                'email' => 'mike.johnson@gym.com',
                'phone' => '+250788345678',
                'bio' => 'CrossFit Level 3 trainer and former Marine. Expert in high-intensity functional training, metabolic conditioning, and military-style bootcamps. Get ready to push your limits!',
                'certifications' => 'CrossFit Level 3, TRX Certified, First Aid/CPR',
                'salary' => 750000,
            ],
            [
                'name' => 'Emma Chen',
                'specialization' => 'Personal Training & Weight Loss',
                'email' => 'emma.chen@gym.com',
                'phone' => '+250788456789',
                'bio' => 'Certified personal trainer specializing in weight loss transformation and body recomposition. Helped over 200 clients achieve their fitness goals through personalized training programs.',
                'certifications' => 'NASM-CPT, Certified Nutrition Coach, TRX Specialist',
                'salary' => 650000,
            ],
            [
                'name' => 'David "Coach D" Williams',
                'specialization' => 'Sports Performance',
                'email' => 'david.williams@gym.com',
                'phone' => '+250788567890',
                'bio' => 'Former professional athlete turned performance coach. Specializes in speed, agility, and sport-specific training for athletes at all levels. Works with local football and basketball teams.',
                'certifications' => 'CSCS, USA Track & Field Level 2, FMS Certified',
                'salary' => 850000,
            ],
            [
                'name' => 'Lisa Thompson',
                'specialization' => 'Group Fitness & Dance',
                'email' => 'lisa.thompson@gym.com',
                'phone' => '+250788678901',
                'bio' => 'Dynamic group fitness instructor with 10 years of experience. Teaches Zumba, Body Pump, Spin classes, and dance fitness. Making workouts fun and effective!',
                'certifications' => 'ACE Group Fitness, Zumba Instructor, Spinning Certified',
                'salary' => 550000,
            ],
        ];

        foreach ($trainers as $index => $trainerData) {
            // Assign to gym (distribute evenly across gyms)
            $gym = $gyms[$index % $gyms->count()];
            $trainerData['gym_id'] = $gym->id;
            
            Trainer::create($trainerData);
        }

        $this->command->info('Successfully seeded ' . count($trainers) . ' trainers!');
    }
}

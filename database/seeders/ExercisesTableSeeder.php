<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Exercise;

class ExercisesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Exercise::create([
            'name' => 'Push-ups',
            'description' => 'Zet je handen en voeten op de grond en laat jezelf naar beneden zakken. Druk jezelf hierna weer omhoog.',
            'vertaling_en' => 'Place your hands and feet on the ground and lower yourself down. Push yourself back up.',
            'image' => './images/pushups.jpeg',
        ]);

        Exercise::create([
            'name' => 'Squats',
            'description' => 'Zak helemaal door je knieën en weer omhoog.',
            'vertaling_en' => 'Squat all the way down and then back up.',
            'image' => './images/squats.png',
        ]);

        Exercise::create([
            'name' => 'Dip',
            'description' => 'Plaats je handen op een stabiel oppervlak en laat je lichaam zakken, vervolgens druk je jezelf omhoog.',
            'vertaling_en' => 'Place your hands on a stable surface and lower your body, then push yourself up.',
            'image' => './images/dips.jpg',
        ]);

        Exercise::create([
            'name' => 'Plank',
            'description' => 'Houd je lichaam recht en steun op je onderarmen en tenen.',
            'vertaling_en' => 'Keep your body straight, supporting yourself on your forearms and toes.',
            'image' => './images/plank.jpg',
        ]);

        Exercise::create([
            'name' => 'Paardentrap',
            'description' => 'Begin op handen en knieën en trap met één been omhoog achter je.',
            'vertaling_en' => 'Start on hands and knees and kick one leg up behind you.',
            'image' => 'paardentrap.jpg',
        ]);
        
        Exercise::create([
            'name' => 'Mountain climber',
            'description' => 'Begin in een push-up positie en breng afwisselend je knieën naar je borst.',
            'vertaling_en' => 'Start in a push-up position and alternately bring your knees towards your chest.',
            'image' => 'mountain_climber.jpg',
        ]);
        
        Exercise::create([
            'name' => 'Burpee',
            'description' => 'Begin staand, zak naar een squat, plaats je handen op de grond, spring je benen naar achteren, doe een push-up, spring terug naar een squat en spring omhoog.',
            'vertaling_en' => 'Start standing, squat down, place your hands on the ground, jump your legs back, do a push-up, jump back to a squat, and jump up.',
            'image' => 'burpee.jpg',
        ]);
        
        Exercise::create([
            'name' => 'Lunge',
            'description' => 'Stap met één voet naar voren en buig beide knieën, houd je bovenlichaam recht.',
            'vertaling_en' => 'Step forward with one foot and bend both knees, keeping your upper body straight.',
            'image' => 'lunge.jpg',
        ]);
        
        Exercise::create([
            'name' => 'Wall sit',
            'description' => 'Leun tegen een muur en zak door je knieën tot je in een zittende positie zit, zonder stoel.',
            'vertaling_en' => 'Lean against a wall and lower yourself into a sitting position with your knees bent, without a chair.',
            'image' => 'wall_sit.jpg',
        ]);
        
        Exercise::create([
            'name' => 'Crunch',
            'description' => 'Ga op je rug liggen, buig je knieën, plaats je handen achter je hoofd en rol je bovenlichaam omhoog richting je knieën.',
            'vertaling_en' => 'Lie on your back, bend your knees, place your hands behind your head, and lift your upper body towards your knees.',
            'image' => 'crunch.jpg',
        ]);
    }
}

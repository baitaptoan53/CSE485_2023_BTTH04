<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;



/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class StudentsFactory extends Factory
{
    protected $model = Student::class;
    
    public function definition(): array
    {
        return [
            'student_name' => $this->faker->name,
            'student_email' => $this->faker->unique()->safeEmail,
            'student_gender' => $this->faker->randomElement(['male', 'female']),
            'student_image' => $this->faker->image('public/storage/images', 640, 480, null, false)
        ];
    }
}

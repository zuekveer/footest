<?php

namespace Database\Factories;

use App\Models\Notebook;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotebookFactory extends Factory
{
    protected $model = Notebook::class;

    public function definition()
    {
        return [
            'fio' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'company' => $this->faker->company,
            'birth_date' => $this->faker->date(),
            'photo' => $this->faker->imageUrl(),
        ];
    }
}

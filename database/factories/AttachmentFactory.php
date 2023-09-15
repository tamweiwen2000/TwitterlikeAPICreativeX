<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attachment>
 */
class AttachmentFactory extends Factory
{
    public function definition()
    {
        return [
            'filename' => uniqid() . '.' . $this->faker->fileExtension(),
            'mime_type' => $this->faker->mimeType(),
            'size' => $this->faker->numberBetween(1024, 2048),
            // 'tweet_id' => $this->faker->numberBetween(1, 100),
        ];
    }
}

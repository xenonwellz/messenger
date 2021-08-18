<?php

namespace Xenonwellz\Messenger\Database\Factories;

use Xenonwellz\Messenger\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Message::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'sender_id' => '1',
            'receiver_id' => '2',
            'message' => $this->faker->text(100)
        ];
    }
}

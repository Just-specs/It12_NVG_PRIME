<?php

namespace Database\Factories;

use App\Models\DeliveryRequest;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DeliveryRequest>
 */
class DeliveryRequestFactory extends Factory
{
    protected $model = DeliveryRequest::class;

    public function definition(): array
    {
        $containerSizes = ['20ft', '40ft', '40HQ'];
        $containerTypes = ['Dry', 'Reefer', 'Open Top'];
        $contactMethods = ['mobile', 'email', 'group_chat'];
        $statuses = ['pending', 'verified', 'assigned', 'completed', 'cancelled'];

        return [
            'client_id' => Client::factory(),
            'contact_method' => $this->faker->randomElement($contactMethods),
            'atw_reference' => 'ATW-' . strtoupper($this->faker->bothify('??###')),
            'pickup_location' => $this->faker->streetAddress() . ', ' . $this->faker->city(),
            'delivery_location' => $this->faker->streetAddress() . ', ' . $this->faker->city(),
            'container_size' => $this->faker->randomElement($containerSizes),
            'container_type' => $this->faker->randomElement($containerTypes),
            'preferred_schedule' => $this->faker->dateTimeBetween('now', '+14 days'),
            'status' => $this->faker->randomElement($statuses),
            'notes' => $this->faker->optional()->sentence(),
            'atw_verified' => $this->faker->boolean(40),
        ];
    }
}

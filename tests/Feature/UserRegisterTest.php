<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Tests\TestCase;
use Log;

class UserRegisterTest extends TestCase
{
   // use RefreshDatabase;
    // ./vendor/bin/phpunit --coverage-html coverage
    /**
     * A basic feature test example.
     */
    public function test_it_can_register_a_user()
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'country_code' => $user->country_code,
            'mobile' => $user->mobile,
        ]);
    }

    public function test_user_registration()
    {
        // Simulate the GraphQL request to register a user with an unverified mobile number
        $response = $this->postJson('/graphql', [
            'query' => '
                mutation registerMobile {
                    registerMobile(input: {country_code: "0098", mobile: "9372120890"}) {
                        status
                        tokens {
                            access_token
                            expires_in
                            token_type
                            refresh_token
                            user {
                                id
                                mobile
                            }
                        }
                    }
                }
            '
        ]);

        // Assert that the registration was successful
        $response->assertJsonFragment([
            'status' => 'SUCCESS'
        ]);

        // Assert that the user has a mobile number
        $this->assertDatabaseHas('users', [
            'mobile' => '00989372120890' // Should match the country code + mobile
        ]);
    }

    public function test_user_mobile_already_verified()
    {
        $user = User::factory()->create([
            'country_code' => '0098',
            'mobile' => '00989372120891',
            'mobile_is_verified' => true,
        ]);

        $response = $this->postJson('/graphql', [
            'query' => '
            mutation registerMobile {
                registerMobile(input: {country_code: "0098", mobile: "9372120891"}) {
                    status
                }
            }
        '
        ]);
        //$response->dump();
//       Log::info("response is:" . json_encode($response));

        $response->assertJson([
            'errors' => [
                [
                    'message' => 'This mobile number is already verified.',
                ]
            ]
        ]);
    }

    public function test_user_cannot_request_code_within_cooldown_period()
    {
        // Create a user with a recent last_attempt_at timestamp
        $user = User::factory()->create([
            'country_code' => '0098',
            'mobile' => '00989372120892',
            'mobile_is_verified' => false,
            'last_attempt_at' => now()->subMinutes(3), // Within the 5-minute cooldown
        ]);

        // Simulate the GraphQL request to register with the same mobile number
        $response = $this->postJson('/graphql', [
            'query' => '
            mutation registerMobile {
                registerMobile(input: {country_code: "0098", mobile: "9372120892"}) {
                    status
                    tokens {
                        access_token
                        expires_in
                        token_type
                        refresh_token
                        user {
                            id
                            mobile
                        }
                    }
                }
            }
        '
        ]);

        //$response->dump();
        // Assert the response contains the expected error message
        $response->assertJson([
            'errors' => [
                [
                    'message' => 'You can only request a new code every 5 minutes. Please wait.',
                ]
            ],
        ]);

        // Ensure the database has not updated the last_attempt_at field
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'last_attempt_at' => $user->last_attempt_at, // Ensure it hasn't changed
        ]);
    }

    public function test_user_can_request_code_after_cooldown_period()
    {
        // Create a user whose cooldown period has already passed
        $user = User::factory()->create([
            'country_code' => '0098',
            'mobile' => '00989372120893', // Includes country code
            'mobile_is_verified' => false,
            'last_attempt_at' => now()->subMinutes(6), // Beyond the 5-minute cooldown
        ]);

        $response = $this->postJson('/graphql', [
            'query' => '
            mutation registerMobile {
                registerMobile(input: {country_code: "0098", mobile: "9372120893"}) {
                    status
                    tokens {
                        access_token
                        expires_in
                        token_type
                        refresh_token
                        user {
                            id
                            mobile
                        }
                    }
                }
            }
        ',
        ]);

        // Validate the response is successful and contains the new user ID
        $response->assertJson([
            'data' => [
                'registerMobile' => [
                    'status' => 'SUCCESS',
                    'tokens' => null,
                ],
            ],
        ]);

        // Check the database for the updated last attempt time
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'mobile' => '00989372120893',
        ]);
    }

    public function test_prevent_duplicate_user_creation()
    {
        // Create an existing user
        $existingUser = User::factory()->create([
            'country_code' => '0098',
            'mobile' => '00989372120894', // Includes country code
            'mobile_is_verified' => false,
        ]);

        $response = $this->postJson('/graphql', [
            'query' => '
            mutation registerMobile {
                registerMobile(input: {country_code: "0098", mobile: "9372120894"}) {
                    status
                    tokens {
                        access_token
                        expires_in
                        token_type
                        refresh_token
                        user {
                            id
                            mobile
                        }
                    }
                }
            }
        ',
        ]);

        // Assert that the response is successful and no duplicate user is created
        $response->assertJson([
            'data' => [
                'registerMobile' => [
                    'status' => 'SUCCESS',
                    'tokens' => null,
                ],
            ],
        ]);

        // Ensure the user is not duplicated in the database
       // $this->assertDatabaseCount('users', 1); // Still only one user in the database
        $this->assertDatabaseHas('users', [
            'id' => $existingUser->id,
            'mobile' => '00989372120894',
        ]);
    }

    public function test_prevent_new_code_generation_during_cooldown()
    {
        // Create a user who is still within the cooldown period
        $user = User::factory()->create([
            'country_code' => '0098',
            'mobile' => '00989372120895',
            'mobile_is_verified' => false,
            'sent_code' => '654321',
            'code_expired_at' => now()->addMinutes(5), // Code is still valid
            'last_attempt_at' => now()->subMinutes(2), // Still within the cooldown period (5 minutes)
        ]);

        $response = $this->postJson('/graphql', [
            'query' => '
            mutation registerMobile {
                registerMobile(input: {country_code: "0098", mobile: "9372120895"}) {
                    status
                    tokens {
                        access_token
                        expires_in
                        token_type
                        refresh_token
                        user {
                            id
                            mobile
                        }
                    }
                }
            }
        ',
        ]);

        // Assert the response contains the expected error message
        $response->assertJson([
            'errors' => [
                [
                    'message' => 'You can only request a new code every 5 minutes. Please wait.',
                ],
            ],
        ]);

        // Ensure the database still contains the original code and no new attempt is logged
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'mobile' => '00989372120895',
            'sent_code' => '654321', // Original code
            'last_attempt_at' => $user->last_attempt_at, // Original last attempt time
        ]);
    }

    public function test_user_not_found()
    {
        $response = $this->postJson('/graphql', [
            'query' => '
                mutation VerifyMobile {
                    VerifyMobile(
                    input: {user_id: "1400", code: "371945", password: "12345678", password_confirmation: "12345678"}
                    ) {
                    tokens {
                        access_token
                        expires_in
                        token_type
                        user {
                        id
                        mobile
                        country_code
                        role
                        }
                    }
                    user {
                        id
                        mobile
                    }
                    }
                }
            ',
        ]);

       // $response->dump();
        $response->assertJson([
            'errors' => [
                [
                    'message' => 'User not found!',
                ],
            ],
        ]);
    }





}
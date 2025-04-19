<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Tests\TestCase;
use App\GraphQL\Enums\UserStatus;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;


use Carbon\Carbon;
use Log;

class UserChangePasswordTest extends TestCase
{
    // use RefreshDatabase;
    // ./vendor/bin/phpunit --coverage-html coverage
    /**
     * A basic feature test example.
     */

    public function test_change_password()
    {
        // First, create a user
        $user = User::factory()->create([
            'mobile' => '00989372120790',
            'country_code' => '0098',
            'password' => Hash::make('12345678'),
            'status' => UserStatus::Active,
            'mobile_is_verified' => true,
        ]);

//       Log::info('Personal access client ID: ' . Passport::personalAccessClient()->getKey());
//       Log::info('Personal access client secret: ' . Passport::personalAccessClient()->secret);


        // Generate a token for the user (mock the token or use Passport/Token generation)
        $token = $user->createToken('TestApp')->accessToken;

//       Log::info("the user token is :" . json_encode($token));

        // Send the GraphQL request to request a new verification code using postJson
        $response = $this->postJson('/graphql', [
            'query' => '
             mutation changePassword {
                 changePassword(input: {country_code: "0098", mobile: "9372120790"}) {
                     status
                     tokens {
                         access_token
                     }
                 }
             }',
        ], [
            'Authorization' => "Bearer {$token}",  // Send the token in the header
        ]);

//       Log::info("GraphQL Response: " . json_encode($response));

        //$response->dump();
        // Assert the response status is successful
        $response->assertJson([
            'data' => [
                'changePassword' => [
                    'status' => 'SUCCESS',
                    'tokens' => null
                ]
            ]
        ]);

        // Now, retrieve the user again to ensure the code and expiration time are set
        $user = $user->fresh();

        // Generate the expected 6-digit code
        $expectedCode = $user->sent_code;

        // Check if the sent code is a 6-digit number
        $this->assertMatchesRegularExpression('/^\d{6}$/', $expectedCode, 'The sent code should be a 6-digit number.');

        // Check if the code expiration time is set correctly and is in the future
        $this->assertNotNull($user->code_expired_at, 'The code expiration time should be set.');
        $this->assertTrue(Carbon::parse($user->code_expired_at)->isFuture(), 'The code expiration time should be in the future.');
    }

    public function test_change_password_user_not_loggedin()
    {
        // Create a user without logging them in
        $user = User::factory()->create([
            'mobile' => '00989372120791',
            'country_code' => '0098',
            'password' => Hash::make('12345678'),
            'status' => UserStatus::Active,
            'mobile_is_verified' => true,
        ]);

        // Attempt to request a verification code without authentication (no token)
        $response = $this->postJson('/graphql', [
            'query' => '
        mutation changePassword {
            changePassword(input: {country_code: "0098", mobile: "9372120791"}) {
                status
            }
        }',
        ]);

        // $response->dump();
        // Assert that the response indicates an error (e.g., "Unauthenticated" or "Forbidden")
        $response->assertJson([
            'errors' => [
                ['message' => 'Unauthenticated.']
            ]
        ]);
    }

    public function test_correct_6_digit_code_sent()
    {
        // Create a user
        $user = User::factory()->create([
            'mobile' => '00989372120792',
            'country_code' => '0098',
            'password' => Hash::make('12345678'),
            'status' => UserStatus::Active,
            'mobile_is_verified' => true,
        ]);

        // Simulate the password change request
        $response = $this->postJson('/graphql', [
            'query' => '
        mutation changePassword {
            changePassword(input: {country_code: "0098", mobile: "9372120792"}) {
                status
            }
        }',
        ], [
            'Authorization' => "Bearer {$user->createToken('TestApp')->accessToken}",
        ]);

        // Reload the user from the database to check if sent_code is set
        $user = $user->fresh();

        // Log the sent code to verify it's being set
//       Log::info('Sent code: ' . $user->sent_code);

        // Ensure the sent code is a 6-digit number
        $this->assertMatchesRegularExpression('/^\d{6}$/', $user->sent_code, 'The sent code should be a 6-digit number.');
    }

    public function test_code_expiration_format()
    {
        // Create a user
        $user = User::factory()->create([
            'mobile' => '00989372120793',
            'country_code' => '0098',
            'password' => Hash::make('12345678'),
            'status' => UserStatus::Active,
            'mobile_is_verified' => true,
        ]);

        // Simulate the password change request
        $response = $this->postJson('/graphql', [
            'query' => '
        mutation changePassword {
            changePassword(input: {country_code: "0098", mobile: "9372120793"}) {
                status
            }
        }',
        ], [
            'Authorization' => "Bearer {$user->createToken('TestApp')->accessToken}",
        ]);


        $user = $user->fresh();

        // Ensure the expiration time is in the correct format (Y-m-d H:i:s)
        $this->assertTrue(
            Carbon::hasFormat($user->code_expired_at, 'Y-m-d H:i:s'),
            'Expiration code format is incorrect'
        );
    }



    public function test_a_user_cannot_change_others_password()
    {
        // Create two users
        $user1 = User::factory()->create([
            'mobile' => '00989372120794',
            'country_code' => '0098',
            'password' => Hash::make('12345678'),
            'status' => UserStatus::Active,
            'mobile_is_verified' => true,
        ]);

        $user2 = User::factory()->create([
            'mobile' => '00989372120795',
            'country_code' => '0098',
            'password' => Hash::make('password123'),
            'status' => UserStatus::Active,
            'mobile_is_verified' => true,
        ]);

        $user1 = $user1->fresh();
        $user2 = $user2->fresh();

        // Simulate an attempt by user2 to change user1's password (unauthorized)
        $response = $this->postJson('/graphql', [
            'query' => '
        mutation changePassword {
            changePassword(input: {country_code: "0098", mobile: "9372120794"}) {
                status
            }
        }',
        ], [
            'Authorization' => "Bearer {$user2->createToken('TestApp')->accessToken}",
        ]);

        // $response->dump();
        // Assert that the response contains the access denied message
        $response->assertJson([
            'errors' => [
                ['message' => 'access denied!']
            ]
        ]);
    }


    public function test_user_not_found_error()
    {
        // Create a user (but not the one being searched for)
        $user = User::factory()->create([
            'mobile' => '00989372120796',
            'country_code' => '0098',
            'password' => Hash::make('12345678'),
            'status' => UserStatus::Active,
            'mobile_is_verified' => true,
        ]);

        // Simulate a request for a non-existing user
        $response = $this->postJson('/graphql', [
            'query' => '
        mutation changePassword {
            changePassword(input: {country_code: "0098", mobile: "9372120797"}) {
                status
            }
        }',
        ], [
            'Authorization' => "Bearer {$user->createToken('TestApp')->accessToken}",
        ]);

        // Assert that the response contains the "User not found" message
        $response->assertJson([
            'errors' => [
                ['message' => 'User not found']
            ]
        ]);
    }

    public function test_user_can_change_password_with_valid_code()
    {
        // Create a user
        $user = User::factory()->create([
            'mobile' => '00989372120798',
            'country_code' => '0098',
            'password' => Hash::make('oldpassword'),
            'status' => UserStatus::Active,
            'mobile_is_verified' => true,
        ]);

        $user = $user->fresh();

        // First step: Request verification code using the changePassword mutation
        $response = $this->postJson('/graphql', [
            'query' => '
            mutation changePassword {
                changePassword(input: {country_code: "0098", mobile: "9372120798"}) {
                    status
                    tokens {
                        access_token
                    }
                }
            }',
        ], [
            'Authorization' => "Bearer {$user->createToken('TestApp')->accessToken}",
        ]);


        //$response->dump();
        // Assert that the response is successful
        $response->assertJson([
            'data' => [
                'changePassword' => [
                    'status' => 'SUCCESS',
                    'tokens' => null,  // Tokens will be null for this step
                ]
            ]
        ]);

        // Assume the verification code was sent (you may retrieve the code from the database, etc.)
        $user = $user->fresh();
        $verificationCode = $user->sent_code; // This is the sent verification code

        // Wait before proceeding (simulate the user waiting for 1 minute or within 5 minutes)
        Carbon::setTestNow(Carbon::now()->addMinutes(1));  // Simulate 1 minute later

        // Second step: Verify the code and change the password
        $response = $this->postJson('/graphql', [
            'query' => '
            mutation changePasswordVerifyCode {
                changePasswordVerifyCode(input: {country_code: "0098", mobile: "9372120798", code: "' . $verificationCode . '", password: "12345678", password_confirmation: "12345678"}) {
                    status
                    tokens {
                        access_token
                    }
                }
            }',
        ], [
            'Authorization' => "Bearer {$user->createToken('TestApp')->accessToken}",
        ]);


        //$response->dump();

        // Assert that the response status is success and access token is returned
        $response->assertJson([
            'data' => [
                'changePasswordVerifyCode' => [
                    'status' => 'SUCCESS',
                    'tokens' => [
                        'access_token' => true,
                    ]
                ]
            ]
        ]);

        // Reload user and check if the password was updated
        $user = $user->fresh();
        $this->assertTrue(Hash::check('12345678', $user->password));  // Ensure the password is correctly hashed
    }

    public function test_user_cannot_change_password_with_invalid_code()
    {
        // Create a user
        $user = User::factory()->create([
            'mobile' => '00989372120799',
            'country_code' => '0098',
            'password' => Hash::make('oldpassword'),
            'status' => UserStatus::Active,
            'mobile_is_verified' => true,
        ]);

        // First step: Request verification code using the changePassword mutation
        $response = $this->postJson('/graphql', [
            'query' => '
            mutation changePassword {
                changePassword(input: {country_code: "0098", mobile: "9372120799"}) {
                    status
                    tokens {
                        access_token
                    }
                }
            }',
        ], [
            'Authorization' => "Bearer {$user->createToken('TestApp')->accessToken}",
        ]);

        //$response->dump();

        // Assert that the response is successful
        $response->assertJson([
            'data' => [
                'changePassword' => [
                    'status' => 'SUCCESS',
                    'tokens' => null,  // Tokens will be null for this step
                ]
            ]
        ]);

        // Assume the verification code was sent and we retrieve the code for testing
        $user = $user->fresh();
        $verificationCode = $user->sent_code; // The correct verification code sent to the user

        // Wait before proceeding (simulate the user waiting for 1 minute or within 5 minutes)
        Carbon::setTestNow(Carbon::now()->addMinutes(1));  // Simulate 1 minute later

        // Second step: Try to verify the code with an invalid (incorrect) code
        $response = $this->postJson('/graphql', [
            'query' => '
            mutation changePasswordVerifyCode {
                changePasswordVerifyCode(input: {country_code: "0098", mobile: "9372120799", code: "123456", password: "newpassword", password_confirmation: "newpassword"}) {
                    status
                    tokens {
                        access_token
                    }
                }
            }',
        ], [
            'Authorization' => "Bearer {$user->createToken('TestApp')->accessToken}",
        ]);

        //$response->dump();

        // Assert that the response status indicates an error (invalid code)
        $response->assertJson([
            'errors' => [
                [
                    'message' => 'User not found',
                ]
            ]
        ]);

        // Reload user to ensure the password has not been changed
        $user = $user->fresh();
        $this->assertTrue(Hash::check('oldpassword', $user->password));  // Ensure the password remains the same
    }

    public function test_user_cannot_change_password_with_expired_code()
    {
        // Create a user
        $user = User::factory()->create([
            'mobile' => '00989372120780',
            'country_code' => '0098',
            'password' => Hash::make('oldpassword'),
            'status' => UserStatus::Active,
            'mobile_is_verified' => true,
        ]);

        // First step: Request verification code using the changePassword mutation
        $response = $this->postJson('/graphql', [
            'query' => '
        mutation changePassword {
            changePassword(input: {country_code: "0098", mobile: "9372120780"}) {
                status
                tokens {
                    access_token
                }
            }
        }',
        ], [
            'Authorization' => "Bearer {$user->createToken('TestApp')->accessToken}",
        ]);

        // Assert that the response is successful
        $response->assertJson([
            'data' => [
                'changePassword' => [
                    'status' => 'SUCCESS',
                    'tokens' => null,  // Tokens will be null for this step
                ]
            ]
        ]);

        // Assume the verification code was sent and we retrieve the code for testing
        $user = $user->fresh();
        $verificationCode = $user->sent_code; // The correct verification code sent to the user

        // Simulate the expiration of the verification code by moving time 10 minutes ahead
        Carbon::setTestNow(Carbon::now()->addMinutes(10));  // Simulate code expiration (after 5 minutes)

        // Second step: Try to verify the code after it has expired
        $response = $this->postJson('/graphql', [
            'query' => '
        mutation changePasswordVerifyCode {
            changePasswordVerifyCode(input: {country_code: "0098", mobile: "9372120780", code: "' . $verificationCode . '", password: "newpassword", password_confirmation: "newpassword"}) {
                status
                tokens {
                    access_token
                }
            }
        }',
        ], [
            'Authorization' => "Bearer {$user->createToken('TestApp')->accessToken}",
        ]);

        // Assert that the response contains an error indicating the code is expired
        $response->assertJsonStructure([
            'errors' => [
                [
                    'message'
                ]
            ]
        ]);

        // Check if the error message matches "The code is expired. Please send the code again."
        $response->assertJsonFragment([
            'message' => 'The code is expired. Please send the code again.'
        ]);

        // Reload user to ensure the password has NOT been changed
        $user = $user->fresh();
        $this->assertTrue(Hash::check('oldpassword', $user->password));  // Ensure the password remains the same
    }


    public function test_user_cannot_change_password_with_code_expired_after_exactly_5_minutes()
    {
        // Create a user
        $user = User::factory()->create([
            'mobile' => '00989372120781',
            'country_code' => '0098',
            'password' => Hash::make('oldpassword'),
            'status' => UserStatus::Active,
            'mobile_is_verified' => true,
        ]);

        // First step: Request verification code using the changePassword mutation
        $response = $this->postJson('/graphql', [
            'query' => '
        mutation changePassword {
            changePassword(input: {country_code: "0098", mobile: "9372120781"}) {
                status
                tokens {
                    access_token
                }
            }
        }',
        ], [
            'Authorization' => "Bearer {$user->createToken('TestApp')->accessToken}",
        ]);

        // Assert that the response is successful and returns status
        $response->assertJson([
            'data' => [
                'changePassword' => [
                    'status' => 'SUCCESS',
                    'tokens' => null,  // Tokens will be null for this step
                ]
            ]
        ]);

        // Assume the verification code was sent and we retrieve the code for testing
        $user = $user->fresh();
        $verificationCode = $user->sent_code; // The correct verification code sent to the user

        // Set the expiration time to exactly 5 minutes in the future
        $user->code_expired_at = Carbon::now()->addMinutes(5);
        $user->save();

        // Now, simulate the exact 5-minute mark by waiting for 5 minutes
        Carbon::setTestNow(Carbon::now()->addMinutes(5));

        // Second step: Try to verify the code right after 5 minutes (exactly at expiration time)
        $response = $this->postJson('/graphql', [
            'query' => '
        mutation changePasswordVerifyCode {
            changePasswordVerifyCode(input: {country_code: "0098", mobile: "9372120781", code: "' . $verificationCode . '", password: "newpassword", password_confirmation: "newpassword"}) {
                status
                tokens {
                    access_token
                }
            }
        }',
        ], [
            'Authorization' => "Bearer {$user->createToken('TestApp')->accessToken}",
        ]);

        // Assert that the response contains an error indicating the code has expired
        $response->assertJsonStructure([
            'errors' => [
                [
                    'message'
                ]
            ]
        ]);

        // Check if the error message matches "The code is expired. Please send the code again."
        $response->assertJsonFragment([
            'message' => 'The code is expired. Please send the code again.'
        ]);

        // Reload user to ensure the password has NOT been changed
        $user = $user->fresh();
        $this->assertTrue(Hash::check('oldpassword', $user->password));  // Ensure the password remains the same
    }

    public function test_user_must_request_new_code_after_password_change()
    {
        // Step 1: Create a user
        $user = User::factory()->create([
            'mobile' => '00989372120782',
            'country_code' => '0098',
            'password' => Hash::make('oldpassword'),
            'status' => UserStatus::Active,
            'mobile_is_verified' => true,
        ]);

        // Simulate the first request for password change and get a code
        $response = $this->postJson('/graphql', [
            'query' => '
            mutation changePassword {
                changePassword(input: {country_code: "0098", mobile: "9372120782"}) {
                    status
                    tokens {
                        access_token
                    }
                }
            }',
        ], [
            'Authorization' => "Bearer {$user->createToken('TestApp')->accessToken}",
        ]);

        // Assert that the code has been sent successfully
        $response->assertJson([
            'data' => [
                'changePassword' => [
                    'status' => 'SUCCESS',
                    'tokens' => null,  // No token returned yet
                ]
            ]
        ]);

        // Retrieve the verification code that was sent
        $user = $user->fresh();
        $verificationCode = $user->sent_code; // Correct code sent to the user

        // Step 2: Change password with the correct code (1st attempt)
        $response = $this->postJson('/graphql', [
            'query' => '
            mutation changePasswordVerifyCode {
                changePasswordVerifyCode(input: {country_code: "0098", mobile: "9372120782", code: "' . $verificationCode . '", password: "newpassword1", password_confirmation: "newpassword1"}) {
                    status
                    tokens {
                        access_token
                    }
                }
            }',
        ], [
            'Authorization' => "Bearer {$user->createToken('TestApp')->accessToken}",
        ]);

        // Assert that the password change was successful (1st attempt)
        $response->assertJson([
            'data' => [
                'changePasswordVerifyCode' => [
                    'status' => 'SUCCESS',
                    'tokens' => [
                        'access_token' => true,
                    ]
                ]
            ]
        ]);

        // Step 3: Attempt to use the expired code again (should fail)
        $response = $this->postJson('/graphql', [
            'query' => '
            mutation changePasswordVerifyCode {
                changePasswordVerifyCode(input: {country_code: "0098", mobile: "9372120782", code: "' . $verificationCode . '", password: "newpassword2", password_confirmation: "newpassword2"}) {
                    status
                    tokens {
                        access_token
                    }
                }
            }',
        ], [
            'Authorization' => "Bearer {$user->createToken('TestApp')->accessToken}",
        ]);

        // Assert that the response contains an error about the expired code
        $response->assertJsonStructure([
            'errors' => [
                [
                    'message',
                ]
            ]
        ]);

        // Check that the error message indicates that the code has expired
        $response->assertJsonFragment([
            'message' => 'The code is expired. Please send the code again.'
        ]);

        // Step 4: Request a new code (via `changePassword` mutation)
        $response = $this->postJson('/graphql', [
            'query' => '
            mutation changePassword {
                changePassword(input: {country_code: "0098", mobile: "9372120782"}) {
                    status
                    tokens {
                        access_token
                    }
                }
            }',
        ], [
            'Authorization' => "Bearer {$user->createToken('TestApp')->accessToken}",
        ]);

        // Assert that a new code is sent successfully
        $response->assertJson([
            'data' => [
                'changePassword' => [
                    'status' => 'SUCCESS',
                    'tokens' => null,  // No token returned yet
                ]
            ]
        ]);

        // Retrieve the new verification code
        $user = $user->fresh();
        $newVerificationCode = $user->sent_code; // New code sent to the user

        // Step 5: Change password again with the new code
        $response = $this->postJson('/graphql', [
            'query' => '
            mutation changePasswordVerifyCode {
                changePasswordVerifyCode(input: {country_code: "0098", mobile: "9372120782", code: "' . $newVerificationCode . '", password: "newpassword3", password_confirmation: "newpassword3"}) {
                    status
                    tokens {
                        access_token
                    }
                }
            }',
        ], [
            'Authorization' => "Bearer {$user->createToken('TestApp')->accessToken}",
        ]);

        // Assert that the password change is successful with the new code
        $response->assertJson([
            'data' => [
                'changePasswordVerifyCode' => [
                    'status' => 'SUCCESS',
                    'tokens' => [
                        'access_token' => true,
                    ]
                ]
            ]
        ]);

        // Reload user and check if password is updated correctly
        $user = $user->fresh();
        $this->assertTrue(Hash::check('newpassword3', $user->password));  // Ensure the password is updated
    }


    public function test_user_can_change_password_only_twice_per_day()
    {
        // Step 1: Create a user
        $user = User::factory()->create([
            'mobile' => '00989372120783',
            'country_code' => '0098',
            'password' => Hash::make('oldpassword'),
            'status' => UserStatus::Active,
            'mobile_is_verified' => true,
        ]);

        // Step 2: Send code for the first time using changePassword mutation
        $response = $this->postJson('/graphql', [
            'query' => '
            mutation changePassword {
                changePassword(input: {country_code: "0098", mobile: "9372120783"}) {
                    status
                    tokens {
                        access_token
                    }
                }
            }',
        ], [
            'Authorization' => "Bearer {$user->createToken('TestApp')->accessToken}",
        ]);

        // Assert that the code has been sent successfully
        $response->assertJson([
            'data' => [
                'changePassword' => [
                    'status' => 'SUCCESS',
                    'tokens' => null,  // No token returned yet
                ]
            ]
        ]);

        // Retrieve the verification code that was sent
        $user = $user->fresh();
        $verificationCode = $user->sent_code; // Correct code sent to the user

        // Step 3: Change password with the correct code (1st attempt)
        $response = $this->postJson('/graphql', [
            'query' => '
            mutation changePasswordVerifyCode {
                changePasswordVerifyCode(input: {country_code: "0098", mobile: "9372120783", code: "' . $verificationCode . '", password: "newpassword1", password_confirmation: "newpassword1"}) {
                    status
                    tokens {
                        access_token
                    }
                }
            }',
        ], [
            'Authorization' => "Bearer {$user->createToken('TestApp')->accessToken}",
        ]);

        // Assert that the password change was successful (1st attempt)
        $response->assertJson([
            'data' => [
                'changePasswordVerifyCode' => [
                    'status' => 'SUCCESS',
                    'tokens' => [
                        'access_token' => true,
                    ]
                ]
            ]
        ]);

        // Step 4: Send code for the second time using changePassword mutation
        $response = $this->postJson('/graphql', [
            'query' => '
            mutation changePassword {
                changePassword(input: {country_code: "0098", mobile: "9372120783"}) {
                    status
                    tokens {
                        access_token
                    }
                }
            }',
        ], [
            'Authorization' => "Bearer {$user->createToken('TestApp')->accessToken}",
        ]);

        // Assert that the code has been sent successfully for the second time
        $response->assertJson([
            'data' => [
                'changePassword' => [
                    'status' => 'SUCCESS',
                    'tokens' => null,  // No token returned yet
                ]
            ]
        ]);

        // Retrieve the verification code that was sent again
        $user = $user->fresh();
        $verificationCode = $user->sent_code; // Correct code sent to the user

        // Step 5: Change password with the correct code (2nd attempt)
        $response = $this->postJson('/graphql', [
            'query' => '
            mutation changePasswordVerifyCode {
                changePasswordVerifyCode(input: {country_code: "0098", mobile: "9372120783", code: "' . $verificationCode . '", password: "newpassword2", password_confirmation: "newpassword2"}) {
                    status
                    tokens {
                        access_token
                    }
                }
            }',
        ], [
            'Authorization' => "Bearer {$user->createToken('TestApp')->accessToken}",
        ]);

        // Assert that the password change was successful (2nd attempt)
        $response->assertJson([
            'data' => [
                'changePasswordVerifyCode' => [
                    'status' => 'SUCCESS',
                    'tokens' => [
                        'access_token' => true,
                    ]
                ]
            ]
        ]);

        // Step 6: Try to change the password a third time on the same day (should fail)
        $response = $this->postJson('/graphql', [
            'query' => '
            mutation changePassword {
                changePassword(input: {country_code: "0098", mobile: "9372120783"}) {
                    status
                    tokens {
                        access_token
                    }
                }
            }',
        ], [
            'Authorization' => "Bearer {$user->createToken('TestApp')->accessToken}",
        ]);

        // Assert that the response indicates the code was sent again
        $response->assertJson([
            'data' => [
                'changePassword' => [
                    'status' => 'SUCCESS',
                    'tokens' => null,  // No token returned yet
                ]
            ]
        ]);

        // Retrieve the verification code that was sent again
        $user = $user->fresh();
        $verificationCode = $user->sent_code; // Correct code sent to the user

        // Step 7: Try to change the password again with the code (3rd attempt)
        $response = $this->postJson('/graphql', [
            'query' => '
            mutation changePasswordVerifyCode {
                changePasswordVerifyCode(input: {country_code: "0098", mobile: "9372120783", code: "' . $verificationCode . '", password: "newpassword3", password_confirmation: "newpassword3"}) {
                    status
                    tokens {
                        access_token
                    }
                }
            }',
        ], [
            'Authorization' => "Bearer {$user->createToken('TestApp')->accessToken}",
        ]);

        // Assert that the third password change attempt fails
        $response->assertJsonStructure([
            'errors' => [
                [
                    'message',
                ]
            ]
        ]);

        // Check that the error message indicates that the user has exceeded the limit
        $response->assertJsonFragment([
            'message' => 'You cannot change your password more than 2 times in a day.'
        ]);
    }


    public function test_password_change_attempts_reset_at_the_start_of_new_day()
    {
        // Create a user with initial password change attempts set to 0 (no change attempted yet)
        $user = User::factory()->create([
            'mobile' => '00989372120784',
            'country_code' => '0098',
            'password' => Hash::make('oldpassword'),
            'status' => UserStatus::Active,
            'mobile_is_verified' => true,
            //'password_change_attempts' => 0, // No password change attempt yet
            //'last_password_change_attempt' => Carbon::now()->subHours(1), // Last change attempt was 1 hour ago (for the reset logic)
        ]);

        // Step 1: Change password for the first time (before the new day starts)
        $response = $this->postJson('/graphql', [
            'query' => '
        mutation changePassword {
            changePassword(input: {country_code: "0098", mobile: "9372120784"}) {
                status
                tokens {
                    access_token
                }
            }
        }',
        ], [
            'Authorization' => "Bearer {$user->createToken('TestApp')->accessToken}",
        ]);

        // Assert that the code has been sent successfully
        $response->assertJson([
            'data' => [
                'changePassword' => [
                    'status' => 'SUCCESS',
                    'tokens' => null,  // No token returned yet
                ]
            ]
        ]);

        // Retrieve the verification code that was sent
        $user = $user->fresh();
        $verificationCode = $user->sent_code;

        // Step 2: Change password with the correct code (First attempt)
        $response = $this->postJson('/graphql', [
            'query' => '
        mutation changePasswordVerifyCode {
            changePasswordVerifyCode(input: {country_code: "0098", mobile: "9372120784", code: "' . $verificationCode . '", password: "newpassword1", password_confirmation: "newpassword1"}) {
                status
                tokens {
                    access_token
                }
            }
        }',
        ], [
            'Authorization' => "Bearer {$user->createToken('TestApp')->accessToken}",
        ]);

        // Assert that the password is successfully changed
        $response->assertJson([
            'data' => [
                'changePasswordVerifyCode' => [
                    'status' => 'SUCCESS',
                    'tokens' => [
                        'access_token' => true,
                    ]
                ]
            ]
        ]);

        // Step 3: Attempt to change the password for the second time (before moving to the next day)
        $response = $this->postJson('/graphql', [
            'query' => '
        mutation changePassword {
            changePassword(input: {country_code: "0098", mobile: "9372120784"}) {
                status
                tokens {
                    access_token
                }
            }
        }',
        ], [
            'Authorization' => "Bearer {$user->createToken('TestApp')->accessToken}",
        ]);

        // Assert that the code has been sent successfully again (for the second password change attempt)
        $response->assertJson([
            'data' => [
                'changePassword' => [
                    'status' => 'SUCCESS',
                    'tokens' => null,  // No token returned yet
                ]
            ]
        ]);

        // Retrieve the verification code again
        $user = $user->fresh();
        $verificationCode = $user->sent_code;

        // Step 4: Change password with the correct code (Second attempt before the new day)
        $response = $this->postJson('/graphql', [
            'query' => '
        mutation changePasswordVerifyCode {
            changePasswordVerifyCode(input: {country_code: "0098", mobile: "9372120784", code: "' . $verificationCode . '", password: "newpassword2", password_confirmation: "newpassword2"}) {
                status
                tokens {
                    access_token
                }
            }
        }',
        ], [
            'Authorization' => "Bearer {$user->createToken('TestApp')->accessToken}",
        ]);

       // $response->dump();
        // Assert that the password is successfully changed
        $response->assertJson([
            'data' => [
                'changePasswordVerifyCode' => [
                    'status' => 'SUCCESS',
                    'tokens' => [
                        'access_token' => true,
                    ]
                ]
            ]
        ]);

        // Debug: Check password_change_attempts before moving to the next day
        $user = $user->fresh();
//       Log::info('Password change attempts before next day: ' . $user->password_change_attempts);

        // Step 5: Simulate the user waiting until the next day (move to the next day)
        Carbon::setTestNow(Carbon::today()->addDay()); // Set to midnight of the next day

        // Debug: Check the date change and user details
        $user = $user->fresh();
//       Log::info('Current date after manipulation: ' . Carbon::now());
//       Log::info('Password change attempts after day reset: ' . $user->password_change_attempts);

        // Step 6: Attempt to change password for the third time (now on the new day)
        $response = $this->postJson('/graphql', [
            'query' => '
        mutation changePassword {
            changePassword(input: {country_code: "0098", mobile: "9372120784"}) {
                status
                tokens {
                    access_token
                }
            }
        }',
        ], [
            'Authorization' => "Bearer {$user->createToken('TestApp')->accessToken}",
        ]);

        // Assert that the code has been sent successfully again
        $response->assertJson([
            'data' => [
                'changePassword' => [
                    'status' => 'SUCCESS',
                    'tokens' => null,  // No token returned yet
                ]
            ]
        ]);

        // Retrieve the verification code again
        $user = $user->fresh();
        $verificationCode = $user->sent_code;

        // Step 7: Change password with the correct code (Third attempt on the new day)
        $response = $this->postJson('/graphql', [
            'query' => '
        mutation changePasswordVerifyCode {
            changePasswordVerifyCode(input: {country_code: "0098", mobile: "9372120784", code: "' . $verificationCode . '", password: "newpassword3", password_confirmation: "newpassword3"}) {
                status
                tokens {
                    access_token
                }
            }
        }',
        ], [
            'Authorization' => "Bearer {$user->createToken('TestApp')->accessToken}",
        ]);

        // Assert that the password is successfully changed
        $response->assertJson([
            'data' => [
                'changePasswordVerifyCode' => [
                    'status' => 'SUCCESS',
                    'tokens' => [
                        'access_token' => true,
                    ]
                ]
            ]
        ]);

        // Final assertion: password_change_attempts should now be 1 (because the day reset)
        $user = $user->fresh();
//       Log::info('Password change attempts after third change: ' . $user->password_change_attempts);

        // Step 8: Assert that the password_change_attempts is now 1 (since we moved to the next day)
        $this->assertEquals(1, $user->password_change_attempts, 'Password change attempts should be 1 after the new day starts.');
    }

}
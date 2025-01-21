<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Tests\TestCase;
use App\GraphQL\Enums\UserStatus;
use Illuminate\Support\Facades\Hash;

use Log;

class UserLoginTest extends TestCase
{
    //use RefreshDatabase;
    // ./vendor/bin/phpunit --coverage-html coverage
    /**
     * A basic feature test example.
     */
    public function test_successful_login()
    {
        // Prepare the user data
        $user = User::factory()->create([
            'country_code' => '0098',
            'mobile' => '00989372120896',
            'password' => Hash::make('12345678'),  // Assuming bcrypt password hashing
            'mobile_is_verified' => true,  // Ensure the user has a verified mobile
            'status' => UserStatus::Active,  // Set the user's status to active
        ]);

        // Prepare the credentials
        $credentials = [
            'username' => '00989372120896',  // Username should be mobile in this case
            'password' => '12345678',
        ];


        // Send GraphQL request
        $response = $this->postJson('/graphql', [
            'query' => '
                    mutation login4 {
                                login(input: {
                                            username: "00989372120896",
                                            password: "12345678"
                                }) 
                            {
                                access_token
                                expires_in
                                refresh_token
                                token_type
                                    user {
                                        id
                                        mobile
                                        
                                        role
                                            Notifications{
                                            id
                                            message
                                            notif_status
                                        }
                                    }
                            }
                     }
                    
            ',
        ]);

        //$response->dump();

        // Check for a successful response
        $response->assertJson([
            'data' => [
                'login' => [
                    'access_token' => true, // Ensure token exists
                    'expires_in' => 86400,
                    'token_type' => 'Bearer',
                    'refresh_token' => true,
                    'user' => [
                        'id' => $user->id,
                        'mobile' => $user->mobile,
                        'role' => 'User',
                        'Notifications' => [],
                    ],
                ],
            ],
        ]);

    }


    public function test_invalid_login()
    {
        // Create a user for valid testing
        $user = User::factory()->create([
            'country_code' => '0098',
            'mobile' => '00989372120897',
            'password' => Hash::make('12345678'),  // Correct password
            'mobile_is_verified' => true,  // Ensure the user has a verified mobile
            'status' => UserStatus::Active,  // Set the user's status to active
        ]);

        // Prepare invalid credentials (wrong password)
        $credentials = [
            'username' => '00989372120897',
            'password' => 'wrongpassword',  // Incorrect password
        ];

        // Send GraphQL request with invalid credentials
        $response = $this->postJson('/graphql', [
            'query' => '
            mutation login {
                login(input: {
                    username: "00989372120897",
                    password: "wrongpassword"
                }) {
                    access_token
                    expires_in
                    refresh_token
                    token_type
                    user {
                        id
                        mobile
                        role
                        Notifications {
                            id
                            message
                            notif_status
                        }
                    }
                }
            }
        ',
        ]);

        // Assert that the response contains the expected error message
        $response->assertJson([
            'errors' => [
                [
                    'message' => 'Authentication exception',  // Match the actual error message
                    'extensions' => [
                        'reason' => 'Incorrect username or password',  // Match the reason in the response
                    ]
                ],
            ],
        ]);
    }

    public function test_invalid_login_Inactive_status()
    {
        // Create a user for valid testing
        $user = User::factory()->create([
            'country_code' => '0098',
            'mobile' => '00989372120880',
            'password' => Hash::make('12345678'),  // Correct password
            'mobile_is_verified' => true,  // Ensure the user has a verified mobile
            'status' => UserStatus::Inactive,  // Set the user's status to active
        ]);

        // Prepare invalid credentials (wrong password)
        $credentials = [
            'username' => '00989372120880',
            'password' => 'wrongpassword',  // Incorrect password
        ];

        // Send GraphQL request with invalid credentials
        $response = $this->postJson('/graphql', [
            'query' => '
            mutation login {
                login(input: {
                    username: "00989372120880",
                    password: "wrongpassword"
                }) {
                    access_token
                    expires_in
                    refresh_token
                    token_type
                    user {
                        id
                        mobile
                        role
                        Notifications {
                            id
                            message
                            notif_status
                        }
                    }
                }
            }
        ',
        ]);

        // Assert that the response contains the expected error message
        $response->assertJson([
            'errors' => [
                [
                    'message' => 'Authentication exception',  // Match the actual error message
                    'extensions' => [
                        'reason' => 'Incorrect username or password',  // Match the reason in the response
                    ]
                ],
            ],
        ]);
    }

    public function test_invalid_login_Balocked_status()
    {
        // Create a user for valid testing
        $user = User::factory()->create([
            'country_code' => '0098',
            'mobile' => '00989372120885',
            'password' => Hash::make('12345678'),  // Correct password
            'mobile_is_verified' => true,  // Ensure the user has a verified mobile
            'status' => UserStatus::Balocked,  // Set the user's status to active
        ]);

        // Prepare invalid credentials (wrong password)
        $credentials = [
            'username' => '00989372120885',
            'password' => 'wrongpassword',  // Incorrect password
        ];

        // Send GraphQL request with invalid credentials
        $response = $this->postJson('/graphql', [
            'query' => '
            mutation login {
                login(input: {
                    username: "00989372120885",
                    password: "wrongpassword"
                }) {
                    access_token
                    expires_in
                    refresh_token
                    token_type
                    user {
                        id
                        mobile
                        role
                        Notifications {
                            id
                            message
                            notif_status
                        }
                    }
                }
            }
        ',
        ]);

        // Assert that the response contains the expected error message
        $response->assertJson([
            'errors' => [
                [
                    'message' => 'Authentication exception',  // Match the actual error message
                    'extensions' => [
                        'reason' => 'Incorrect username or password',  // Match the reason in the response
                    ]
                ],
            ],
        ]);
    }

    public function test_invalid_login_Suspend_status()
    {
        // Create a user for valid testing
        $user = User::factory()->create([
            'country_code' => '0098',
            'mobile' => '00989372120881',
            'password' => Hash::make('12345678'),  // Correct password
            'mobile_is_verified' => true,  // Ensure the user has a verified mobile
            'status' => UserStatus::Suspend,  // Set the user's status to active
        ]);

        // Prepare invalid credentials (wrong password)
        $credentials = [
            'username' => '00989372120881',
            'password' => 'wrongpassword',  // Incorrect password
        ];

        // Send GraphQL request with invalid credentials
        $response = $this->postJson('/graphql', [
            'query' => '
            mutation login {
                login(input: {
                    username: "00989372120881",
                    password: "wrongpassword"
                }) {
                    access_token
                    expires_in
                    refresh_token
                    token_type
                    user {
                        id
                        mobile
                        role
                        Notifications {
                            id
                            message
                            notif_status
                        }
                    }
                }
            }
        ',
        ]);

        // Assert that the response contains the expected error message
        $response->assertJson([
            'errors' => [
                [
                    'message' => 'Authentication exception',  // Match the actual error message
                    'extensions' => [
                        'reason' => 'Incorrect username or password',  // Match the reason in the response
                    ]
                ],
            ],
        ]);
    }

    public function test_invalid_login_None_status()
    {
        // Create a user for valid testing
        $user = User::factory()->create([
            'country_code' => '0098',
            'mobile' => '00989372120882',
            'password' => Hash::make('12345678'),  // Correct password
            'mobile_is_verified' => true,  // Ensure the user has a verified mobile
            'status' => UserStatus::None,  // Set the user's status to active
        ]);

        // Prepare invalid credentials (wrong password)
        $credentials = [
            'username' => '00989372120882',
            'password' => 'wrongpassword',  // Incorrect password
        ];

        // Send GraphQL request with invalid credentials
        $response = $this->postJson('/graphql', [
            'query' => '
            mutation login {
                login(input: {
                    username: "00989372120882",
                    password: "wrongpassword"
                }) {
                    access_token
                    expires_in
                    refresh_token
                    token_type
                    user {
                        id
                        mobile
                        role
                        Notifications {
                            id
                            message
                            notif_status
                        }
                    }
                }
            }
        ',
        ]);

        // Assert that the response contains the expected error message
        $response->assertJson([
            'errors' => [
                [
                    'message' => 'Authentication exception',  // Match the actual error message
                    'extensions' => [
                        'reason' => 'Incorrect username or password',  // Match the reason in the response
                    ]
                ],
            ],
        ]);
    }

    public function test_invalid_login_New_status()
    {
        // Create a user for valid testing
        $user = User::factory()->create([
            'country_code' => '0098',
            'mobile' => '00989372120883',
            'password' => Hash::make('12345678'),  // Correct password
            'mobile_is_verified' => true,  // Ensure the user has a verified mobile
            'status' => UserStatus::New,  // Set the user's status to active
        ]);

        // Prepare invalid credentials (wrong password)
        $credentials = [
            'username' => '00989372120883',
            'password' => 'wrongpassword',  // Incorrect password
        ];

        // Send GraphQL request with invalid credentials
        $response = $this->postJson('/graphql', [
            'query' => '
            mutation login {
                login(input: {
                    username: "00989372120883",
                    password: "wrongpassword"
                }) {
                    access_token
                    expires_in
                    refresh_token
                    token_type
                    user {
                        id
                        mobile
                        role
                        Notifications {
                            id
                            message
                            notif_status
                        }
                    }
                }
            }
        ',
        ]);

        // Assert that the response contains the expected error message
        $response->assertJson([
            'errors' => [
                [
                    'message' => 'Authentication exception',  // Match the actual error message
                    'extensions' => [
                        'reason' => 'Incorrect username or password',  // Match the reason in the response
                    ]
                ],
            ],
        ]);
    }
    public function test_invalid_login_Active_status_Not_Verified()
    {
        // Create a user for valid testing
        $user = User::factory()->create([
            'country_code' => '0098',
            'mobile' => '00989372120884',
            'password' => Hash::make('12345678'),  // Correct password
            'mobile_is_verified' => false,  // Ensure the user has a verified mobile
            'status' => UserStatus::Active,  // Set the user's status to active
        ]);

        // Prepare invalid credentials (wrong password)
        $credentials = [
            'username' => '00989372120884',
            'password' => 'wrongpassword',  // Incorrect password
        ];

        // Send GraphQL request with invalid credentials
        $response = $this->postJson('/graphql', [
            'query' => '
            mutation login {
                login(input: {
                    username: "00989372120884",
                    password: "wrongpassword"
                }) {
                    access_token
                    expires_in
                    refresh_token
                    token_type
                    user {
                        id
                        mobile
                        role
                        Notifications {
                            id
                            message
                            notif_status
                        }
                    }
                }
            }
        ',
        ]);

        // Assert that the response contains the expected error message
        $response->assertJson([
            'errors' => [
                [
                    'message' => 'Authentication exception',  // Match the actual error message
                    'extensions' => [
                        'reason' => 'Incorrect username or password',  // Match the reason in the response
                    ]
                ],
            ],
        ]);
    }
    public function test_invalid_username()
    {
        // Try logging in with an invalid username
        $response = $this->postJson('/graphql', [
            'query' => '
            mutation login {
                login(input: {
                    username: "invalid_username",  
                    password: "12345678"           
                }) {
                    access_token
                    expires_in
                    refresh_token
                    token_type
                    user {
                        id
                        mobile
                        role
                        Notifications {
                            id
                            message
                            notif_status
                        }
                    }
                }
            }
        ',
        ]);

        // Assert that the response contains the expected error message
        $response->assertJson([
            'errors' => [
                [
                    'message' => 'Authentication exception',
                    'extensions' => [
                        'reason' => 'Incorrect username or password',
                    ]
                ],
            ],
        ]);
    }


    public function test_find_for_passport_with_mobile()
    {
        // Create a user in the database with specific mobile and country_code
        $user = User::factory()->create([
            'country_code' => '0098',
            'mobile' => '00989372120898',
            'password' => Hash::make('12345678'),
            'mobile_is_verified' => true,
            'status' => UserStatus::Active,
        ]);

        // Call the findForPassport method with the mobile number
        $foundUser = $user->findForPassport('00989372120898');

        // Assert that the found user is the same as the one created
        $this->assertNotNull($foundUser);
        $this->assertEquals($user->id, $foundUser->id);
        $this->assertEquals($user->mobile, $foundUser->mobile);
        $this->assertEquals($user->country_code, $foundUser->country_code);
    }




}
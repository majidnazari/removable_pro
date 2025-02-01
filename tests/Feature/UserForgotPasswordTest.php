<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Tests\TestCase;
use App\GraphQL\Enums\UserStatus;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

use Log;

class UserForgotPasswordTest extends TestCase
{


    public function test_verify_forgot_password_with_valid_code()
    {
        $user = User::factory()->create([
            'mobile' => '00989372120600',
            'country_code' => '0098',
            'password' => Hash::make('oldpassword'),
            'status' => UserStatus::Active,
            'mobile_is_verified' => true,
            'sent_code' => '123456',
            'code_expired_at' => Carbon::now()->addMinutes(5),
            'password_change_attempts' => 0,
            'last_password_change_attempt' => null,
        ]);

        $response = $this->postJson('/graphql', [
            'query' => '
        mutation verifyForgotPassword {
            verifyForgotPassword(input: {country_code: "0098", mobile: "9372120600", code: "123456", password: "newpassword", password_confirmation: "newpassword"}) {
                status
                tokens {
                    access_token
                }
            }
        }',
        ]);

        $response->dump();
        $response->assertJson([
            'data' => [
                'verifyForgotPassword' => [
                    'status' => 'SUCCESS',
                    'tokens' => [
                        'access_token' => true,
                    ],
                ],
            ],
        ]);

        $this->assertTrue(Hash::check('newpassword', $user->fresh()->password), 'Password should be updated.');
        $this->assertEquals(1, $user->fresh()->password_change_attempts, 'Password change attempts should increment.');
    }

}
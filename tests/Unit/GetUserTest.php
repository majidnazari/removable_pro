<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\GraphQL\Queries\User\GetUser;
use Mockery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Auth;
use App\GraphQL\Enums\UserStatus;
use Illuminate\Support\Facades\Hash;

class GetUserTest extends TestCase
{
    //use RefreshDatabase;  // This ensures the database is reset for each test

   
    public function test_it_returns_a_user_by_id()
    {
        // Create a user in the database
        $user = User::factory()->create([
            'mobile' => '00989372120790',
            'country_code' => '0098',
            'password' => Hash::make('12345678'),
            'status' => UserStatus::Active,
            'mobile_is_verified' => true,
        ]);
        // Mock the authenticated user (the user that is supposed to be authenticated)
        $mockAuthUser = Mockery::mock(User::class);
        
        // Mock methods that are called in the AuthorizesUser trait
        $mockAuthUser->shouldReceive('isAdmin')
            ->andReturn(true);  // or false depending on what you need for the test
        $mockAuthUser->shouldReceive('isSupporter')
            ->andReturn(false); // or true depending on your test scenario
        
        // Mock the attribute getter method for various fields
        $mockAuthUser->shouldReceive('__get')
            ->with('id')
            ->andReturn(1);
        $mockAuthUser->shouldReceive('__get')
            ->with('country_code')
            ->andReturn('0098');
        $mockAuthUser->shouldReceive('__get')
            ->with('mobile')
            ->andReturn('00989000000001');
        $mockAuthUser->shouldReceive('__get')
            ->with('role')
            ->andReturn('User');
        $mockAuthUser->shouldReceive('__get')
            ->with('status')
            ->andReturn('Active');

        // Mock the Auth facade to return the mocked user
        Auth::shouldReceive('guard')
            ->once()
            ->with('api')
            ->andReturnSelf();

        Auth::shouldReceive('user')
            ->once()
            ->andReturn($mockAuthUser);

        // Mock the GraphQLContext and ResolveInfo (which might be used for context)
        $mockContext = Mockery::mock(GraphQLContext::class);
        $resolveInfo = Mockery::mock(ResolveInfo::class);

        // Instantiate the GetUser resolver
        $resolver = new GetUser();

        // Call the resolver's method to resolve the user by ID (fetch the user created above)
        $result = $resolver->resolveUser(null, ['id' => 1], $mockContext, $resolveInfo);

        // Log the result for debugging purposes
        // \Log::info("Fetched user: " . json_encode($result));

        // Assert that the result matches the expected mock data
        $this->assertEquals(1, $result->id);
        $this->assertEquals('0098', $result->country_code);
        $this->assertEquals('00989000000001', $result->mobile);
        $this->assertEquals('User', $result->role);
        $this->assertEquals('Active', $result->status);
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_request_verify_otp_and_reset_password(): void
    {
        config(['mail.default' => 'array']);

        $user = User::factory()->create([
            'password' => Hash::make('OldPassword123!'),
        ]);

        $this->post(route('password.email'), ['email' => $user->email])
            ->assertRedirect(route('password.otp.form'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('password_reset_tokens', ['email' => strtolower($user->email)]);

        DB::table('password_reset_tokens')->where('email', strtolower($user->email))->update([
            'token' => Hash::make('123456'),
            'created_at' => now(),
        ]);

        $this->post(route('password.otp.verify'), ['otp' => '123456'])
            ->assertRedirect(route('password.reset'));



        $this->post(route('password.update'), [
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ])->assertRedirect(route('login'))
            ->assertSessionHas('success');

        $this->assertTrue(Hash::check('NewPassword123!', $user->fresh()->password));
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => strtolower($user->email)]);
    }

    public function test_incorrect_otp_does_not_allow_password_reset(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('OldPassword123!'),
        ]);

        DB::table('password_reset_tokens')->insert([
            'email' => strtolower($user->email),
            'token' => Hash::make('123456'),
            'created_at' => now(),
        ]);

        $this->withSession(['password_reset_email' => strtolower($user->email)])
            ->post(route('password.otp.verify'), ['otp' => '654321'])
            ->assertSessionHasErrors('otp');

        $this->get(route('password.reset'))->assertRedirect(route('password.request'));
        $this->assertTrue(Hash::check('OldPassword123!', $user->fresh()->password));
    }
}



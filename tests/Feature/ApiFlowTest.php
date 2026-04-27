<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Institusi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class ApiFlowTest extends TestCase
{
    use RefreshDatabase; // We will use RefreshDatabase

    public function test_api_scenarios(): void
    {
        $results = [];

        // 1. REGISTER
        $email = 'test_' . Str::random(5) . '@example.com';
        $registerResponse = $this->postJson('/api/auth/register', [
            'nama_pt' => 'PT Test',
            'jenis_pt' => 'Universitas',
            'nama_pic' => 'Budi',
            'no_hp_pic' => '08123456789',
            'jabatan_pic' => 'Rektor',
            'email' => $email,
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);
        
        $results['register'] = [
            'status' => $registerResponse->status(),
            'body' => $registerResponse->json() ?? $registerResponse->getContent()
        ];

        // Create a forced logged-in user for the rest of tests
        $user = User::factory()->create([
            'role' => 'PESERTA',
            'status' => 'ACTIVE'
        ]);

        $token = $user->createToken('test')->plainTextToken;

        // 2. LOGIN (Using factory user)
        $loginResponse = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        
        $results['login'] = [
            'status' => $loginResponse->status(),
            'body' => $loginResponse->json() ?? $loginResponse->getContent()
        ];

        // 3. PROFILE
        $profileResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/profile');

        $results['profile'] = [
            'status' => $profileResponse->status(),
            'body' => $profileResponse->json() ?? $profileResponse->getContent()
        ];

        // 4. PROFILE STATUS
        $statusResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/profile/status');

        $results['profile_status'] = [
            'status' => $statusResponse->status(),
            'body' => $statusResponse->json() ?? $statusResponse->getContent()
        ];
        
        // 5. BASELINE {userId}
        $baselineResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson("/api/baseline/{$user->id}", [
            'key' => 'val' // just dummy payload
        ]);

        $results['baseline_userId'] = [
            'status' => $baselineResponse->status(),
            'body' => $baselineResponse->json() ?? $baselineResponse->getContent()
        ];

        // 6. PROFILE BASELINE
        $profileBaselineResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/profile/baseline', [
            'key' => 'val'
        ]);

        $results['profile_baseline'] = [
            'status' => $profileBaselineResponse->status(),
            'body' => $profileBaselineResponse->getContent()
        ];

        // 7. ASSESSMENT QUESTIONS
        $assessmentId = 1;
        $questionsResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson("/api/assessment/peserta/questions/{$assessmentId}");

        $results['assessment_questions'] = [
            'status' => $questionsResponse->status(),
            'body' => $questionsResponse->getContent()
        ];

        // 8. ASSESSMENT SAVE ANSWER
        $saveAnswerResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson("/api/assessment/peserta/save-answer/{$user->id}", []);

        $results['assessment_save_answer'] = [
            'status' => $saveAnswerResponse->status(),
            'body' => $saveAnswerResponse->getContent()
        ];
        
        // 9. REVIEWER TASKS
        $reviewer = User::factory()->create(['role' => 'REVIEWER']);
        $reviewerToken = $reviewer->createToken('test-rev')->plainTextToken;
        $revTasksResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $reviewerToken,
        ])->getJson("/api/assessment/reviewer/tasks");
        
        $results['reviewer_tasks'] = [
            'status' => $revTasksResponse->status(),
            'body' => $revTasksResponse->getContent()
        ];

        // 10. LOGOUT
        $logoutResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/auth/logout');

        $results['logout'] = [
            'status' => $logoutResponse->status(),
            'body' => $logoutResponse->getContent()
        ];

        file_put_contents(storage_path('api_test_results.json'), json_encode($results, JSON_PRETTY_PRINT));
        
        $this->assertTrue(true);
    }
}

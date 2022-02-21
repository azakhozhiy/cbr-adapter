<?php

namespace Tests\Feature;

use App\Packages\CbrClient\CbrClient;
use Tests\TestCase;

class CollectCourseTest extends TestCase
{
    public function test_response_with_error(): void
    {
        $this->app->singleton(CbrClient::class, fn() => new CbrClient('http://tesdsfa123.ru'));
        $response = $this->secureRequest('GET', '/api/cbr/courses', ['startsAt' => now()->toDateString()]);
        $response->assertStatus(200);

        $error = $response->json('error');

        self::assertStringContainsString('cURL error 6:', $error);
    }

    public function test_access_denied(): void
    {
        $response = $this->json('GET', '/api/cbr/courses');

        $response->assertStatus(401)
            ->assertJson([
                'status' => 401,
                'data' => [
                    'message' => 'Access denied.',
                    'code' => 102
                ]
            ]);
    }

    public function test_get_courses_bad_request(): void
    {
        $response = $this->secureRequest('GET', '/api/cbr/courses');

        $response->assertStatus(400)
            ->assertJson([
                'status' => 400,
                'data' => [
                    'message' => 'Bad course request.',
                    'code' => 100
                ]
            ]);
    }

    public function test_get_courses_with_start_date(): void
    {
        $response = $this->secureRequest('GET', '/api/cbr/courses', [
            'startsAt' => now()->toDateString(),
        ]);

        $datesAsserts = [
            now()->format('d/m/Y') => false
        ];

        $response->assertStatus(200);

        $this->assertCourseDate($datesAsserts, $response->json('data'));
    }

    private function assertCourseDate(array $dates, array $data): void
    {
        foreach ($data as $courseItem) {
            if (isset($dates[$courseItem['date']])) {
                $dates[$courseItem['date']] = true;
            }
        }

        foreach ($dates as $item) {
            self::assertTrue($item);
        }
    }

    public function test_get_courses_period(): void
    {
        $countDays = 5;
        $startsAt = now()->subDays($countDays);
        $endsAt = now();

        $response = $this->secureRequest('GET', '/api/cbr/courses', [
            'startsAt' => $startsAt->toDateString(),
            'endsAt' => $endsAt->toDateString(),
        ]);

        $response->assertStatus(200);

        $datesAsserts = [
            $startsAt->format('d/m/Y') => false,
            $endsAt->format('d/m/Y') => false
        ];

        $this->assertCourseDate($datesAsserts, $response->json('data'));
    }

    public function test_get_courses_with_currency(): void
    {
        $now = now();

        $response = $this->secureRequest('GET', '/api/cbr/courses', [
            'startsAt' => $now->toDateString(),
            'currencyCode' => 'GBP'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                [
                    'currencyCode' => 'GBP'
                ]
            ]
        ]);
    }

    public function test_get_courses_with_currency_and_period(): void
    {
        $countRecords = 6;
        $countDays = $countRecords - 1;
        $startsAt = now()->subDays($countDays);
        $endsAt = $startsAt->copy()->addDays($countDays);

        $response = $this->secureRequest('GET', '/api/cbr/courses', [
            'startsAt' => $startsAt->toDateString(),
            'endsAt' => $endsAt->toDateString(),
            'currencyCode' => 'GBP',
        ]);

        $datesAsserts = [
            $startsAt->format('d/m/Y') => false,
            $endsAt->format('d/m/Y') => false
        ];

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                [
                    'currencyCode' => 'GBP'
                ]
            ]
        ])->assertJsonCount($countRecords, 'data');

        $this->assertCourseDate($datesAsserts, $response->json('data'));
    }
}

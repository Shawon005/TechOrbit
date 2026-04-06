<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_root_route_redirects_to_the_default_locale(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/en');
    }

    public function test_the_localized_homepage_renders_successfully(): void
    {
        $response = $this->get('/en');

        $response->assertOk();
        $response->assertSee('TechOrbit IT');
    }

    public function test_the_admin_preview_renders_successfully(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/admin/login');
    }

    public function test_the_admin_login_page_renders_successfully(): void
    {
        $response = $this->get('/admin/login');

        $response->assertOk();
        $response->assertSee('Login to Admin');
    }

    public function test_contact_submission_is_saved_to_admin_inquiries(): void
    {
        $path = storage_path('app/techorbit-content.json');
        $original = File::exists($path) ? File::get($path) : null;

        try {
            $response = $this->post('/en/contact', [
                'name' => 'Rahim Uddin',
                'email' => 'rahim@example.com',
                'phone' => '01700000000',
                'company' => 'Orbit Client',
                'service' => 'Web Development',
                'budget' => '$2,000 - $5,000',
                'message' => 'Need a custom company website with admin dashboard.',
            ]);

            $response->assertRedirect();
            $response->assertSessionHas('success');

            $stored = json_decode(File::get($path), true);

            $this->assertTrue(collect($stored['admin']['inquiries'])->contains(
                fn (array $inquiry): bool => ($inquiry['email'] ?? null) === 'rahim@example.com'
                    && ($inquiry['status'] ?? null) === 'New'
                    && ($inquiry['message'] ?? null) === 'Need a custom company website with admin dashboard.'
            ));
        } finally {
            if ($original === null) {
                File::delete($path);
            } else {
                File::put($path, $original);
            }
        }
    }
}

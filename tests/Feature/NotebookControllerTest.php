<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Notebook;


class NotebookControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_can_get_all_notebooks()
{
    // Create a notebook using the factory
    Notebook::factory()->count(10)->create();

    // Make an API request
    $response = $this->getJson('/api/v1/notebook');

    // Assert the response status is OK (200)
    $response->assertStatus(200);

    // Decode the JSON response
    $responseData = $response->json();

    // Assert that the response contains 'data' key and it's an array
    $this->assertArrayHasKey('data', $responseData);
    $this->assertIsArray($responseData['data']);  // Ensure 'data' is an array of notebooks

    // Optionally, assert the structure of the first notebook in the 'data' array
    if (count($responseData['data']) > 0) {
        $this->assertArrayHasKey('fio', $responseData['data'][0]);
        $this->assertArrayHasKey('phone', $responseData['data'][0]);
        $this->assertArrayHasKey('email', $responseData['data'][0]);
        $this->assertArrayHasKey('company', $responseData['data'][0]);
        $this->assertArrayHasKey('birth_date', $responseData['data'][0]);
        $this->assertArrayHasKey('photo', $responseData['data'][0]);
    }
}


    public function test_can_create_notebook()
{
    $data = [
        'fio' => 'John Doe',
        'phone' => '+123456789',
        'email' => 'john.doe@example.com',
        'company' => 'Company XYZ',
        'birth_date' => '1990-01-01',
        'photo' => 'http://example.com/photo.jpg',
    ];

    $response = $this->postJson('/api/v1/notebook', $data);

    // Assert that the notebook was created and returned correctly
    $response->assertStatus(201);
    $response->assertJson($data);  // Assert that the response contains the data
}

public function test_cannot_create_notebook_with_duplicate_phone()
{
    // Create a notebook
    Notebook::factory()->create([
        'phone' => '+123456789',
        'email' => 'existing@example.com',
    ]);

    // Try to create another notebook with the same phone number
    $data = [
        'fio' => 'Jane Doe',
        'phone' => '+123456789',
        'email' => 'jane.doe@example.com',
    ];

    $response = $this->postJson('/api/v1/notebook', $data);

    // Assert that the response is a validation error for phone duplication
    $response->assertStatus(422);
    $response->assertJson(['error' => 'The phone or email has already been taken.']);
}

public function test_cannot_create_notebook_with_duplicate_email()
{
    // Create a notebook
    Notebook::factory()->create([
        'phone' => '+987654321',
        'email' => 'existing@example.com',
    ]);

    // Try to create another notebook with the same email
    $data = [
        'fio' => 'Jane Doe',
        'phone' => '+987654321',
        'email' => 'existing@example.com',
    ];

    $response = $this->postJson('/api/v1/notebook', $data);

    // Assert that the response is a validation error for email duplication
    $response->assertStatus(422);
    $response->assertJson(['error' => 'The phone or email has already been taken.']);
}

public function test_can_get_single_notebook()
{
    // Create a notebook
    $notebook = Notebook::factory()->create();

    // Send GET request to fetch the notebook by its ID
    $response = $this->getJson("/api/v1/notebook/{$notebook->id}");

    // Assert the response contains the correct notebook data
    $response->assertStatus(200);
    $response->assertJson([
        'id' => $notebook->id,
        'fio' => $notebook->fio,
        'phone' => $notebook->phone,
        'email' => $notebook->email,
    ]);
}

public function test_returns_404_if_notebook_not_found()
{
    // Try fetching a non-existent notebook
    $response = $this->getJson('/api/v1/notebook/999');

    // Assert that it returns a 404 error
    $response->assertStatus(404);
    $response->assertJson(['error' => 'Notebook entry not found']);
}

public function test_can_update_notebook()
{
    // Create a notebook
    $notebook = Notebook::factory()->create();

    // Data to update the notebook
    $data = [
        'fio' => 'Updated Name',
        'phone' => '+987654321',
        'email' => 'updated@example.com',
    ];

    // Send PUT request to update the notebook
    $response = $this->putJson("/api/v1/notebook/{$notebook->id}", $data);

    // Assert that the response is successful and contains the updated data
    $response->assertStatus(200);
    $response->assertJson($data);
}

public function test_can_delete_notebook()
{
    // Create a notebook
    $notebook = Notebook::factory()->create();

    // Send DELETE request to remove the notebook
    $response = $this->deleteJson("/api/v1/notebook/{$notebook->id}");

    // Assert that the notebook was deleted successfully
    $response->assertStatus(200);
    $response->assertJson(['message' => 'Notebook entry deleted successfully']);
}

public function test_returns_404_if_notebook_not_found_on_delete()
{
    // Try deleting a non-existent notebook
    $response = $this->deleteJson('/api/v1/notebook/999');

    // Assert that it returns a 404 error
    $response->assertStatus(404);
    $response->assertJson(['error' => 'Notebook entry not found']);
}
}

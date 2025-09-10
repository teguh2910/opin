<?php

namespace Tests\Feature;

use App\Models\Bom;
use App\Models\Component;
use App\Models\Opin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BomTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test user
        $this->user = User::factory()->create();
    }

    /** @test */
    public function guests_cannot_access_bom_routes()
    {
        // Test index
        $this->get(route('bom.index'))->assertRedirect('/login');

        // Test create
        $this->get(route('bom.create'))->assertRedirect('/login');

        // Test store
        $this->post(route('bom.store'))->assertRedirect('/login');

        // Test show
        $this->get(route('bom.show', 1))->assertRedirect('/login');

        // Test edit
        $this->get(route('bom.edit', 1))->assertRedirect('/login');

        // Test update
        $this->put(route('bom.update', 1))->assertRedirect('/login');

        // Test delete
        $this->delete(route('bom.destroy', 1))->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_users_can_view_bom_index()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('bom.index'));

        $response->assertStatus(200)
            ->assertViewIs('bom.index')
            ->assertViewHas('boms');
    }

    /** @test */
    public function authenticated_users_can_view_create_form()
    {
        $this->actingAs($this->user);

        // Create some test data
        Opin::factory()->create();
        Component::factory()->create();

        $response = $this->get(route('bom.create'));

        $response->assertStatus(200)
            ->assertViewIs('bom.create')
            ->assertViewHas(['opins', 'components']);
    }

    /** @test */
    public function authenticated_users_can_create_bom()
    {
        $this->actingAs($this->user);

        $opin = Opin::factory()->create();
        $component = Component::factory()->create();

        $bomData = [
            'opin_id' => $opin->id,
            'component_id' => $component->id,
            'quantity' => 5.5,
        ];

        $response = $this->post(route('bom.store'), $bomData);

        $response->assertRedirect(route('bom.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('bill_of_materials', $bomData);
    }

    /** @test */
    public function bom_creation_requires_validation()
    {
        $this->actingAs($this->user);

        $response = $this->post(route('bom.store'), []);

        $response->assertSessionHasErrors([
            'opin_id',
            'component_id',
            'quantity',
        ]);
    }

    /** @test */
    public function bom_creation_prevents_duplicate_entries()
    {
        $this->actingAs($this->user);

        $opin = Opin::factory()->create();
        $component = Component::factory()->create();

        // Create first BOM entry
        Bom::create([
            'opin_id' => $opin->id,
            'component_id' => $component->id,
            'quantity' => 5.0,
        ]);

        // Try to create duplicate
        $duplicateData = [
            'opin_id' => $opin->id,
            'component_id' => $component->id,
            'quantity' => 3.0,
        ];

        $response = $this->post(route('bom.store'), $duplicateData);

        $response->assertRedirect()
            ->assertSessionHasErrors('duplicate');
    }

    /** @test */
    public function authenticated_users_can_view_bom_details()
    {
        $this->actingAs($this->user);

        $bom = Bom::factory()->create();

        $response = $this->get(route('bom.show', $bom));

        $response->assertStatus(200)
            ->assertViewIs('bom.show')
            ->assertViewHas('bom');
    }

    /** @test */
    public function authenticated_users_can_view_edit_form()
    {
        $this->actingAs($this->user);

        $bom = Bom::factory()->create();

        $response = $this->get(route('bom.edit', $bom));

        $response->assertStatus(200)
            ->assertViewIs('bom.edit')
            ->assertViewHas(['bom', 'opins', 'components']);
    }

    /** @test */
    public function authenticated_users_can_update_bom()
    {
        $this->actingAs($this->user);

        $bom = Bom::factory()->create();
        $newOpin = Opin::factory()->create();
        $newComponent = Component::factory()->create();

        $updatedData = [
            'opin_id' => $newOpin->id,
            'component_id' => $newComponent->id,
            'quantity' => 7.25,
        ];

        $response = $this->put(route('bom.update', $bom), $updatedData);

        $response->assertRedirect(route('bom.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('bill_of_materials', $updatedData);
    }

    /** @test */
    public function authenticated_users_can_delete_bom()
    {
        $this->actingAs($this->user);

        $bom = Bom::factory()->create();

        $response = $this->delete(route('bom.destroy', $bom));

        $response->assertRedirect(route('bom.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('bill_of_materials', ['id' => $bom->id]);
    }

    /** @test */
    public function bom_show_handles_nonexistent_record()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('bom.show', 999));

        $response->assertStatus(404);
    }

    /** @test */
    public function bom_edit_handles_nonexistent_record()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('bom.edit', 999));

        $response->assertStatus(404);
    }

    /** @test */
    public function bom_update_handles_nonexistent_record()
    {
        $this->actingAs($this->user);

        $response = $this->put(route('bom.update', 999), [
            'opin_id' => 1,
            'component_id' => 1,
            'quantity' => 5.0,
        ]);

        $response->assertStatus(404);
    }

    /** @test */
    public function bom_delete_handles_nonexistent_record()
    {
        $this->actingAs($this->user);

        $response = $this->delete(route('bom.destroy', 999));

        $response->assertStatus(404);
    }
}

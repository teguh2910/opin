<?php

namespace Tests\Feature;

use App\Models\Component;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ComponentTest extends TestCase
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
    public function guests_cannot_access_component_routes()
    {
        // Test index
        $this->get(route('component.index'))->assertRedirect('/login');

        // Test create
        $this->get(route('component.create'))->assertRedirect('/login');

        // Test store
        $this->post(route('component.store'))->assertRedirect('/login');

        // Test show
        $this->get(route('component.show', 1))->assertRedirect('/login');

        // Test edit
        $this->get(route('component.edit', 1))->assertRedirect('/login');

        // Test update
        $this->put(route('component.update', 1))->assertRedirect('/login');

        // Test delete
        $this->delete(route('component.destroy', 1))->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_users_can_view_component_index()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('component.index'));

        $response->assertStatus(200)
            ->assertViewIs('component.index')
            ->assertViewHas('components');
    }

    /** @test */
    public function authenticated_users_can_view_create_form()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('component.create'));

        $response->assertStatus(200)
            ->assertViewIs('component.create');
    }

    /** @test */
    public function authenticated_users_can_create_component()
    {
        $this->actingAs($this->user);

        $componentData = [
            'part_no' => 'TEST001',
            'part_name' => 'Test Component',
            'type' => 'rm',
            'unit_cost' => 15000.50,
            'unit' => 'pcs',
        ];

        $response = $this->post(route('component.store'), $componentData);

        $response->assertRedirect(route('component.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('components', $componentData);
    }

    /** @test */
    public function component_creation_requires_validation()
    {
        $this->actingAs($this->user);

        $response = $this->post(route('component.store'), []);

        $response->assertSessionHasErrors([
            'part_no',
            'part_name',
            'type',
            'unit_cost',
            'unit',
        ]);
    }

    /** @test */
    public function component_part_no_must_be_unique()
    {
        $this->actingAs($this->user);

        // Create first component
        Component::create([
            'part_no' => 'DUPLICATE001',
            'part_name' => 'First Component',
            'type' => 'rm',
            'unit_cost' => 10000,
            'unit' => 'pcs',
        ]);

        // Try to create duplicate
        $duplicateData = [
            'part_no' => 'DUPLICATE001',
            'part_name' => 'Second Component',
            'unit_cost' => 20000,
            'unit' => 'kg',
        ];

        $response = $this->post(route('component.store'), $duplicateData);

        $response->assertSessionHasErrors('part_no');
    }

    /** @test */
    public function component_unit_cost_must_be_numeric_and_positive()
    {
        $this->actingAs($this->user);

        $invalidData = [
            'part_no' => 'INVALID001',
            'part_name' => 'Invalid Component',
            'unit_cost' => -100,
            'unit' => 'pcs',
        ];

        $response = $this->post(route('component.store'), $invalidData);

        $response->assertSessionHasErrors('unit_cost');
    }

    /** @test */
    public function authenticated_users_can_view_component_details()
    {
        $this->actingAs($this->user);

        $component = Component::factory()->create();

        $response = $this->get(route('component.show', $component));

        $response->assertStatus(200)
            ->assertViewIs('component.show')
            ->assertViewHas('component');
    }

    /** @test */
    public function authenticated_users_can_view_edit_form()
    {
        $this->actingAs($this->user);

        $component = Component::factory()->create();

        $response = $this->get(route('component.edit', $component));

        $response->assertStatus(200)
            ->assertViewIs('component.edit')
            ->assertViewHas('component');
    }

    /** @test */
    public function authenticated_users_can_update_component()
    {
        $this->actingAs($this->user);

        $component = Component::factory()->create();

        $updatedData = [
            'part_no' => 'UPDATED001',
            'part_name' => 'Updated Component',
            'type' => 'lp',
            'unit_cost' => 25000.75,
            'unit' => 'kg',
        ];

        $response = $this->put(route('component.update', $component), $updatedData);

        $response->assertRedirect(route('component.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('components', $updatedData);
    }

    /** @test */
    public function component_update_requires_validation()
    {
        $this->actingAs($this->user);

        $component = Component::factory()->create();

        $response = $this->put(route('component.update', $component), [
            'part_no' => '',
            'part_name' => '',
            'type' => '',
            'unit_cost' => 'invalid',
            'unit' => '',
        ]);

        $response->assertSessionHasErrors([
            'part_no',
            'part_name',
            'type',
            'unit_cost',
            'unit',
        ]);
    }

    /** @test */
    public function authenticated_users_can_delete_component()
    {
        $this->actingAs($this->user);

        $component = Component::factory()->create();

        $response = $this->delete(route('component.destroy', $component));

        $response->assertRedirect(route('component.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('components', ['id' => $component->id]);
    }

    /** @test */
    public function cannot_delete_component_used_in_bom()
    {
        $this->actingAs($this->user);

        $component = Component::factory()->create();
        $opin = \App\Models\Opin::factory()->create();

        // Create a BOM entry that uses this component
        $component->billOfMaterials()->create([
            'opin_id' => $opin->id,
            'quantity' => 5.0,
        ]);

        $response = $this->delete(route('component.destroy', $component));

        $response->assertRedirect(route('component.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('components', ['id' => $component->id]);
    }

    /** @test */
    public function component_show_handles_nonexistent_record()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('component.show', 999));

        $response->assertStatus(404);
    }

    /** @test */
    public function component_edit_handles_nonexistent_record()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('component.edit', 999));

        $response->assertStatus(404);
    }

    /** @test */
    public function component_update_handles_nonexistent_record()
    {
        $this->actingAs($this->user);

        $response = $this->put(route('component.update', 999), [
            'part_no' => 'TEST001',
            'part_name' => 'Test Component',
            'type' => 'rm',
            'unit_cost' => 15000,
            'unit' => 'pcs',
        ]);

        $response->assertStatus(404);
    }

    /** @test */
    public function component_delete_handles_nonexistent_record()
    {
        $this->actingAs($this->user);

        $response = $this->delete(route('component.destroy', 999));

        $response->assertStatus(404);
    }

    /** @test */
    public function component_index_shows_empty_state_when_no_records()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('component.index'));

        $response->assertStatus(200)
            ->assertSee('No Components Found');
    }

    /** @test */
    public function component_index_displays_components_with_pagination()
    {
        $this->actingAs($this->user);

        Component::factory()->count(15)->create();

        $response = $this->get(route('component.index'));

        $response->assertStatus(200)
            ->assertViewHas('components');
    }
}

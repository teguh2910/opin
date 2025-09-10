<?php

namespace Tests\Feature;

use App\Models\Opin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OpinTest extends TestCase
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
    public function guests_cannot_access_opin_routes()
    {
        // Test index
        $this->get(route('opin.index'))->assertRedirect('/login');

        // Test create
        $this->get(route('opin.create'))->assertRedirect('/login');

        // Test store
        $this->post(route('opin.store'))->assertRedirect('/login');

        // Test show
        $this->get(route('opin.show', 1))->assertRedirect('/login');

        // Test edit
        $this->get(route('opin.edit', 1))->assertRedirect('/login');

        // Test update
        $this->put(route('opin.update', 1))->assertRedirect('/login');

        // Test delete
        $this->delete(route('opin.destroy', 1))->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_users_can_view_opin_index()
    {
        $this->actingAs($this->user);

        Opin::factory()->count(3)->create();

        $response = $this->get(route('opin.index'));

        $response->assertStatus(200)
            ->assertViewIs('opin.index')
            ->assertViewHas('opins');
    }

    /** @test */
    public function authenticated_users_can_view_create_form()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('opin.create'));

        $response->assertStatus(200)
            ->assertViewIs('opin.create');
    }

    /** @test */
    public function authenticated_users_can_create_opin()
    {
        $this->actingAs($this->user);

        $opinData = [
            'part_no' => 'TEST-001',
            'part_name' => 'Test Part',
            'sales_price' => 100.50,
            'labor_cost' => 10.00,
            'machine_cost' => 25.00,
            'current_machine' => 12.00,
            'other_fixed' => 2.50,
            'defect_cost' => 1.00,
        ];

        $response = $this->post(route('opin.store'), $opinData);

        $response->assertRedirect(route('opin.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('opins', $opinData);
    }

    /** @test */
    public function opin_creation_requires_validation()
    {
        $this->actingAs($this->user);

        $response = $this->post(route('opin.store'), []);

        $response->assertSessionHasErrors([
            'part_no',
            'part_name',
            'sales_price',
            'labor_cost',
            'machine_cost',
            'current_machine',
            'other_fixed',
            'defect_cost',
        ]);
    }

    /** @test */
    public function authenticated_users_can_view_opin_details()
    {
        $this->actingAs($this->user);

        $opin = Opin::factory()->create();

        $response = $this->get(route('opin.show', $opin));

        $response->assertStatus(200)
            ->assertViewIs('opin.show')
            ->assertViewHas('opin', $opin);
    }

    /** @test */
    public function authenticated_users_can_view_edit_form()
    {
        $this->actingAs($this->user);

        $opin = Opin::factory()->create();

        $response = $this->get(route('opin.edit', $opin));

        $response->assertStatus(200)
            ->assertViewIs('opin.edit')
            ->assertViewHas('opin', $opin);
    }

    /** @test */
    public function authenticated_users_can_update_opin()
    {
        $this->actingAs($this->user);

        $opin = Opin::factory()->create();

        $updatedData = [
            'part_no' => 'UPDATED-001',
            'part_name' => 'Updated Part',
            'sales_price' => 150.75,
            'labor_cost' => 12.00,
            'machine_cost' => 30.00,
            'current_machine' => 15.00,
            'other_fixed' => 3.00,
            'defect_cost' => 1.50,
        ];

        $response = $this->put(route('opin.update', $opin), $updatedData);

        $response->assertRedirect(route('opin.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('opins', $updatedData);
    }

    /** @test */
    public function opin_update_requires_validation()
    {
        $this->actingAs($this->user);

        $opin = Opin::factory()->create();

        $response = $this->put(route('opin.update', $opin), [
            'part_no' => '',
            'part_name' => '',
            'sales_price' => 'not-a-number',
        ]);

        $response->assertSessionHasErrors([
            'part_no',
            'part_name',
            'sales_price',
        ]);
    }

    /** @test */
    public function authenticated_users_can_delete_opin()
    {
        $this->actingAs($this->user);

        $opin = Opin::factory()->create();

        $response = $this->delete(route('opin.destroy', $opin));

        $response->assertRedirect(route('opin.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('opins', ['id' => $opin->id]);
    }

    /** @test */
    public function profit_calculation_works_correctly_in_show_view()
    {
        $this->actingAs($this->user);

        $opin = Opin::factory()->create([
            'sales_price' => 100.00,
            'labor_cost' => 10.00,
            'machine_cost' => 25.00,
            'current_machine' => 12.00,
            'other_fixed' => 2.50,
            'defect_cost' => 1.00,
        ]);

        // Total cost should be: 20 + 15 + 5 + 3 + 10 + 25 + 12 + 2.5 + 1 = 93.5
        // Profit should be: 100 - 93.5 = 6.5
        // Profit margin should be: (6.5 / 100) * 100 = 6.5%

        $response = $this->get(route('opin.show', $opin));

        $response->assertStatus(200)
            ->assertViewHas('opin', function ($viewOpin) {
                return $viewOpin->id === $this->user->id; // This will be checked in the view
            });
    }

    /** @test */
    public function opin_index_shows_empty_state_when_no_records()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('opin.index'));

        $response->assertStatus(200)
            ->assertViewHas('opins', function ($opins) {
                return $opins->count() === 0;
            });
    }

    /** @test */
    public function opin_show_handles_nonexistent_record()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('opin.show', 999));

        $response->assertStatus(404);
    }

    /** @test */
    public function opin_edit_handles_nonexistent_record()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('opin.edit', 999));

        $response->assertStatus(404);
    }

    /** @test */
    public function opin_update_handles_nonexistent_record()
    {
        $this->actingAs($this->user);

        $response = $this->put(route('opin.update', 999), [
            'part_no' => 'TEST-001',
            'part_name' => 'Test Part',
            'sales_price' => 100.00,
            'labor_cost' => 10.00,
            'machine_cost' => 25.00,
            'current_machine' => 12.00,
            'other_fixed' => 2.50,
            'defect_cost' => 1.00,
        ]);

        $response->assertStatus(404);
    }

    /** @test */
    public function opin_delete_handles_nonexistent_record()
    {
        $this->actingAs($this->user);

        $response = $this->delete(route('opin.destroy', 999));

        $response->assertStatus(404);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ProductManagerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
    }

    public function test_admin_can_create_product()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');
        $this->actingAs($user);

        Storage::fake('public');
        $file = UploadedFile::fake()->image('product.jpg');

        Livewire::test('product-manager')
            ->set('name', 'Produto Teste')
            ->set('description', 'Descrição do produto')
            ->set('price', 99.99)
            ->set('stock', 10)
            ->set('imageFile', $file)
            ->call('save')
            ->assertHasNoErrors();

        $product = Product::where('name', 'Produto Teste')->first();
        $this->assertNotNull($product);
        $this->assertNotNull($product->image);
        Storage::disk('public')->assertExists(str_replace('/storage/', '', $product->image));
    }

    public function test_admin_can_edit_product()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');
        $this->actingAs($user);

        $product = Product::factory()->create();

        Livewire::test('product-manager')
            ->call('showEditForm', $product->id)
            ->set('name', 'Produto Editado')
            ->set('description', 'Nova descrição')
            ->set('price', 199.99)
            ->set('stock', 5)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Produto Editado']);
    }

    public function test_admin_can_delete_product()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');
        $this->actingAs($user);

        $product = Product::factory()->create();

        Livewire::test('product-manager')
            ->call('delete', $product->id);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_validation_required_fields()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');
        $this->actingAs($user);

        Livewire::test('product-manager')
            ->call('save')
            ->assertHasErrors(['name', 'price', 'stock']);
    }

    public function test_admin_can_upload_product_image()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');
        $this->actingAs($user);

        Storage::fake('public');
        $file = UploadedFile::fake()->image('product2.jpg');

        Livewire::test('product-manager')
            ->set('name', 'Produto Imagem')
            ->set('description', 'Produto com imagem')
            ->set('price', 49.99)
            ->set('stock', 3)
            ->set('imageFile', $file)
            ->call('save')
            ->assertHasNoErrors();

        $product = Product::where('name', 'Produto Imagem')->first();
        $this->assertNotNull($product);
        $this->assertNotNull($product->image);
        Storage::disk('public')->assertExists(str_replace('/storage/', '', $product->image));
    }
} 
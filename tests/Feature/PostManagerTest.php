<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class PostManagerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
    }

    public function test_admin_can_create_post()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');
        $this->actingAs($user);

        Storage::fake('public');
        $file = UploadedFile::fake()->image('post.jpg');

        Livewire::test('post-manager')
            ->set('title', 'Novo Post')
            ->set('content', 'Conteúdo do post')
            ->set('status', 'published')
            ->set('imageFile', $file)
            ->call('save')
            ->assertHasNoErrors();

        $post = Post::where('title', 'Novo Post')->first();
        $this->assertNotNull($post);
        $this->assertNotNull($post->image);
        Storage::disk('public')->assertExists(str_replace('/storage/', '', $post->image));
    }

    public function test_admin_can_edit_post()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');
        $this->actingAs($user);

        $post = Post::factory()->create(['user_id' => $user->id]);

        Livewire::test('post-manager')
            ->call('showEditForm', $post->id)
            ->set('title', 'Editado')
            ->set('content', 'Novo conteúdo')
            ->set('status', 'draft')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Editado']);
    }

    public function test_admin_can_delete_post()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');
        $this->actingAs($user);

        $post = Post::factory()->create(['user_id' => $user->id]);

        Livewire::test('post-manager')
            ->call('delete', $post->id);

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_validation_required_fields()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');
        $this->actingAs($user);

        Livewire::test('post-manager')
            ->call('save')
            ->assertHasErrors(['title', 'content', 'status']);
    }
} 
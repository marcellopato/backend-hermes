<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class PostManager extends Component
{
    use WithPagination, WithFileUploads;

    public $title, $content, $status, $image, $user_id, $post_id;
    public $showForm = false;
    public $isEdit = false;
    public $search = '';
    public $orderBy = 'id';
    public $orderDirection = 'desc';
    public $perPage = 10;
    public $imageFile;

    public function mount()
    {
        if (!Auth::user() || !Auth::user()->hasAnyRole(['admin', 'manager', 'redator'])) {
            abort(403);
        }
    }

    public function render()
    {
        $query = Post::query();
        if ($this->search) {
            $query->where('title', 'ilike', "%{$this->search}%");
        }
        $posts = $query->orderBy($this->orderBy, $this->orderDirection)
            ->paginate($this->perPage);
        return view('livewire.post-manager', compact('posts'))
            ->layout('layouts.app');
    }

    public function showCreateForm()
    {
        $this->resetForm();
        $this->showForm = true;
        $this->isEdit = false;
    }

    public function showEditForm($id)
    {
        $post = Post::findOrFail($id);
        $this->post_id = $post->id;
        $this->title = $post->title;
        $this->content = $post->content;
        $this->status = $post->status;
        $this->image = $post->image;
        $this->imageFile = null;
        $this->showForm = true;
        $this->isEdit = true;
    }

    public function save()
    {
        $data = $this->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published,archived',
            'imageFile' => 'nullable|image|max:2048',
        ]);
        if ($this->imageFile) {
            $imagePath = $this->imageFile->store('posts', 'public');
            $data['image'] = '/storage/' . $imagePath;
        } else if ($this->isEdit && $this->image) {
            $data['image'] = $this->image;
        } else {
            $data['image'] = null;
        }
        unset($data['imageFile']);
        $data['user_id'] = Auth::id();
        if ($this->isEdit && $this->post_id) {
            $post = Post::findOrFail($this->post_id);
            $post->update($data);
        } else {
            Post::create($data);
        }
        $this->resetForm();
        $this->showForm = false;
        session()->flash('success', 'Post salvo com sucesso!');
    }

    public function delete($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        session()->flash('success', 'Post removido com sucesso!');
    }

    public function sortBy($field)
    {
        if ($this->orderBy === $field) {
            $this->orderDirection = $this->orderDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->orderBy = $field;
            $this->orderDirection = 'asc';
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->title = $this->content = $this->status = $this->image = $this->post_id = null;
        $this->imageFile = null;
        $this->isEdit = false;
    }
}

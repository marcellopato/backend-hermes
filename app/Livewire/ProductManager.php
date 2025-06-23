<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class ProductManager extends Component
{
    use WithPagination, WithFileUploads;

    public $name, $description, $price, $stock, $image, $product_id;
    public $isEdit = false;
    public $showForm = false;
    public $perPage = 10;
    public $search = '';
    public $orderBy = 'id';
    public $orderDirection = 'desc';
    public $minPrice = null;
    public $maxPrice = null;
    public $imageFile;

    public function mount()
    {
        $user = Auth::user();
        if (!$user || !method_exists($user, 'hasAnyRole') || !$user->hasAnyRole(['admin', 'manager', 'vendor'])) {
            abort(403);
        }
    }

    public function render()
    {
        $query = Product::query();
        if ($this->search) {
            $query->where('name', 'ilike', "%{$this->search}%");
        }
        if ($this->minPrice !== null && $this->minPrice !== '') {
            $query->where('price', '>=', $this->minPrice);
        }
        if ($this->maxPrice !== null && $this->maxPrice !== '') {
            $query->where('price', '<=', $this->maxPrice);
        }
        $products = $query->orderBy($this->orderBy, $this->orderDirection)
            ->paginate($this->perPage);
        return view('livewire.product-manager', compact('products'))
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
        $product = Product::findOrFail($id);
        $this->product_id = $product->id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->stock = $product->stock;
        $this->image = $product->image;
        $this->imageFile = null;
        $this->showForm = true;
        $this->isEdit = true;
    }

    public function save()
    {
        $data = $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'imageFile' => 'nullable|image|max:2048', // até 2MB
        ]);
        // Upload da imagem
        if ($this->imageFile) {
            $imagePath = $this->imageFile->store('products', 'public');
            $data['image'] = '/storage/' . $imagePath;
        } else if ($this->isEdit && $this->image) {
            $data['image'] = $this->image;
        } else {
            $data['image'] = null;
        }
        unset($data['imageFile']);
        if ($this->isEdit && $this->product_id) {
            $product = Product::findOrFail($this->product_id);
            $product->update($data);
        } else {
            Product::create($data);
        }
        $this->resetForm();
        $this->showForm = false;
        session()->flash('success', 'Produto salvo com sucesso!');
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        session()->flash('success', 'Produto removido com sucesso!');
    }

    public function resetForm()
    {
        $this->name = $this->description = $this->price = $this->stock = $this->image = $this->product_id = null;
        $this->imageFile = null;
        $this->isEdit = false;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingMinPrice()
    {
        $this->resetPage();
    }
    public function updatingMaxPrice()
    {
        $this->resetPage();
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
}

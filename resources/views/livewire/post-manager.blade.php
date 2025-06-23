<div>
    <div class="max-w-4xl mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">Postagens</h2>

        {{-- Filtros de pesquisa --}}
        <div class="flex flex-wrap gap-2 mb-4 items-end">
            <div>
                <label class="block text-sm font-semibold">Buscar por título</label>
                <input type="text" wire:model.debounce.500ms="search" class="border rounded px-2 py-1" placeholder="Título do post">
            </div>
        </div>

        @if (session()->has('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <button wire:click="showCreateForm" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 hover:bg-blue-700">Nova Postagem</button>

        @if ($showForm)
            <div class="bg-gray-100 p-4 rounded mb-4">
                <form wire:submit.prevent="save">
                    <div class="mb-2">
                        <label class="block font-semibold">Título</label>
                        <input type="text" wire:model.defer="title" class="w-full border rounded px-2 py-1" required>
                        @error('title') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-2" x-data x-init="window.initTinyMCE && window.initTinyMCE()">
                        <label class="block font-semibold">Conteúdo</label>
                        <livewire:jodit-text-editor wire:model.live="content" wire:key="jodit-editor-{{ $isEdit ? $post_id : 'new' }}" />
                        @error('content') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-2">
                        {{-- <label class="block font-semibold">Imagem</label> --}}
                        <x-upload wire:model="imageFile" :imageFile="$imageFile ?? null" :image="$image ?? null" />
                        @error('imageFile') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-2">
                        <label class="block font-semibold">Status</label>
                        <select wire:model.defer="status" class="w-full border rounded px-2 py-1">
                            <option value="">Selecione</option>
                            <option value="draft">Rascunho</option>
                            <option value="published">Publicado</option>
                            <option value="archived">Arquivado</option>
                        </select>
                        @error('status') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex gap-2 mt-2">
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Salvar</button>
                        <button type="button" wire:click="resetForm" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">Cancelar</button>
                    </div>
                </form>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border rounded">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('id')">
                            ID
                            @if($orderBy === 'id')
                                @if($orderDirection === 'asc') ▲ @else ▼ @endif
                            @endif
                        </th>
                        <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('title')">
                            Título
                            @if($orderBy === 'title')
                                @if($orderDirection === 'asc') ▲ @else ▼ @endif
                            @endif
                        </th>
                        <th class="px-4 py-2 border">Status</th>
                        <th class="px-4 py-2 border">Imagem</th>
                        <th class="px-4 py-2 border">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                        <tr>
                            <td class="px-4 py-2 border">{{ $post->id }}</td>
                            <td class="px-4 py-2 border">{{ $post->title }}</td>
                            <td class="px-4 py-2 border">{{ ucfirst($post->status) }}</td>
                            <td class="px-4 py-2 border">
                                @if ($post->image)
                                    <img src="{{ $post->image }}" alt="{{ $post->title }}" class="h-10 w-10 object-cover rounded">
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 border">
                                <button wire:click="showEditForm({{ $post->id }})" class="text-yellow-500 hover:text-yellow-700" title="Editar">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 inline">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487a2.1 2.1 0 1 1 2.97 2.97L7.5 19.79l-4 1 1-4 12.362-12.303z" />
                                    </svg>
                                </button>
                                <button wire:click="delete({{ $post->id }})" class="text-red-600 hover:text-red-800 ml-2" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 inline">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $posts->links() }}
        </div>
    </div>
</div>

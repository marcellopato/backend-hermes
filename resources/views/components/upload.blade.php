<div>
    <label class="block font-semibold">Imagem</label>
    <input type="file" {{ $attributes->merge(['class' => 'w-full border rounded px-2 py-1', 'accept' => 'image/*']) }}>
    <div wire:loading wire:target="{{ $attributes->get('wire:model') }}" class="flex items-center gap-2 mt-2">
        <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
        </svg>
        <span class="text-blue-600">Enviando imagem...</span>
    </div>
    @if ($imageFile ?? false)
        <div class="mt-2">
            <img src="{{ $imageFile->temporaryUrl() }}" class="h-20 w-20 object-cover rounded" />
        </div>
    @elseif ($image ?? false)
        <div class="mt-2">
            <img src="{{ $image }}" class="h-20 w-20 object-cover rounded" />
        </div>
    @endif
    @error($attributes->get('wire:model')) <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
</div> 
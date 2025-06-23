<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Diskon: ') }} {{ $discount->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.discounts.update', $discount) }}" method="POST">
                    @method('PUT')
                    @include('admin.discount._form', ['discount' => $discount])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

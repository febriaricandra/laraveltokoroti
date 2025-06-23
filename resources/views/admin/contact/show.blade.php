<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Pesan Kontak') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="mb-6">
                    <a href="{{ route('admin.contact.index') }}" 
                        class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                        ← Kembali ke Daftar Pesan
                    </a>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $contact->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $contact->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Telepon</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $contact->phone }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $contact->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Subjek</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $contact->subject }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Pesan</label>
                        <div class="mt-1 p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $contact->message }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex gap-4">
                    <!-- <a href="mailto:{{ $contact->email }}" 
                        class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                        Balas via Email
                    </a> -->
                    <form action="{{ route('admin.contact.destroy', $contact) }}" method="POST" 
                        onsubmit="return confirm('Yakin ingin menghapus pesan ini?')" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">
                            Hapus Pesan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
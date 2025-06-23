<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pesan Kontak') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-500 text-white rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="px-4 py-2 border">No</th>
                                <th class="px-4 py-2 border">Nama</th>
                                <th class="px-4 py-2 border">Email</th>
                                <th class="px-4 py-2 border">Subjek</th>
                                <th class="px-4 py-2 border">Status</th>
                                <th class="px-4 py-2 border">Tanggal</th>
                                <th class="px-4 py-2 border">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($messages as $message)
                                <tr class="border-b hover:bg-gray-100 {{ !$message->is_read ? 'bg-blue-50' : '' }}">
                                    <td class="px-4 py-2 border">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-2 border">{{ $message->name }}</td>
                                    <td class="px-4 py-2 border">{{ $message->email }}</td>
                                    <td class="px-4 py-2 border">{{ Str::limit($message->subject, 30) }}</td>
                                    <td class="px-4 py-2 border">
                                        <span class="px-2 py-1 rounded text-xs {{ $message->is_read ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $message->is_read ? 'Dibaca' : 'Belum dibaca' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 border">{{ $message->created_at->format('d M Y H:i') }}</td>
                                    <td class="px-4 py-2 border">
                                        <div class="flex gap-2">
                                            <a href="{{ route('admin.contact.show', $message) }}"
                                                class="px-3 py-1 bg-blue-500 text-white rounded text-sm hover:bg-blue-600">
                                                Detail
                                            </a>
                                            <form action="{{ route('admin.contact.destroy', $message) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus pesan ini?')" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="px-3 py-1 bg-red-500 text-white rounded text-sm hover:bg-red-600">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                        Belum ada pesan kontak
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $messages->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
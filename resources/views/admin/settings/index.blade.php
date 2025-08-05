<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengaturan Toko') }}
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

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-500 text-white rounded-lg">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.settings.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Informasi Toko -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Informasi Toko</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="shop_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Toko <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="shop_name" 
                                       id="shop_name" 
                                       value="{{ old('shop_name', isset($settings['general']) ? $settings['general']->where('key', 'shop_name')->first()->value ?? '' : '') }}"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500"
                                       required>
                            </div>

                            <div>
                                <label for="shop_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nomor Telepon <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="shop_phone" 
                                       id="shop_phone" 
                                       value="{{ old('shop_phone', isset($settings['general']) ? $settings['general']->where('key', 'shop_phone')->first()->value ?? '' : '') }}"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500"
                                       required>
                            </div>

                            <div>
                                <label for="shop_email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" 
                                       name="shop_email" 
                                       id="shop_email" 
                                       value="{{ old('shop_email', isset($settings['general']) ? $settings['general']->where('key', 'shop_email')->first()->value ?? '' : '') }}"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500"
                                       required>
                            </div>

                            <div>
                                <label for="shop_postal_code" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kode Pos <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="shop_postal_code" 
                                       id="shop_postal_code" 
                                       value="{{ old('shop_postal_code', isset($settings['shipping']) ? $settings['shipping']->where('key', 'shop_postal_code')->first()->value ?? '' : '') }}"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500"
                                       required>
                            </div>

                            <div class="md:col-span-2">
                                <label for="shop_address" class="block text-sm font-medium text-gray-700 mb-2">
                                    Alamat Lengkap <span class="text-red-500">*</span>
                                </label>
                                <textarea name="shop_address" 
                                          id="shop_address" 
                                          rows="3"
                                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500"
                                          required>{{ old('shop_address', isset($settings['general']) ? $settings['general']->where('key', 'shop_address')->first()->value ?? '' : '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Pengaturan Lokasi & Pengiriman -->
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Pengaturan Lokasi & Pengiriman</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="shop_province_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Provinsi Toko <span class="text-red-500">*</span>
                                </label>
                                <select name="shop_province_id" 
                                        id="shop_province_id" 
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500"
                                        required>
                                    <option value="">Pilih Provinsi</option>
                                </select>
                            </div>

                            <div>
                                <label for="shop_city_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kota/Kabupaten Toko <span class="text-red-500">*</span>
                                </label>
                                <select name="shop_city_id" 
                                        id="shop_city_id" 
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500"
                                        required>
                                    <option value="">Pilih Kota/Kabupaten</option>
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <h4 class="font-medium text-blue-800 mb-2">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        Konfigurasi RajaOngkir API Key
                                    </h4>
                                    <p class="text-sm text-blue-700 mb-2">
                                        API Key RajaOngkir dikonfigurasi melalui file <code class="bg-blue-100 px-1 rounded">.env</code> untuk keamanan.
                                    </p>
                                    <p class="text-sm text-blue-600">
                                        Tambahkan baris berikut di file <code class="bg-blue-100 px-1 rounded">.env</code>:<br>
                                        <code class="bg-blue-100 px-2 py-1 rounded text-xs">RAJAONGKIR_API_KEY=your_api_key_here</code>
                                    </p>
                                    <p class="text-xs text-blue-500 mt-2">
                                        Status API Key: 
                                        @if(config('services.rajaongkir.api_key'))
                                            <span class="text-green-600 font-semibold">✓ Terkonfigurasi</span>
                                        @else
                                            <span class="text-red-600 font-semibold">✗ Belum Dikonfigurasi</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           name="enable_shipping_cost" 
                                           id="enable_shipping_cost" 
                                           value="1"
                                           {{ old('enable_shipping_cost', isset($settings['shipping']) ? $settings['shipping']->where('key', 'enable_shipping_cost')->first()->value ?? false : false) ? 'checked' : '' }}
                                           class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                                    <label for="enable_shipping_cost" class="ml-2 block text-sm text-gray-700">
                                        Aktifkan perhitungan ongkos kirim
                                    </label>
                                </div>
                                <p class="text-sm text-gray-500 mt-1">
                                    Jika dinonaktifkan, ongkos kirim akan gratis untuk semua order
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                                class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 transition-colors">
                            Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const provinceSelect = document.getElementById('shop_province_id');
            const citySelect = document.getElementById('shop_city_id');
            
            // Store current values
            const currentProvinceId = '{{ old("shop_province_id", isset($settings["shipping"]) ? $settings["shipping"]->where("key", "shop_province_id")->first()->value ?? "" : "") }}';
            const currentCityId = '{{ old("shop_city_id", isset($settings["shipping"]) ? $settings["shipping"]->where("key", "shop_city_id")->first()->value ?? "" : "") }}';

            // Load provinces on page load (API key is configured in .env)
            loadProvinces();

            provinceSelect.addEventListener('change', function() {
                const provinceId = this.value;
                citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                
                if (provinceId) {
                    loadCities(provinceId);
                }
            });

            function loadProvinces() {
                fetch('{{ route("admin.settings.provinces") }}')
                    .then(response => response.json())
                    .then(data => {
                        console.log('Provinces response:', data); // Debug log
                        
                        if (data.data && Array.isArray(data.data)) {
                            provinceSelect.innerHTML = '<option value="">Pilih Provinsi</option>';
                            
                            data.data.forEach(province => {
                                const option = document.createElement('option');
                                option.value = province.id;
                                option.textContent = province.name;
                                
                                if (province.id == currentProvinceId) {
                                    option.selected = true;
                                }
                                
                                provinceSelect.appendChild(option);
                            });
                            
                            // Load cities if province is selected
                            if (currentProvinceId) {
                                loadCities(currentProvinceId);
                            }
                        } else {
                            console.error('Invalid provinces data structure:', data);
                            if (data.error) {
                                alert('Error: ' + data.error);
                            } else {
                                alert('Format data provinsi tidak valid.');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error loading provinces:', error);
                        alert('Gagal memuat daftar provinsi. Pastikan RAJAONGKIR_API_KEY sudah dikonfigurasi di .env file.');
                    });
            }

            function loadCities(provinceId) {
                fetch(`{{ url('/admin/settings/cities') }}/${provinceId}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Cities response:', data); // Debug log
                        
                        if (data.data && Array.isArray(data.data)) {
                            citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                            
                            data.data.forEach(city => {
                                const option = document.createElement('option');
                                option.value = city.id;
                                option.textContent = city.name;
                                
                                if (city.id == currentCityId) {
                                    option.selected = true;
                                }
                                
                                citySelect.appendChild(option);
                            });
                        } else {
                            console.error('Invalid cities data structure:', data);
                            alert('Format data kota tidak valid.');
                        }
                    })
                    .catch(error => {
                        console.error('Error loading cities:', error);
                        alert('Gagal memuat daftar kota. Error: ' + error.message);
                    });
            }
        });
    </script>
    @endpush
</x-app-layout>

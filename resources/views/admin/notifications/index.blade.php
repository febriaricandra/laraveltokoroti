<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <i class="fas fa-bell mr-2"></i>{{ __('Notifikasi') }}
            </h2>
            <button id="markAllRead" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                <i class="fas fa-check-double mr-2"></i>
                {{ __('Tandai Semua Sudah Dibaca') }}
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div id="notifications-container">
                @if($notifications->count() > 0)
                    <div class="space-y-4">
                        @foreach($notifications as $notification)
                            <div class="notification-item bg-white overflow-hidden shadow-sm sm:rounded-lg {{ $notification->read_at ? '' : 'border-l-4 border-yellow-400' }}" 
                                 data-id="{{ $notification->id }}">
                                <div class="p-6">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center mb-3">
                                                <div class="flex-shrink-0">
                                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-shopping-cart text-blue-600"></i>
                                                    </div>
                                                </div>
                                                <div class="ml-3">
                                                    <h3 class="text-lg font-medium text-gray-900">
                                                        {{ $notification->data['title'] ?? 'Notifikasi' }}
                                                    </h3>
                                                    @if(!$notification->read_at)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            Baru
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="ml-13">
                                                <p class="text-sm text-gray-700 mb-2">
                                                    {{ $notification->data['message'] }}
                                                </p>
                                                
                                                <div class="flex items-center text-sm text-gray-500 space-x-4">
                                                    <div class="flex items-center">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </div>
                                                    <div class="flex items-center">
                                                        <i class="fas fa-money-bill-wave mr-1"></i>
                                                        <span class="font-medium text-green-600">
                                                            Rp{{ number_format($notification->data['total_price'], 0, ',', '.') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center space-x-2 ml-4">
                                            @if(!$notification->read_at)
                                                <button class="mark-read-btn inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition ease-in-out duration-150" 
                                                        data-id="{{ $notification->id }}">
                                                    <i class="fas fa-check mr-1"></i>
                                                    Baca
                                                </button>
                                            @endif
                                            <a href="{{ route('admin.order.show', $notification->data['order_id']) }}" 
                                               class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150">
                                                <i class="fas fa-eye mr-1"></i>
                                                Lihat Order
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-8">
                        {{ $notifications->links() }}
                    </div>
                @else
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-center">
                            <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-bell-slash text-2xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">
                                {{ __('Tidak ada notifikasi') }}
                            </h3>
                            <p class="text-sm text-gray-500">
                                {{ __('Notifikasi akan muncul ketika ada order baru.') }}
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Mark individual notification as read
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('mark-read-btn') || e.target.closest('.mark-read-btn')) {
                const btn = e.target.closest('.mark-read-btn');
                const notificationId = btn.getAttribute('data-id');
                
                fetch(`/admin/notifications/${notificationId}/mark-as-read`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const notificationItem = btn.closest('.notification-item');
                        notificationItem.classList.remove('border-l-4', 'border-yellow-400');
                        btn.remove();
                        updateNotificationCount();
                        
                        // Remove "Baru" badge
                        const badge = notificationItem.querySelector('.bg-yellow-100');
                        if (badge) {
                            badge.remove();
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        });

        // Mark all notifications as read
        document.getElementById('markAllRead').addEventListener('click', function() {
            const button = this;
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
            
            fetch('/admin/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-check-double mr-2"></i>Tandai Semua Sudah Dibaca';
            });
        });

        // Update notification count
        function updateNotificationCount() {
            fetch('/admin/notifications/unread-count')
                .then(response => response.json())
                .then(data => {
                    const countElement = document.getElementById('notification-count');
                    if (countElement) {
                        countElement.textContent = data.count;
                        if (data.count === 0) {
                            countElement.style.display = 'none';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>
</x-app-layout>
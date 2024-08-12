<?php

use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public $notifications;

    public function mount(): void
    {
        $this->getNotifs();
    }

    #[On('chirp-created')]
    public function getNotifs(): void
    {
        $this->notifications = auth()->user()->fresh()->unreadNotifications;
    }

    public function markAsRead($notif_id): void
    {
        auth()->user()->unreadNotifications->where('id', $notif_id)->markAsRead();
        $this->dispatch('notif-marked-as-read');
        $this->getNotifs();
    }
}; ?>

<div class="divide-y">
    @forelse ($notifications as $notification)
        <div class="flex space-x-2" wire:key="{{ $notification->id }}">
            <div class="py-1 px-2 w-full cursor-pointer hover:bg-emerald-50" wire:click.prevent="markAsRead('{{ $notification->id }}')">
                <div class="flex justify-between italic">
                    <span>{{ $notification->data['user'] }}</span>
                    <span>{{ \Carbon\Carbon::createFromDate($notification->data['created_at'])->diffForHumans() }}</span>
                </div>
                <code class="text-gray-500">{{ $notification->data['message'] }}</code>
            </div>
        </div>
    @empty
        <div class="p-6 text-center">
            <span class="italic">No new notification...</span>
        </div>
    @endforelse
</div>

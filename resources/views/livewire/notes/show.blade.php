<?php
use Livewire\WithPagination;

use Livewire\Volt\Component;

use App\Models\Note;

new class extends Component {

    use WithPagination;

    public function delete($noteId) : void {
        $note = Note::where('id', $noteId)->first();
        $this->authorize('delete', $note);
        $note->delete();
    }
    
    public function with(): array
    {
        return [
            'notes' => Auth::user()
                ->notes()
                ->orderBy('send_date', 'asc')
                ->paginate(9),
                
        ];
    }
}; ?>

<div>
    <div class="space-y-2">
        @if ($notes->isEmpty())
        <div class="text-center">
            <p class="text-xl font-bold">No notes yet</p>
            <p class="text-sm">Let's create your first note to send.</p>
            <x-button primary icon-right="plus" class="mt-6" href="{{route('notes.create')}}" wire:navigate>Create note
            </x-button>
        </div>
        @else
        <x-button primary icon-right="plus" class="mt-6 mb-12" href="{{route('notes.create')}}" wire:navigate>Create
            note</x-button>
        <div class="grid grid-cols-3 gap-4 mt-12">
            @foreach ($notes as $note)
            <x-card wire:key='{{$note -> id}}' class="flex flex-col justify-around">
                <div class="flex justify-between gap-2">
                    <div>
                        @can('update', $note)
                            <a href="{{ route('notes.edit', $note) }}" wire:navigate
                                class="text-xl font-bold hover:underline hover:text-blue-500">
                                {{$note->title}}
                            </a>
                        @else
                            <p class="text-xl font-bold text-gray-500">{{ $note->title }}</p>
                        @endcan
                        <p class="mt-2 text-xs">{{Str::limit($note->body, 50)}}</p>
                    </div>
                    <div class="text-xs text-gray-500 text-pretty lg:text-nowrap ">
                        {{\Carbon\Carbon::parse($note->send_date)->format("d-M-Y")}}
                    </div>
                </div>
                <div class="flex items-end justify-between mt-4 space-y-1">
                    <p class="text-xs">Recipient: <span class="font-semibold"> {{$note->recipient}}
                        </span>
                    </p>
                    <div>
                        <x-button.circle icon="eye" href="{{route('notes.view', $note)}}"></x-button>
                        <x-button.circle icon="trash" wire:click="delete('{{$note->id}}')"></x-button>
                    </div>
                </div>
            </x-card>
            @endforeach

        </div>
        <div class="mt-10">
            {{ $notes->links(data: ['scrollTo' => false]) }}
        </div>
        @endif
    </div>
</div>
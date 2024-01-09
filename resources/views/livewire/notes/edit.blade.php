<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Note;

new #[Layout('layouts.app')] class extends Component {
    public Note $note;

    public $noteTitle;
    public $noteBody;
    public $noteRecipient;
    public $noteSendDate;
    public $noteIsPublished;

    public function mount(Note $note)
    {
        $this->authorize('update', $note);
        $this->fill($note);
        $this->noteTitle = $note->title;
        $this->noteBody = $note->body;
        $this->noteRecipient = $note->recipient;
        $this->noteSendDate = $note->send_date;
        $this->noteIsPublished = $note->is_published;
    }

    public function saveNote()
    {
        $validate = $this->validate([
            'noteTitle' => ['required', 'string', 'min:5'],
            'noteBody' => ['required', 'string', 'min:20'],
            'noteRecipient' => ['required', 'email'],
            'noteSendDate' => ['required', 'date'],
        ]);

        $this->note->update([
            'title' => $this->noteTitle,
            'body' => $this->noteBody,
            'recipient' => $this->noteRecipient,
            'send_date' => $this->noteSendDate,
            'is_published' => $this->noteIsPublished,
    ]);
    $this->dispatch("note-saved");
    }
}; ?>

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Edit Note') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 space-y-4 text-gray-900 dark:text-gray-100">
                <form wire:submit='saveNote' class="space-y-4">
                    <x-input wire:model='noteTitle' label="Title" aria-placeholder="What's the note about?" placeholder="What's the note about?" /> 
                    <x-textarea wire:model='noteBody' label="Your Note" aria-placeholder="What are you thinking?" placeholder="What are you thinking?"/> 
                    <x-input icon="user" wire:model='noteRecipient' label="Recipient" aria-placeholder="example@email.com" placeholder="example@email.com" type="email"/> 
                    <x-input icon="calendar" wire:model='noteSendDate' type="date" label="Send Date" /> 
                    <x-checkbox label="Note Published" wire:model='noteIsPublished' />
                    <div class="flex justify-between pt-4">
                        <x-button type="submit" secondary spinner="saveNote">Save Note</x-button>
                        <x-button href="{{route('notes.index')}}" flat negative>Back to notes</x-button> 
                    </div>
                    <x-action-message on="note-saved" />
                    <x-errors />
                </form>
            </div>
        </div>
    </div>


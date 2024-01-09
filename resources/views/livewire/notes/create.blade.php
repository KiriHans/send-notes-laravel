<?php

use Livewire\Volt\Component;

new class extends Component {
    public $noteTitle;
    public $noteBody;
    public $noteRecipient;
    public $noteSendDate;

    public function submit(){
        $validate = $this->validate([
            'noteTitle' => ['required', 'string', 'min:5'],
            'noteBody' => ['required', 'string', 'min:20'],
            'noteRecipient' => ['required', 'email'],
            'noteSendDate' => ['required', 'date'],
        ]);

        auth()->user()->notes()->create([
            'title' => $this->noteTitle,
            'body' => $this->noteBody,
            'recipient' => $this->noteRecipient,
            'send_date' => $this->noteSendDate,
            'is_published' => false,
        ]);

       

        redirect(route('notes.index'));
    }

}; ?>

<div>
    <form wire:submit='submit' class="space-y-4">
        <x-input wire:model='noteTitle' label="Title" aria-placeholder="What's the note about?" placeholder="What's the note about?" /> 
        <x-textarea wire:model='noteBody' label="Your Note" aria-placeholder="What are you thinking?" placeholder="What are you thinking?"/> 
        <x-input icon="user" wire:model='noteRecipient' label="Recipient" aria-placeholder="example@email.com" placeholder="example@email.com" type="email"/> 
        <x-input icon="calendar" wire:model='noteSendDate' type="date" label="Send Date" /> 
        <div class="pt-4">
            <x-button wire:click="submit" primary right-icon="calendar" spinner>Schedule Note</x-button>

        </div>
        <x-errors />
    </form>
</div>

<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        $events = Event::latest('tgl_mulai')->paginate(15);

        return view('admin.events.index', compact('events'));
    }

    public function create(): View
    {
        return view('admin.events.create', ['event' => new Event()]);
    }

    public function store(EventRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('gambar_event')) {
            $data['gambar_event'] = $request->file('gambar_event')->store('events', 'uploads');
        }

        Event::create($data);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil ditambahkan.');
    }

    public function edit(Event $event): View
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(EventRequest $request, Event $event): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('gambar_event')) {
            if ($event->gambar_event) {
                Storage::disk('uploads')->delete($event->gambar_event);
            }

            $data['gambar_event'] = $request->file('gambar_event')->store('events', 'uploads');
        }

        $event->update($data);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil diperbarui.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        if ($event->gambar_event) {
            Storage::disk('uploads')->delete($event->gambar_event);
        }

        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus.');
    }
}

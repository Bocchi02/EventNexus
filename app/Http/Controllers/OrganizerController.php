<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class OrganizerController extends Controller
{
    /**
     * Display organizer dashboard with their events
     */
    public function dashboard()
    {
        $organizerId = auth()->id();

        $totalEvents = Event::where('organizer_id', $organizerId)->count();
        $upcomingEvents = Event::where('organizer_id', $organizerId)
                                ->where('status', 'upcoming')
                                ->count();
        $completedEvents = Event::where('organizer_id', $organizerId)
                                ->where('status', 'completed')
                                ->count();

        return view('organizer.dashboard', compact('totalEvents', 'upcomingEvents', 'completedEvents'));
    }


    /**
     * Show all events for the organizer
     */
    public function events()
    {
        $events = Event::where('organizer_id', auth()->id())
                       ->orderBy('created_at', 'desc')
                       ->get();

        return view('organizer.events', compact('events'));
    }

    /**
     * Store a new event
     */
    public function storeEvent(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'venue' => 'required|string|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'organizer_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'venue' => $validated['venue'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => 'upcoming',
        ];

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $coverImage = $request->file('cover_image');
            $coverImageName = time() . '_cover.' . $coverImage->getClientOriginalExtension();
            $coverImage->move(public_path('uploads/events'), $coverImageName);
            $data['cover_image'] = 'uploads/events/' . $coverImageName;
        }

        // Handle gallery images upload
        if ($request->hasFile('gallery_images')) {
            $galleryImages = [];
            foreach ($request->file('gallery_images') as $index => $image) {
                $imageName = time() . '_gallery_' . $index . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/events'), $imageName);
                $galleryImages[] = 'uploads/events/' . $imageName;
            }
            $data['gallery_images'] = $galleryImages;
        }

        Event::create($data);

        return redirect()->back()->with('success', 'Event created successfully.');
    }

    /**
     * Update an existing event
     */
    public function updateEvent(Request $request, $id)
    {
        $event = Event::where('id', $id)
                     ->where('organizer_id', auth()->id())
                     ->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'venue' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'nullable|in:upcoming,ongoing,completed,cancelled',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'venue' => $validated['venue'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ];

        if (isset($validated['status'])) {
            $data['status'] = $validated['status'];
        }

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            // Delete old cover image if exists
            if ($event->cover_image && file_exists(public_path($event->cover_image))) {
                unlink(public_path($event->cover_image));
            }

            $coverImage = $request->file('cover_image');
            $coverImageName = time() . '_cover.' . $coverImage->getClientOriginalExtension();
            $coverImage->move(public_path('uploads/events'), $coverImageName);
            $data['cover_image'] = 'uploads/events/' . $coverImageName;
        }

        // Handle gallery images upload
        if ($request->hasFile('gallery_images')) {
            $galleryImages = $event->gallery_images ?? [];
            
            foreach ($request->file('gallery_images') as $index => $image) {
                $imageName = time() . '_gallery_' . $index . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/events'), $imageName);
                $galleryImages[] = 'uploads/events/' . $imageName;
            }
            $data['gallery_images'] = $galleryImages;
        }

        $event->update($data);

        return redirect()->back()->with('success', 'Event updated successfully.');
    }

    /**
     *  View Event Description Huehuehuehue
     */
    public function show($id)
    {
        $event = Event::where('organizer_id', auth()->id())->findOrFail($id);
        return view('organizer.show', compact('event'));
    }

    /** 
     * Edit Event 
     */
    public function editEvent($id)
    {
        $event = Event::where('organizer_id', auth()->id())->findOrFail($id);
        return view('organizer.edit', compact('event'));
    }

    /**
     * Delete an event
     */
    public function deleteEvent($id)
    {
        $event = Event::where('id', $id)
                     ->where('organizer_id', auth()->id())
                     ->firstOrFail();

        // Delete cover image if exists
        if ($event->cover_image && file_exists(public_path($event->cover_image))) {
            unlink(public_path($event->cover_image));
        }

        // Delete gallery images if exist
        if ($event->gallery_images) {
            foreach ($event->gallery_images as $image) {
                if (file_exists(public_path($image))) {
                    unlink(public_path($image));
                }
            }
        }

        $event->delete();

        return redirect()->back()->with('success', 'Event deleted successfully.');
    }

    /**
     * Toggle event status
     */
    public function toggleStatus($id)
    {
        $event = Event::where('id', $id)
                     ->where('organizer_id', auth()->id())
                     ->firstOrFail();

        // Cycle through statuses: upcoming -> ongoing -> completed
        $statusCycle = [
            'upcoming' => 'ongoing',
            'ongoing' => 'completed',
            'completed' => 'upcoming',
            'cancelled' => 'upcoming',
        ];

        $event->status = $statusCycle[$event->status] ?? 'upcoming';
        $event->save();

        return redirect()->back()->with('success', 'Event status updated.');
    }
}
<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEvent;
use App\Http\Requests\UpdateEvent;
use App\Http\Resources\EventResource;
use App\Models\Event;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('index','show');
    }

    public function index()
    {
        return EventResource::collection(
            Event::with('user','attendees')->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEvent $request)
    {
        $event = Event::create([
            ...$request->validated(),
            'user_id'=>$request->user()->id
        ]);

        return new EventResource($event);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $event->load('user','attendees');
        return new EventResource($event);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEvent $request, Event $event)
    {
        $event->update($request->validated());

        return new EventResource($event);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return response()->json([
            'message'=>'Event Deleted Successfully'
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Traits\CanLoadRelationships;
use Illuminate\Http\Request;
use App\Http\Requests\EventRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Event;

class EventController extends Controller
{
    use CanLoadRelationships;

    private array $relations = ['user', 'attendees', 'attendees.user'];

    public function __construct() {
        $this->middleware('throttle:api')
            ->only(['store', 'update', 'destroy']);
            
        $this->authorizeResource(Event::class, 'event', [
            'except' => ['index', 'show']
        ]);
    }

    public function index() {
        $query = $this->loadRelationships(Event::query());

        return EventResource::collection(
            $query->latest()->paginate()
        );
    }

    public function show(Event $event) {
        $event->load('user', 'attendees');

        return new EventResource($this->loadRelationships($event));
    }

    public function store(EventRequest $request){
        DB::beginTransaction();

        try {
            
            $data = $request->validated();
            $data['user_id'] = $request->user()->id;
            $event = Event::create($data);

            DB::commit();

             // Return the newly created event as a JSON response
            return response()->json([
                'success' => true,
                'message' => 'Event created successfully',
                'data' => new EventResource($this->loadRelationships($event))
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            // Return a JSON error response
            return response()->json([
                'success' => false,
                'message' => 'Failed to create the event',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(EventRequest $request, Event $event) {
        DB::beginTransaction();

        try {
            $event->update($request->validated());

            DB::commit();

             // Return the newly updated event as a JSON response
            return response()->json([
                'success' => true,
                'message' => 'Event updated successfully',
                'data' => new EventResource($this->loadRelationships($event))
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Return a JSON error response
            return response()->json([
                'success' => false,
                'message' => 'Failed to update the event',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function delete(Event $event) {
        $event->delete();

        return response()->json([
            'success' => true,
            'message' => 'Event deleted successfully',
        ], 204);
    }
}

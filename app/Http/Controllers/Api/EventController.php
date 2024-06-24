<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use Illuminate\Http\Request;
use App\Http\Requests\EventRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Event;

class EventController extends Controller
{
    public function index() {

        $query = Event::query();

        $relations = ['user', 'attendees', 'attendees.user'];

        foreach ($relations as $relation) {
            $query->when(
                $this->shouldIncludeRelation($relation),
                fn($q) => $q->with($relation)
            );
        }

        return EventResource::collection(
            $query->latest()->paginate()
        );
    }

    protected function shouldIncludeRelation(string $relation): bool {
        $include = request()->query('include');

        if (!$include) {
            return false;
        }

        $relations = array_map('trim', explode(',', $include));

        return in_array($relation, $relations);
    }

    public function show(Event $event) {
        $event->load('user', 'attendees');

        return new EventResource($event);
    }

    public function store(EventRequest $request){
        DB::beginTransaction();

        try {
            
            $data = $request->validated();
            $data['user_id'] = 1;
            $event = Event::create($data);

            DB::commit();

             // Return the newly created event as a JSON response
            return response()->json([
                'success' => true,
                'message' => 'Event created successfully',
                'data' => $event
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
                'data' => new EventResource($event)
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
            'message' => 'Event updated successfully',
        ], 204);
    }
}

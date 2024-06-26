<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{
    use CanLoadRelationships;

    private array $relations = ['user'];

    public function __construct() {
        $this->middleware('throttle:api')
            ->only(['store', 'destroy']);
            
        $this->authorizeResource(Attendee::class, 'attendee', [
            'except' => ['index', 'show', 'update']
        ]);
    }

    public function index(Event $event) {
        $attendees = $this->loadRelationships(
            $event->attendees()->latest()
        );

        return AttendeeResource::collection(
            $attendees->paginate()
        );
    }

    public function store(Request $request, Event $event){
        $attendee = $this->loadRelationships(
            $event->attendees()->create([
                'user_id' => 3
            ])
        );

        return new AttendeeResource($attendee);
    }

    public function show(Event $event, Attendee $attendee) {
        return new AttendeeResource(
            $this->loadRelationships($attendee)
        );
    }

    public function delete(Event $event, Attendee $attendee) {
        $attendee->delete();

        return response()->json([
            'success' => true,
            'message' => 'Attendee delete successfully',
        ], 204);
    }

}

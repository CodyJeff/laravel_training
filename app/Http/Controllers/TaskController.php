<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Requests\Task\TaskRequest;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    /**
     * Display a listing of the tasks.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        // Retrieve the latest tasks  from the database, ordering by the most recent 
        // and paginate the results to show 10 task per page
        $tasks = Task::latest()->paginate(10);

        // Return `task.index` view with retrieved tasks data
        return view('task.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new task.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //Return `task.create` view which contains the form for creating new task
        return view('task.create');
    }

    /**
     * Display the specified task.
     *
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task) {
        // Check if the task exists. The `Task` model binding will automatically fetch the task
        // or return a 404 response if the task is not found, so this check is not necessarry.
        // However, if you want to manually handle the not found case:
        if(!$task) {
            // Abort with a 404 Not Found response if the does not exists
            abort(Response::HTTP_NOT_FOUND);
        }

        // Return the `task.show` view with the retrieved task data
        return view('task.show', compact('task'));
    }

    /**
     * Store a newly created task in the database.
     *
     * @param \App\Http\Requests\TaskRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskRequest $request) {
        // Begin a database transaction
        DB::beginTransaction();

        try {
            // Attempt the create a new task with the validated data from the request
            $task = Task::create($request->validated());

            // Commit the transaction if the task is created successfully
            DB::commit();
        } catch (\Exception $e) {
            // Rollback the transaction if an error occurs
            DB::rollBack();

            // Redirect back to the task creation page with an error message
            return redirect()->route('tasks.create')
                ->with('error', 'Error in saving in Database');
        }

        // Redirect back to the task show page with an success message
        return redirect()->route('tasks.show', ['task' => $task->id])
            ->with('success', 'Task Created Successfully');
    }

    /**
     * Show the form for editing the specified task.
     *
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task) {
        // Return the `task.edit` view which contains the form for editing the specified task
        // The task data is passed to the view using the compact function
        return view('task.edit', compact('task'));
    }

    /**
     * Update the specified task in the database.
     *
     * @param \App\Models\Task $task
     * @param \App\Http\Requests\TaskRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(Task $task, TaskRequest $request) {
        // Begin a database transaction
        DB::beginTransaction();

        try {
            // Attempt to update the task with the validated data from the request
            $task->update($request->validated());

            // Commit the transaction if the task is updated successfully
            DB::commit();
        } catch (\Exception $e) {
            // Rollback the transaction if an error occurs
            DB::rollBack();

            // Redirect back to the task show page with an error message
            return redirect()->route('tasks.show', ['task' => $task->id])
                ->with('error', 'Error in Updating in Database');
        }

        // Redirect back to the task show page with an success message
        return redirect()->route('tasks.show', ['task' => $task->id])
            ->with('success', 'Task Updated Successfully');
    }

    /**
     * Remove the specified task from the database.
     *
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\Response
     */
    public function delete(Task $task) {
        // Attempt to delete specified task from the database
        $task->delete();

        // Redirect back to the task list page with an success message
        return redirect()->route('tasks.list')
            ->with('success', 'Task deleted successfully!');
    }

    /**
     * Toggle the completion status of the specified task.
     *
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\Response
     */
    public function toggleComplete(Task $task) {
        // Toggle the completion status of the specified task
        $task->toggleComplete();

        // Redirect back to the previous page with a success message
        return redirect()->back()
            ->with('success', 'Task updated successfully!');
    }
}


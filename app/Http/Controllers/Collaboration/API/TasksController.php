<?php

namespace App\Http\Controllers\Collaboration\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Collaboration\StoreTask;
use App\Http\Resources\Collaboration\TaskResource;
use App\Models\Collaboration\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TasksController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Task::class);
    }

    protected function resourceAbilityMap()
    {
        return array_merge(parent::resourceAbilityMap(), ['done' => 'update']);
    }

    public function index()
    {
        $tasks = Task::open()
            ->withOwner(Auth::id())
            ->get()
            ->filter(fn ($value, $key) => $this->authorize('view', $value));

        return TaskResource::collection($tasks);
    }

    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    public function store(StoreTask $request)
    {
        $task = new Task();
        $task->description = $request->description;
        $task->user()->associate(Auth::user());
        $task->save();

        return response()->json(new TaskResource($task), 201);
    }

    public function update(Task $task, StoreTask $request)
    {
        $task->description = $request->description;
        $task->save();

        return response(null, 204);
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return response(null, 204);
    }

    public function done(Task $task)
    {
        $task->done_date = Carbon::now();
        $task->save();

        return response(null, 204);
    }
}

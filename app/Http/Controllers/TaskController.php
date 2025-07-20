<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $tasks = Task::all();
        return response()->json($tasks);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'nullable|in:true,false,1,0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ], 400);
        }

        try {
            $task = Task::create([
                'title' => $request->title,
                'description' => $request->description,
                'completed' => filter_var($request->completed, FILTER_VALIDATE_BOOLEAN),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear la tarea',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }

        return response()->json([
            'task' => $task,
            'status' => 201
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        try {
            $task = Task::find($id);
            if (!$task) {
                $data = [
                    'message' => "Tarea no encontrada",
                    'status' => 404
                ];

                return response()->json($data, 404);
            }

            $data = [
                'task' => $task,
                'status' => 200
            ];

            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        try {
            $task = Task::find($id);

            if (!$task) {
                $data = [
                    'message' => 'Tarea no encontrada',
                    'status' => 404
                ];

                return response()->json($data, 404);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required'
            ]);

            if ($validator->fails()) {
                $data = [
                    'message' => 'Error en la validación',
                    'error' => $validator->errors(),
                    'status' => 400
                ];

                return response()->json($data, 400);
            }

            $task->title = $request->title;
            $task->description = $request->description;

            $task->save();

            $data = [
                'message' => 'Tarea editada correctamente',
                'task' => $task,
                'status' => 200
            ];

            return response()->json($data, 200);
        } catch (\Exception $e) {
            $errorData = [
                'error' => $e->getMessage(),
                'status' => 500
            ];

            return response()->json($errorData, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }

    public function showCompletedTasks()
    {
        $condition = true;

        try {
            $completedTasks = Task::where('completed', '=', $condition)->get();

            if ($completedTasks->isEmpty()) {
                $data = [
                    'message' => 'Tareas no encontradas',
                    'status' => 404
                ];

                return response()->json($data, 404);
            }

            $data = [
                'completedTasks' => $completedTasks,
                'status' => 200
            ];

            return response()->json($data, 200);
        } catch (\Exception $e) {
            $errorData = [
                'error' => $e->getMessage(),
                'status' => 500
            ];

            return response()->json($errorData, 500);
        }
    }

    public function showPendingTasks()
    {
        $condition = false;

        try {
            $completedTasks = Task::where('completed', '=', $condition)->get();

            if ($completedTasks->isEmpty()) {
                $data = [
                    'message' => 'Tareas no encontradas',
                    'status' => 404
                ];

                return response()->json($data, 404);
            }

            $data = [
                'completedTasks' => $completedTasks,
                'status' => 200
            ];

            return response()->json($data, 200);
        } catch (\Exception $e) {
            $errorData = [
                'error' => $e->getMessage(),
                'status' => 500
            ];

            return response()->json($errorData, 500);
        }
    }

    public function markAsCompleted($id)
    {
        try {
            $task = Task::find($id);
            if (!$task) {
                $data = [
                    'message' => 'Tarea no encontrada',
                    'status' => 404
                ];

                return response()->json($data, 404);
            }

            $task->completed = true;
            $task->save();

            $data = [
                'message' => 'Tarea marcada como completa',
                'task' => $task,
                'status' => 200
            ];

            return response()->json($data, 200);
        } catch (\Exception $e) {
            $errorData = [
                'error' => $e->getMessage(),
                'status' => 500
            ];

            return response()->json($errorData, 500);
        }
    }
}

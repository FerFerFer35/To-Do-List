<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/tasks",
     *     operationId="getTasksList",
     *     tags={"Tasks"},
     *     summary="Obtener todas las tareas",
     *     description="Retorna todas las tareas registradas",
     *     @OA\Response(
     *         response=200,
     *         description="Tareas encontradas correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontraron tareas"
     *     )
     * )
     */
    public function index()
    {
        // Use all() to retrieve all tasks
        // This will return all tasks in the database
        $tasks = Task::all();
        // Check if the tasks collection is empty
        // If it is empty, return a 404 response with a message
        if ($tasks->isEmpty()) {
            return response()->json([
                'message' => 'No tasks found',
                'status' => 404
            ], 404);
        }

        // If tasks are found, return them with a 200 status
        return response()->json($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *     path="/api/createTask",
     *     operationId="createTask",
     *     tags={"Tasks"},
     *     summary="Crear nueva tarea",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property(property="title", type="string", example="Hacer ejercicio"),
     *             @OA\Property(property="description", type="string", example="30 minutos de cardio"),
     *             @OA\Property(property="completed", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tarea creada correctamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error en la validación"
     *     )
     * )
     */
    public function store(Request $request)
    {
        // Use Validator to "validate" the incoming request data
        // Ensure that the title is required, description is optional, and completed is a boolean
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'nullable|in:true,false,1,0',
        ]);

        // If validation fails, return a 400 response with the validation errors
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error in the validation',
                'errors' => $validator->errors(),
                'status' => 400
            ], 400);
        }

        // If validation passes, create a new task using the validated data
        // Use filter_var to ensure the completed field is a boolean
        try {
            // Create a new task with the validated data
            $task = Task::create([
                'title' => $request->title,
                'description' => $request->description,
                'completed' => filter_var($request->completed, FILTER_VALIDATE_BOOLEAN),
            ]);
        } catch (\Exception $e) {
            // If an exception occurs during task creation, return a 500 response with the error message
            return response()->json([
                'message' => 'Error to create task',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }

        // If the task is created successfully, return a 201 response with the task data
        // The 201 status code indicates that a resource has been successfully created
        return response()->json([
            'task' => $task,
            'status' => 201
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/api/tasks/{id}",
     *     operationId="getTaskById",
     *     tags={"Tasks"},
     *     summary="Obtener tarea por ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la tarea",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tarea encontrada"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarea no encontrada"
     *     )
     * )
     */
    public function show($id)
    {
        // Use find() to retrieve a specific task by ID
        try {
            // Find the task by ID
            $task = Task::find($id);
            // If the task is not found, return a 404 response with a message
            // The 404 status code indicates that the requested resource was not found
            if (!$task) {
                $data = [
                    'message' => "Task not found",
                    'status' => 404
                ];

                // Return a JSON response with the message and status code
                // This will indicate that the task with the specified ID does not exist
                return response()->json($data, 404);
            }

            // If the task is found, return it with a 200 status
            // The 200 status code indicates a successful retrieval of the task
            $data = [
                'task' => $task,
                'status' => 200
            ];
            // Return a JSON response with the task and status code
            return response()->json($data, 200);
        } catch (\Exception $e) {
            // If an exception occurs during the retrieval process, return a 500 response with the error message
            // The 500 status code indicates an internal server error
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */

    /**
     * @OA\Patch(
     *     path="/api/updateTask/{id}",
     *     operationId="updateTask",
     *     tags={"Tasks"},
     *     summary="Actualizar tarea",
     *     description="Actualiza los detalles de una tarea existente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la tarea",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","description"},
     *             @OA\Property(property="title", type="string", example="Hacer ejercicio"),
     *             @OA\Property(property="description", type="string", example="30 minutos de cardio"),
     *             @OA\Property(property="completed", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tarea actualizada correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarea no encontrada"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        // 
        try {
            // We need to find the task by ID
            $task = Task::find($id);

            // If the task is not found, return a 404 response with a message
            if (!$task) {
                $data = [
                    'message' => 'Task not found',
                    'status' => 404
                ];

                // Return a JSON response with the message and status code
                return response()->json($data, 404);
            }

            // Validate the request data
            // Ensure that the title and description are required
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required'
            ]);

            // If validation fails, return a 400 response with the validation errors
            // The 400 status code indicates a bad request due to validation errors
            if ($validator->fails()) {
                $data = [
                    'message' => 'Error in the validation',
                    'error' => $validator->errors(),
                    'status' => 400
                ];

                // Return a JSON response with the validation errors and status code
                return response()->json($data, 400);
            }

            // If validation passes, update the task with the new title and description
            // Use the request data to update the task
            $task->title = $request->title;
            $task->description = $request->description;

            // Save the updated task to the database
            $task->save();

            // If the task is updated successfully, return a 200 response with the updated task data
            // The 200 status code indicates a successful update
            $data = [
                'message' => 'Task updated successfully',
                'task' => $task,
                'status' => 200
            ];

            // Return a JSON response with the updated task and status code
            return response()->json($data, 200);
        } catch (\Exception $e) {
            // If an exception occurs during the update process, return a 500 response with the error message
            // The 500 status code indicates an internal server error
            $errorData = [
                'error' => $e->getMessage(),
                'status' => 500
            ];

            // Return a JSON response with the error message and status code
            return response()->json($errorData, 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/showCompletedTasks",
     *     operationId="getTasksListCompleted",
     *     tags={"Tasks"},
     *     summary="Obtener todas las tareas completadas",
     *     description="Retorna todas las tareas completadas",
     *     @OA\Response(
     *         response=200,
     *         description="Tareas encontradas correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontraron tareas"
     *     )
     * )
     */
    public function showCompletedTasks()
    {
        // Define the condition for completed tasks
        // In this case, we are looking for tasks where the 'completed' field is true
        $condition = true;

        try {
            // Retrieve all tasks where the 'completed' field matches the condition
            // Use the Task model to query the database for completed tasks
            $completedTasks = Task::where('completed', '=', $condition)->get();

            // Check if the completed tasks collection is empty
            // If it is empty, return a 404 response with a message
            if ($completedTasks->isEmpty()) {
                $data = [
                    'message' => 'Tasks not found',
                    'status' => 404
                ];

                // Return a JSON response with the message and status code
                return response()->json($data, 404);
            }

            // If completed tasks are found, return them with a 200 status
            // The 200 status code indicates a successful retrieval of completed tasks
            $data = [
                'completedTasks' => $completedTasks,
                'status' => 200
            ];

            // Return a JSON response with the completed tasks and status code
            return response()->json($data, 200);
        } catch (\Exception $e) {
            // If an exception occurs during the retrieval process, return a 500 response with the error message
            // The 500 status code indicates an internal server error
            $errorData = [
                'error' => $e->getMessage(),
                'status' => 500
            ];

            // Return a JSON response with the error message and status code
            return response()->json($errorData, 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/showPendingTasks",
     *     operationId="getTasksListPending",
     *     tags={"Tasks"},
     *     summary="Obtener todas las tareas pendientes",
     *     description="Retorna todas las tareas pendientes",
     *     @OA\Response(
     *         response=200,
     *         description="Tareas encontradas correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontraron tareas"
     *     )
     * )
     */
    public function showPendingTasks()
    {
        // Define the condition for pending tasks
        // In this case, we are looking for tasks where the 'completed' field is false
        // This means we want to retrieve tasks that are not yet completed
        $condition = false;

        try {
            // Retrieve all tasks where the 'completed' field matches the condition
            // Use the Task model to query the database for pending tasks
            $completedTasks = Task::where('completed', '=', $condition)->get();

            // Check if the completed tasks collection is empty
            // If it is empty, return a 404 response with a message
            if ($completedTasks->isEmpty()) {
                $data = [
                    'message' => 'Tasks not found',
                    'status' => 404
                ];
                // Return a JSON response with the message and status code
                return response()->json($data, 404);
            }

            // If pending tasks are found, return them with a 200 status
            // The 200 status code indicates a successful retrieval of pending tasks
            $data = [
                'completedTasks' => $completedTasks,
                'status' => 200
            ];

            // Return a JSON response with the completed tasks and status code
            return response()->json($data, 200);
        } catch (\Exception $e) {
            // If an exception occurs during the retrieval process, return a 500 response with the error message
            // The 500 status code indicates an internal server error
            $errorData = [
                'error' => $e->getMessage(),
                'status' => 500
            ];
            // Return a JSON response with the error message and status code
            return response()->json($errorData, 500);
        }
    }

    /**
     * @OA\Patch(
     *     path="/api/markAsCompleted/{id}",
     *     operationId="markTaskAsCompleted",
     *     tags={"Tasks"},
     *     summary="Marcar tarea como completada",
     *     description="Actualiza el estado de una tarea a completada",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la tarea",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tarea marcada como completada"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarea no encontrada"
     *     )
     * )
     */
    public function markAsCompleted($id)
    {
        try {
            // Find the task by ID
            // Use the Task model to retrieve the task from the database
            $task = Task::find($id);

            // If the task is not found, return a 404 response with a message
            // The 404 status code indicates that the requested resource was not found
            if (!$task) {
                $data = [
                    'message' => 'Task not found',
                    'status' => 404
                ];
                // Return a JSON response with the message and status code
                return response()->json($data, 404);
            }

            // If the task is found, update its 'completed' status to true
            // This indicates that the task has been marked as completed
            // Set the 'completed' field to true
            $task->completed = true;
            // Save the updated task to the database
            // This will persist the changes made to the task
            $task->save();

            // If the task is successfully marked as completed, return a 200 response with the task data
            // The 200 status code indicates a successful operation
            $data = [
                'message' => 'Task marked as completed',
                'task' => $task,
                'status' => 200
            ];
            // Return a JSON response with the updated task and status code
            return response()->json($data, 200);
        } catch (\Exception $e) {
            // If an exception occurs during the marking process, return a 500 response with the error message
            // The 500 status code indicates an internal server error
            $errorData = [
                'error' => $e->getMessage(),
                'status' => 500
            ];
            // Return a JSON response with the error message and status code
            return response()->json($errorData, 500);
        }
    }

    /**
     * @OA\Patch(
     *     path="/api/markAsPending/{id}",
     *     operationId="markTaskAsPending",
     *     tags={"Tasks"},
     *     summary="Marcar tarea como pendiente",
     *     description="Actualiza el estado de una tarea a pendiente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la tarea",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tarea marcada como pendiente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarea no encontrada"
     *     )
     * )
     */
    public function markAsPending($id)
    {
        try {
            // Find the task by ID
            $task = Task::find($id);

            // If the task is not found, return a 404 response with a message
            if (!$task) {
                $data = [
                    'message' => 'Task not found',
                    'status' => 404
                ];
                return response()->json($data, 404);
            }

            // If the task is found, update its 'completed' status to false
            $task->completed = false;
            // Save the updated task to the database
            $task->save();

            // If the task is successfully marked as pending, return a 200 response with the task data
            $data = [
                'message' => 'Task marked as pending',
                'task' => $task,
                'status' => 200
            ];
            return response()->json($data, 200);
        } catch (\Exception $e) {
            // If an exception occurs during the marking process, return a 500 response with the error message
            $errorData = [
                'error' => $e->getMessage(),
                'status' => 500
            ];
            return response()->json($errorData, 500);
        }
    }
}

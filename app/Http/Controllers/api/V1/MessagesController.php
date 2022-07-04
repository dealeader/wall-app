<?php

namespace App\Http\Controllers\api\V1;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Message\StoreRequest;
use App\Http\Resources\Message\MessageResource;
use Illuminate\Support\Carbon;

class MessagesController extends Controller
{
    public function index()
    {
        $messages = MessageResource::collection(Message::orderBy('created_at', 'desc')->paginate(20));

        return response()->json([
            'data' => $messages,
            'page' => $messages->currentPage(),
            'last_page' => $messages->lastPage(),
        ], 200);
    }

    public function show(Message $message)
    {
        return response()->json([
            'data' => new MessageResource($message),
        ], 200);
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        $message = Message::create($data);

        return response()->json([
            'data' => new MessageResource($message),
            'message' => 'The message was successfully created',
        ], 201);
    }

    public function destroy(Message $message)
    {
        $date = $message->publication_date;

        if ($message->user_id !== Auth::id() || Carbon::now()->diffInHours($date) > 24) {
            return response()->json([
                'message' => 'Not enough rights',
            ], 403);
        };

        $message->delete();
        return response()->json([
            'data' => new MessageResource($message),
            'message' => 'The message was successfully deleted',
        ], 202);
    }
}

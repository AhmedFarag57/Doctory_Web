<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetMessageRequest;
use App\Http\Requests\StoreMessageRequest;
use App\Models\ChatMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatMessageController extends Controller
{
    
    /**
     * 
     * Get chat messages
     * 
     * @param GetMessageRequest $request
     * @return JsonRepsonse
     */
    public function index(GetMessageRequest $request) : JsonResponse {

        $data = $request->validated();
        $chatId = $data['chat_id'];
        $currentPage = $data['page'];
        $pageSize = $data['page_size'] ?? 15;

        $messages = ChatMessage::where('chat_id', $chatId)
                                ->with('user')
                                ->latest('created_at')
                                ->simplePaginate(
                                    $pageSize,
                                    ['*'],
                                    'page',
                                    $currentPage
                                );

        return $this->success($messages->getCollection());
    }

    /**
     * 
     * Create a chat messages
     * 
     * @param StoreMessageRequest $request
     * @return JsonRepsonse
     */
    public function store(StoreMessageRequest $request) : JsonResponse {

        $data = $request->validated();
        $data['user_id'] = auth()->user()->id;

        $chatMessage = ChatMessage::create($data);

        $chatMessage->load('user');

        // TODO send broadcast event to pusher
        // and send notification to onesignal services

        return $this->success($chatMessage, 'Message has been sent successfully');
    }
}

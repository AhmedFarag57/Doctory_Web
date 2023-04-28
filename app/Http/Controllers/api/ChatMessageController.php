<?php

namespace App\Http\Controllers\api;

use App\Events\NewMessageSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetMessageRequest;
use App\Http\Requests\StoreMessageRequest;
use App\Models\Appointment;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
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

        // and send notification to onesignal services
        $this->sendNotificationToOther($chatMessage);

        return $this->success($chatMessage, 'Message has been sent successfully');
    }


    /**
     * Send notification
     *
     * @param ChatMessage $chatMessage
     * @return void
     */
    private function sendNotificationToOther(ChatMessage $chatMessage) : void {

        broadcast(new NewMessageSent($chatMessage))->toOthers();
        
        $user = auth()->user();
        $userId = $user->id;

        $chat = Chat::where('id', $chatMessage->chat_id)
        ->with(['participants' => function($query) use ($userId) {
            $query->where('user_id', '!=', $userId);
        }])
        ->first();


        if(count($chat->participants) > 0) {
            $otherUserId = $chat->participants[0]->user_id;
            $otherUser = User::where('id', $otherUserId)->first();
            $otherUser->sendNewMessageNotification([
                'messageData' => [
                    'senderName' => $user->name,
                    'message' => $chatMessage->message,
                    'chatId' => $chatMessage->chat_id
                ]
            ]);
        }
        
    }

    public function getMessages(Request $request)
    {
        $data = $request->validate([
            'page' => 'required|numeric',
            'page_size' => 'nullable|numeric',
            'appointment_id' => 'required|numeric',
        ]);

        $chatId = Chat::where('appointment_id', $data['appointment_id'])
            ->select('id')
            ->first();

        $currentPage = $data['page'];
        $pageSize = $data['page_size'] ?? 15;

        $messages = ChatMessage::where('chat_id', $chatId['id'])
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
}

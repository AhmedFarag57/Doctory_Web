<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetChatRequest;
use App\Http\Requests\StoreChatRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Chat;
use Illuminate\Http\JsonResponse;

class ChatController extends Controller
{
    /**
     * Get chats
     *
     * @param GetChatRequest $request
     * @return JsonResponse
     */
    public function index(GetChatRequest $request) : JsonResponse
    {
        $data = $request->validated();

        $isPrivate = 1;
        if($request->has('is_private')){
            $isPrivate = (int)$data['is_private'];
        }

        $chats = Chat::where('is_private', $isPrivate)
                    ->hasParticipant(auth()->user()->id)
                    ->whereHas('messages')
                    ->with('lastMessage.user', 'participants.user')
                    ->latest('updated_at')
                    ->get();

        return $this->success($chats);
    }

    /**
     * Store a new chat
     *
     * @param  Request  $request
     */
    public function store(Request $request)
    {
        $data = $this->prepareStoreData($request);

        if($data['userId'] === $data['otherUserId']) {
            return $this->error('You can not create a chat with your self');
        }

        //$previousChat = $this->getPreviousChat($data['otherUserId']);

        //if($previousChat === null){

            $chat = Chat::create($data['data']);

            $chat->participants()->createMany([
                [
                    'user_id' => $data['userId']
                ],
                [
                    'user_id' => $data['otherUserId']
                ]
            ]);

            $chat->refresh()->load('lastMessage.user', 'participants.user');

            return $chat;
        //}

        //return $previousChat->load('lastMessage.user', 'participants.user');
    }

    /**
     * Check if user and other user has previuos chat or not
     *
     * @param  int  $otherUserId
     * @return mixed
     */
    private function getPreviousChat(int $otherUserId) : mixed
    {
        $userId = auth()->user()->id;

        return Chat::where('is_private', 1)
                    ->whereHas('participants', function($query) use ($userId){
                        $query->where('user_id', $userId);
                    })
                    ->whereHas('participants', function($query) use ($otherUserId){
                        $query->where('user_id', $otherUserId);
                    })
                    ->first();
    }

    /**
     * Prepares data for store a chat
     *
     * @param  StoreChatRequest  $request
     * @return array
     */
    private function prepareStoreData(Request $request) : array
    {
        $data = $request->validate([
            'user_id' => "required",
            'name' => 'nullable',
            'is_private' => 'nullable|boolean',
            'appointment_id' => 'required',
        ]);

        $otherUserId = (int)$data['user_id'];

        unset($data['user_id']);
        $data['created_by'] = auth()->user()->id;

        return [
            'otherUserId' => $otherUserId,
            'userId' => auth()->user()->id,
            'data' => $data,
        ];
    }

    /**
     * Gets a single chat
     *
     * @param  Chat  $chat
     * @return JsonResponse
     */
    public function show(Chat $chat) : JsonResponse
    {
        $chat->load('lastMessage.user', 'participants.user');
        return $this->success($chat);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

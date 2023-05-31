<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Events\VideoBlurEvent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     *
     * Get Users except yourself
     *
     * @return JsonRepsonse
     */
    public function index() : JsonResponse {

        $users = User::where('id', '!=', auth()->user()->id)->get();

        return $this->success($users);
    }

    public function blockUser($id)
    {
        $user = User::find($id);

        $user->update([
            'blocked' => !$user->blocked
        ]);

        return redirect()->back();
    }

    public function updateFirebaseToken(Request $request)
    {
        $token = $request->firebaseToken;

        $user = auth()->user();

        $user->update([
            'firebase_token' => $token,
        ]);

        return $this->success($user);
    }

    public function blurEvent(Request $request)
    {
        $this->validate($request, [
            'video_id' => 'required',
            'blurred' => 'required',
        ]);

        $videoId = $request->video_id;
        $blurred = $request->blurred;
        
        $event = new VideoBlurEvent($videoId, $blurred);

        broadcast($event)->toOthers();

        return $this->success($event,'Send it successfully');
    }

}

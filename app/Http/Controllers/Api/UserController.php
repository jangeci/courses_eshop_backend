<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Create User
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(),
                [
                    'avatar' => 'required',
                    'type' => 'required',
                    'open_id' => 'required',
                    'name' => 'required',
                    'email' => 'required',
                    'password' => 'nullable|min:6',
                ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $validated = $validateUser->validated();
            $map = [];
            //email, phone, google, fb, apple
            $map['type'] = $validated['type'];
            $map['open_id'] = $validated['open_id'];
            $user = User::where($map)->first();

            //check whether user already logged in
            //empty means does not exist
            //then save user for the first time
            if (empty($user->id)) {
                //this user has never been in our database
                //this token is user id
                $validated['token'] = md5(uniqid(rand(10000, 99999)));
                $validated['created_at'] = Carbon::now();
                if(!empty($validated['password'])){
                    $validated['password'] = Hash::make($validated['password']);
                }
                $userID = User::insertGetId($validated);
                $userInfo = User::where('id', $userID)->first();
                $accessToken = $userInfo->createToken(uniqid())->plainTextToken;
                $userInfo->access_token = $accessToken;
                User::where('id', $userID)->update(['access_token' => $accessToken]);

                return response()->json([
                    'code' => 200,
                    'msg' => 'User Created Successfully',
                    'data' => $userInfo
                ], 200);
            }

            //user previously has logged in
            $accessToken = $user->createToken(uniqid())->plainTextToken;
            $user->access_token = $accessToken;
            User::where('open_id', $validated['open_id'])->update(['access_token' => $accessToken]);

            return response()->json([
                'code' => 200,
                'msg' => 'User logged in Successfully',
                'data' => $user,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'msg' => $th->getMessage()
            ], 500);
        }
    }
}

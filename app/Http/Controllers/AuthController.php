<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

use App\Models\User;
use App\Models\Guest;

class AuthController extends Controller
{

    public function registerMember(Request $request)
    {
        //validate incoming request 
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        try {

            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);

            $user->save();

            //return successful response
            return response()->json(['user' => $user, 'message' => 'CREATED'], 201);

        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'User Registration Failed!'], 409);
        }

    }


    public function registerGuest(Request $request)
    {
        //validate incoming request 
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        try {

            $guest = new Guest;
            $guest->name = $request->input('name');
            $guest->email = $request->input('email');
            $plainPassword = $request->input('password');
            $guest->password = app('hash')->make($plainPassword);

            $guest->save();

            //return successful response
            return response()->json(['user' => $guest, 'message' => 'CREATED'], 201);

        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'User Registration Failed!'], 409);
        }

    }

    public function loginMember(Request $request)
    {
        // Validate incoming request 
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        Auth::factory()->setTTL(1); // Set access token expiry time to 60 minutes

        // Attempt to authenticate the user and generate an access token
        if (!$accessToken = Auth::guard('users')->attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Generate a refresh token
        $refreshToken = JWTAuth::fromUser(Auth::guard('users')->user()); // Generate a random string for the refresh token

        // Save the refresh token and its expiry time in the database for the authenticated user
        $user = User::find(Auth::guard('users')->id());
        $user->refresh_token = $refreshToken;
        $user->refresh_token_expiry = Carbon::now()->addDays(1);
        $user->save();


        // Create a response with the authentication token and refresh token
        $response = $this->respondWithToken($accessToken);
        $response->setData(array_merge($response->getData(true), [
            'refresh_token' => $refreshToken,
            'userid' => $user->id
        ]));

        return $response;
    }

    public function refreshMember(Request $request)
    {
        // Get the refresh token from the request
        $refreshToken = $request->input('refresh_token');

        // Check if the refresh token is valid
        $user = User::where('refresh_token', $refreshToken)
            ->where('id', $request->input('id'))
            ->first();

        if (!$user || $user->refresh_token_expiry < Carbon::now()) {
            return response()->json(['message' => 'Refresh token is invalid or has expired'], 401);
        }
        Auth::factory()->setTTL(1);
        // Attempt to generate a new access token using the refresh token
        Auth::guard('users')->setToken($refreshToken);
        $newAccessToken = Auth::guard('users')->refresh();

        $refreshToken = JWTAuth::fromUser($user);

        // Update the expiry time of the refresh token in the database
        $user->refresh_token = $refreshToken;
        $user->refresh_token_expiry = Carbon::now()->addDays(1);
        $user->save();

        // Create a response with the new access token and updated refresh token expiry time
        $response = $this->respondWithToken($newAccessToken);
        $response->setData(array_merge($response->getData(true), [
            'refresh_token' => $refreshToken,
            'userid' => $user->id
        ]));

        return $response;
    }

    public function loginGuest(Request $request)
    {
        // Validate incoming request 
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        Auth::factory()->setTTL(1); // Set access token expiry time to 60 minutes

        // Attempt to authenticate the user and generate an access token
        if (!$accessToken = Auth::guard('guests')->attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Generate a refresh token
        $refreshToken = JWTAuth::fromUser(Auth::guard('guests')->user()); // Generate a random string for the refresh token

        // Save the refresh token and its expiry time in the database for the authenticated user
        $user = Guest::find(Auth::guard('guests')->id());
        $user->refresh_token = $refreshToken;
        $user->refresh_token_expiry = Carbon::now()->addDays(1);
        $user->save();

        // Create a response with the authentication token and refresh token
        $response = $this->respondWithToken($accessToken);
        $response->setData(array_merge($response->getData(true), [
            'refresh_token' => $refreshToken,
            'userid' => $user->id
        ]));

        return $response;

    }


    public function refreshGuest(Request $request)
    {
        // Get the refresh token from the request
        $refreshToken = $request->input('refresh_token');

        // Check if the refresh token is valid
        $user = Guest::where('refresh_token', $refreshToken)
            ->where('id', $request->input('id'))
            ->first();

        if (!$user || $user->refresh_token_expiry < Carbon::now()) {
            return response()->json(['message' => 'Refresh token is invalid or has expired'], 401);
        }
        Auth::factory()->setTTL(1);
        // Attempt to generate a new access token using the refresh token
        Auth::guard('guests')->setToken($refreshToken);
        $newAccessToken = Auth::guard('guests')->refresh();

        $refreshToken = JWTAuth::fromUser($user);

        // Update the expiry time of the refresh token in the database
        $user->refresh_token = $refreshToken;
        $user->refresh_token_expiry = Carbon::now()->addDays(1);
        $user->save();

        // Create a response with the new access token and updated refresh token expiry time
        $response = $this->respondWithToken($newAccessToken);
        $response->setData(array_merge($response->getData(true), [
            'refresh_token' => $refreshToken,
            'userid' => $user->id
        ]));

        return $response;
    }

}
<?php

namespace App\Http\Controllers;

  use App\User;
use App\Http\Controllers\ApiController;
use App\Http\Requests\RegisterFormRequest;
use Carbon\Carbon;
use Sentinel;
use JWTAuth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends ApiController
{

    public function signin(Request $request)
    {

      $credentials = $request->only('login', 'password');

      try {

          if ($user   =  Sentinel::authenticate($credentials, false))
          {
              $token = JWTAuth::fromUser($user);
              $data = [];
              $meta = [];

              $data['name'] = $user;
              $meta['token'] = $token;

              return response()->json([
                  'data' => $data,
                  'meta' => $meta
              ], 200);
          }
          else
          {
            return response()->json([
                    'message' => 'Could not authenticate.'
                ], 400);
          }
      }
      catch(NotActivatedException $e)
      {

          return response()->json([
                  'message' => 'Your account is not activated.'
              ], 400);
      }
      catch(ThrottlingException $e)
      {
          return response()->json([
                  'message' => 'Your account is temporarily blocked.'
              ], 400);
      }

        // return response()->json([
        //         'message' => 'Invalid username or password.'
        //     ], 400);
        //   try {
        //       $token = JWTAuth::attempt($request->only('login', 'password'), [
        //           'exp' => Carbon::now()->addWeek()->timestamp,
        //       ]);
        //   } catch (JWTException $e) {
        //       return response()->json([
        //           'error' => 'Could not authenticate',
        //       ], 500);
        //   }

        //   if (!$token) {
        //       return response()->json([
        //           'error' => 'Could not authenticate',
        //       ], 401);
        //   } else {
        //       $data = [];
        //       $meta = [];

        //       $data['name'] = $request->user()->name;
        //       $meta['token'] = $token;

        //       return response()->json([
        //           'data' => $data,
        //           'meta' => $meta
        //       ]);
        //   }
    }
}

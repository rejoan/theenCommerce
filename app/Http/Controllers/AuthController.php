<?php
/**
 * Developer: Rejoanul Alam | Reviewed: 2025‑10‑16
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {


  /**
   * create access token for user.
   */
  public function createToken(Request $request) {
    try {
      $inputs = $request->all();
      $validator = Validator::make(
              $inputs,
              [
                  'email' => 'required',
                  'password' => 'required',
              ]
      );
      if ($validator->fails()) {
        return response()->json([
            'message' => $validator->messages()->first(),
            'error' => true
        ], 422);
      }
      $user = User::where(['email' => $inputs['email'],'role' => 'buyer'])->first();
      if (empty($user)) {
       return response()->json([
            'message' => 'User does not match with our record.',
            'error' => true
        ], 422);
      }
      $check = false;
      if (!empty($user)) {
        $check = Hash::check($inputs['password'], $user->password);
      }
      if ($check == false) {
        return response()->json([
            'message' => 'Password does not match with our record.',
            'error' => true
        ], 422);
      }

      $message = 'Login sucessfully';
      $user->tokens()->delete();
      $token = $user->createToken('api_token');
      $plainToken = explode('|', $token->plainTextToken);
      $data = array(
          'user_email' => $user->email,
          'token' => trim($plainToken[1]),
      );
      return response()->json([
            'message' => $message,
            'error' => false,
            'data' => $data
        ], 200);
    } catch (Exception $e) {
        return response()->json([
            'message' => 'Something is wrong, please try after some time' . $e->getMessage(),
            'error' => true,
            'data' => $data
        ], 422);
    }
  }


  /**
   * revoke token.
   */
  public function signOut(Request $request) {
    try {
      $user = $request->user();
      $user->tokens()->delete();
      return response()->json([
            'message' => 'Logout Success & Token deleted',
            'error' => false
        ], 200);
    } catch (Exception $e) {
      return response()->json([
            'message' => $e->getMessage(),
            'error' => false
        ], 422);
    }
  }
}
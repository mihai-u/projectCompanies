<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Google2FA;
use \ParagonIE\ConstantTime\Base32;
use Crypt;



class profileController extends Controller
{

    //This function returns the user profile page
    public function profile(){
        $user = Auth::user();

        return view('user.profile', [
            'user' => $user
        ]);
    }

    private function generateSecret()
    {
        $randomBytes = random_bytes(10);

        return Base32::encodeUpper($randomBytes);
    }

    public function enableTwoFactor(Request $request)
    {
        //generate new secret
        $secret = $this->generateSecret();

        //get user
        $user = Auth::user();

        //encrypt and then save secret
        $user->google2fa_secret = Crypt::encrypt($secret);
        $user->use2fa = 1;
        $user->save();

        //generate image for QR barcode
        $imageDataUri = Google2FA::getQRCodeInline(
            $request->getHttpHost(),
            $user->email,
            $secret,
            200
        );
        // dd($user);

        return response()->json([
            'use2fa' => $user->use2fa,
            'qrSrc' => $imageDataUri,
            'secret' => $secret
        ]);
    }

    public function disableTwoFactor(Request $request){
        // dd("in disable");
        $user = Auth::user();

        $user->google2fa_secret = NULL;
        $user->use2fa = 0;
        $user->save();

        return response()->json([
            'use2fa' => $user->use2fa,
        ]);
    }

    //This function is used in the /profile page to update the user info(name, username, password) based on the input from the user
    //It returns a json with the new name, username, password and an error code if the user entered an incorrect old password
    public function updateInfo(Request $request){
        $user = Auth::user(); //Gets the authenticated user
        $hashedPassword = Hash::make($request->newPassword); //Hashes the input password

        $user->name = $request->newName;
        $user->username = $request->newUsername;

        //Checks if the user entered a correct old passoword
        if (Hash::check($request->password, Auth::user()->password)) {
            $errorOldPassword = 0;
            return response()->json([
                'name' => $user->name,
                'username' => $user->username,
                'errorOldPassword' => $errorOldPassword
            ]);
        }else{
            $errorOldPassword = 1;
        }


        $user->password = $hashedPassword;
        $user->save();

            return response()->json([
                'name' => $user->name,
                'username' => $user->username,
                'newPassword' => $user->password,
                'errorOldPassword' => $errorOldPassword
            ]);

    }

    //This function is used on the /profile page to change the profile photo
    //It return a json with the new photo url
    public function updatePhoto(Request $request){
        $user = Auth::user();

        $user->profile_img = $request->profile_img;
        $user->save();

        return response()->json([
            'newPhotoUrl' => $user->profile_img
        ]);
    }
}

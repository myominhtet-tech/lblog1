<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function index(){
        return view('author.settings');
        }
        public function updateProfile(Request $request) {
        $this->validate($request, [
        'name' => 'required',
        'email' => 'required|email',
        'image' => 'required|image',
        ]);

        $image = $request->file('image');
        $slug = str_slug($request->name);
        $user = User::findOrFail(Auth::id());
        if(isset($image)) {

                //Make Unique Name for Image
                $currentDate = Carbon::now()->toDateString();
                $imageName = $slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();

                //Check Category Dir is exists

                if(!Storage::disk('public')->exists('profile')) {
                    Storage::disk('public')->makeDirectory('profile');
                }


                  //Delete Old Image
                  if(Storage::disk('public')->exists('profile/'.$user->image)) {
                    Storage::disk('public')->delete('profile/'.$user->image);
                }

                //Resize Image for category and upload
                $profile = Image::make($image)->resize(500,500)->stream();
                Storage::disk('public')->put('profile/'.$imageName,$profile);

        }else {
            $imageName = $user->image;
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->image = $imageName;
        $user->about = $request->about;
        $user->save();
        return redirect()->back()->with('successMsg', 'User Profile Updated Successfully');
        }

        public function updatePassword(Request $request) {
            $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required',
            'confirm_password' => 'required',
            ]);
    //  return $request->old_password;
             $hashedPassword = Auth::user()->password;

             if(Hash::check($request->old_password, $hashedPassword)) {
                if(!Hash::check($request->password, $hashedPassword)) {
                     $user = User::find(Auth::id());
                    $user->password = Hash::make($request->password);
                    $user->save();
                    Auth::logout();
                    return redirect()->back()->with('successMsg','Password Updated Successfully');
                 }
                else{
                    return $request."else one";
                    return redirect()->back()->with('loginerrorMsg', 'New Password Cannot be same as old password');
                }

             }
            else{
                return redirect()->back()->with('loginerrorMsg', 'Current Password is not match');

            }

         }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subscriber;
class SubscriberController extends Controller
{

    public function store(Request $request) {
        // return $request->all();
        $this->validate($request, [
            'email' => 'required|email|unique:subscribers'
        ]);
        $subscriber = new Subscriber();
        $subscriber->email = $request->email;
        $subscriber->save();
        return redirect(route('mainhome')."#footer")->with('successMg', 'Subscriber Added Successfully');
    }
}

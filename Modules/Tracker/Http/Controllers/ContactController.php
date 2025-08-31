<?php

namespace Modules\Tracker\Http\Controllers;

use App\Contact;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactController extends Controller
{
   public function map(Request $request){
       $this->authorize('tracker.access_map');
       $business_id = session()->get('user.business_id');
       $contacts = Contact::whereNotNull('location')->where('business_id', $business_id)->get();

       $menuItems = $request->menuItems;
       return view('tracker::contacts.map', compact('contacts','menuItems'));
   }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    /**
     * Display the landing page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
       
        return view('landing');
    }
    /**
 * Handle contact form submission.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\RedirectResponse
 */
public function submitContact(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'message' => 'required|string',
    ]);
    
    // Here you can save the contact form data to the database
    // or send an email notification
    
    return redirect()->route('landing')->with('success', 'Your message has been sent successfully. We will get back to you soon.');
}
}
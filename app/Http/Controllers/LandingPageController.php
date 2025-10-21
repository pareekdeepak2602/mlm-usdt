<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvestmentPlan;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Mail;

class LandingPageController extends Controller
{
    /**
     * Display the landing page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $plans = InvestmentPlan::where('status', 'active')
                              ->orderBy('level', 'asc')
                              ->get();
        
        $levelRequirements = $plans->map(function($plan) {
            return [
                'level' => $plan->level,
                'daily_percentage' => $plan->daily_percentage,
                'direct_referrals' => $plan->direct_referrals_required,
                'indirect_referrals' => $plan->indirect_referrals_required,
                'asset_hold' => $plan->asset_hold
            ];
        });

        return view('landing', compact('plans', 'levelRequirements'));
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
            'message' => 'required|string|min:10',
        ]);

        // Save to database
        ContactMessage::create($validated);

        // Send email notification (optional)
        try {
            Mail::send('emails.contact-notification', $validated, function($message) use ($validated) {
                $message->to('pareekdeepak155@outlook.com')
                        ->subject('New Contact Form Submission')
                        ->from($validated['email'], $validated['name']);
            });
        } catch (\Exception $e) {
            \Log::error('Contact form email failed: ' . $e->getMessage());
        }

        return redirect()->route('landing')->with('success', 'Your message has been sent successfully. We will get back to you soon.');
    }
}
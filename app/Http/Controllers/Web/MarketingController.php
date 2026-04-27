<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MarketingController extends Controller
{
    public function home(): View
    {
        return view('marketing.home');
    }

    public function about(): View
    {
        return view('marketing.about');
    }

    public function howItWorks(): View
    {
        return view('marketing.how-it-works');
    }

    public function partner(): View
    {
        return view('marketing.partner');
    }

    public function partnerSubmit(Request $request): RedirectResponse
    {
        $request->validate([
            'organization' => ['required', 'string', 'max:255'],
            'contact_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        // Phase 1: log to storage; Phase 2 will push to admin incidents/leads table.
        logger()->info('Partner application submitted', $request->only(
            ['organization', 'contact_name', 'email', 'phone', 'message']
        ));

        return back()->with('success', 'Thanks — the CATATU team will reach out within 2 business days.');
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SurveyStepMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Store the allowed flow order
        $steps = [
            'survey-consent',
            'survey-start',
            'submit-survey-start',
            'citizens-charter',
            'submit-citizens-charter',
            'service-quality',
            'submit-service-quality',
            'suggestion',
            'submit-suggestion',
            'finished',
        ];

        // Get the name of the current route
        $currentRoute = $request->route()->getName();

        // Get the last completed step from session
        $lastStep = Session::get('survey_step', 'survey-consent');

        // Allow access only if:
        //  - current route index <= last completed step index + 1
        $currentIndex = array_search($currentRoute, $steps);
        $lastIndex = array_search($lastStep, $steps);

        if ($currentIndex === false) {
            return redirect()->route('user.survey-consent');
        }

        if ($currentIndex > $lastIndex + 1) {
            // User trying to skip ahead
            return redirect()->route('user.' . $lastStep);
        }
        
        return $next($request);
    }
}
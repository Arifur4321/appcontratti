<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Template;
use Illuminate\Support\Facades\Auth;

class TemplateController extends Controller
{
    public function show()
    {
        // Get the currently logged-in user
        $user = Auth::user();

        // Retrieve the template for the logged-in user's company, if it exists
        $template = Template::where('company_id', $user->company_id)->first();

        // Default email content if no template exists
        $defaultEmailContent = "
            <p>Caro cliente,</p>
            <p>Per favore firma il documento cliccando sul link qui sotto:</p>
            <p><a href='#'>Firmare il documento</a></p>
            <p>Distinti saluti,<br>GF SRL.</p>
        ";

        // Default SMS content
        $defaultSmsContent = "Ciao, siamo di Codice 1%. Ecco il tuo contratto. Per favore firma questo documento: %Link%";

        // Determine the email content to display
        if ($template && !empty($template->email_content)) {
            $emailContent = $template->email_content;
        } else {
            $emailContent = $defaultEmailContent;
        }

        // Determine the SMS content to display
        if ($template && !empty($template->sms_content)) {
            $smsContent = $template->sms_content;
        } else {
            $smsContent = $defaultSmsContent;
        }

        // Pass the existing template content or default content to the view
        return view('Email-SMS-Template', compact('emailContent', 'smsContent'));
    }

    public function store(Request $request)
    {
        // Get the currently logged-in user
        $user = Auth::user();

        // Determine which form was submitted
        $isEmailTemplate = $request->has('email_template');
        $isSmsTemplate = $request->has('sms_template');

        // Initialize validation rules
        $rules = [];

        if ($isEmailTemplate) {
            $rules['email_template'] = ['required', 'string', function ($attribute, $value, $fail) {
                if (!preg_match('/%\s*[^%]+\s*%/', $value)) {
                    $fail('Create your signature link in the email template using %%. For example: %Click Here%');
                }
            }];
        }

        if ($isSmsTemplate) {
            $rules['sms_template'] = ['required', 'string', function ($attribute, $value, $fail) {
                if (!preg_match('/%\s*[^%]+\s*%/', $value)) {
                    $fail('Create your signature link in the SMS template using %%. For example: %Link%');
                }
            }];
        }

        // Validate the input
        $request->validate($rules);

        // Check if the template for this company already exists
        $template = Template::where('company_id', $user->company_id)->first();

        if (!$template) {
            // If no template exists, create a new one
            $template = new Template();
            $template->compane_email = $user->email;
            $template->company_id = $user->company_id;
        }

        // Update the template content
        if ($isEmailTemplate) {
            $template->email_content = $request->email_template;
        }
        if ($isSmsTemplate) {
            $template->sms_content = $request->sms_template;
        }

        $template->save();

        return response()->json(['success' => 'Template saved successfully']);
    }
}

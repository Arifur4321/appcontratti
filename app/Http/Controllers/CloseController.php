<?php

namespace App\Http\Controllers;
use App\Models\AppConnection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use GuzzleHttp\Client; 
use Gyurobenjamin\Closeio\Closeio;
 

class CloseController extends Controller

{
      public function __construct()
        {
            $this->middleware('auth');
        }


        public function index()
        {
            $user = Auth::user();
            $company_id = $user->company_id;

            // Initialize variables for each type
            $appConnections = [
                'Close' => ['api_key' => '', 'pending' => '', 'signed' => ''],
                'ActiveCampaign' => ['api_key' => '', 'pending' => '', 'signed' => '', 'tags' => [] , 'signedTags' => []], 
                'Zapier' => ['api_key' => ''],
                'Salesforce' => ['api_key' => ''],
                'Pipedrive' => ['api_key' => ''],
                'SMS' => ['sms_enabled' => false],
                'Sales_SMS' => ['sales_sms_enabled' => false]
            ];

            // Fetch all app connections for the company_id
            $connections = AppConnection::where('company_id', $company_id)->get();

            // Loop through each connection and decode the api_key field
            foreach ($connections as $connection) {
                $decodedData = $this->isValidJson($connection->api_key) ? json_decode($connection->api_key, true) : [];

                switch ($connection->type) {
                    case 'Close':
                        $appConnections['Close'] = [
                            'api_key' => $decodedData['api_key'] ?? '',
                            'pending' => $decodedData['pending'] ?? '',
                            'signed' => $decodedData['signed'] ?? ''
                        ];
                        break;

                    case 'ActiveCampaign':
                        $appConnections['ActiveCampaign'] = [
                            'api_key' => $decodedData['api_key'] ?? '',
                            'pending' => $decodedData['pending'] ?? '',
                            'signed' => $decodedData['signed'] ?? '',
                            'tags' => is_string($decodedData['selectedTags'] ?? null) ? json_decode($decodedData['selectedTags'], true) : ($decodedData['selectedTags'] ?? []),
                            'signedTags' => is_string($decodedData['selectedSignedTags'] ?? null) ? json_decode($decodedData['selectedSignedTags'], true) : ($decodedData['selectedSignedTags'] ?? [])
                        ];
                        
                        break;

                    case 'Zapier':
                        $appConnections['Zapier']['api_key'] = $decodedData['api_key'] ?? '';
                        break;

                    case 'Salesforce':
                        $appConnections['Salesforce']['api_key'] = $decodedData['api_key'] ?? '';
                        break;

                    case 'Pipedrive':
                        $appConnections['Pipedrive']['api_key'] = $decodedData['api_key'] ?? '';
                        break;

                    case 'SMS':
                        $appConnections['SMS']['sms_enabled'] = $decodedData['sms_enabled'] ?? false;
                        break;

                    case 'Sales_SMS':
                        $appConnections['Sales_SMS']['sales_sms_enabled'] = $decodedData['sales_sms_enabled'] ?? false;
                        break;
                }
            }

            return view('AppConnections', [
                'appConnections' => $appConnections
            ]);
        }

        private function isValidJson($string)
        {
            json_decode($string);
            return (json_last_error() == JSON_ERROR_NONE);
        }
        
    

        public function saveApiKey(Request $request)
    
        {
            $request->validate([
                'api_key' => 'required|string',
                'type' => 'required|string|in:Close,ActiveCampaign,Zapier,Salesforce,Pipedrive',
    
               // 'tags' => 'array   ', // Add validation for tags if provided
               'tags' => 'sometimes|json',
            ]);
        
            $user = Auth::user();

            $company_id = $user->company_id;
        
            // Create a JSON structure to store the api_key, pending, signed notes, and selected tags together.
            $apiData = json_encode([
                'api_key' => $request->api_key,
                'pending' => $request->pending ?? '',
                'signed' => $request->signed ?? '',
                'selectedTags' => $request->tags ?? [], // Add selected tags to the JSON structure
                'selectedSignedTags' => $request->signedTags ?? []
            ]);
        
            if ($request->type === 'Close') {
        
                $client = new Client();
        
                try {
                    $response = $client->request('GET', 'https://api.close.com/api/v1/me/', [
                        'auth' => [$request->api_key, '']
                    ]);
        
                    if ($response->getStatusCode() === 200) {
                        $appConnection = AppConnection::where('company_id', $company_id)
                                                      ->where('type', $request->type)
                                                      ->first();
        
                        if ($appConnection) {
                            $appConnection->update(['api_key' => $apiData]);
                        } else {
                            AppConnection::create([
                                'company_id' => $company_id,
                                'type' => $request->type,
                                'api_key' => $apiData,
                            ]);
                        }
        
                        return response()->json(['success' => 'Close API Key saved successfully.']);

                    } else {

                        return response()->json(['error' => 'Invalid Close API Key.'], 400);
                    }

                } catch (\Exception $e) {  
                    return response()->json([
                        'error' => 'Failed to validate Close API key.',
                        'message' => $e->getMessage(),
                    ], 500);
                }
        
            } elseif ($request->type === 'ActiveCampaign') {
        
                $client = new Client();
        
                try {
                    $response = $client->request('GET', 'https://giacomofreddi.api-us1.com/api/3/contacts', [
                        'headers' => [
                            'Api-Token' => $request->api_key
                        ]
                    ]);
        
                    if ($response->getStatusCode() === 200) {
                        $appConnection = AppConnection::updateOrCreate(
                            ['company_id' => $company_id, 'type' => $request->type],
                            ['api_key' => $apiData]
                        );
        
                        return response()->json(['success' => 'ActiveCampaign API Key saved successfully.']);
                    } else {
                        return response()->json(['error' => 'Invalid ActiveCampaign API Key.'], 400);
                    }
                } catch (\Exception $e) {
                    return response()->json([
                        'error' => 'Failed to validate ActiveCampaign API key.',
                        'message' => $e->getMessage(),
                    ], 500);
                }
        
            } else {
                // Update or create for other types without additional validation
                $appConnection = AppConnection::updateOrCreate(
                    ['company_id' => $company_id, 'type' => $request->type],
                    ['api_key' => $apiData]
                );
        
                return response()->json(['success' => "{$request->type} API Key saved successfully."]);
            }
        }
        

    // public function index()
    // {
    //     $user = Auth::user();
    //     $company_id = $user->company_id;
    
    //     // Initialize variables for each type
    //     $appConnections = [
    //         'Close' => ['api_key' => '', 'pending' => '', 'signed' => ''],
    //         'ActiveCampaign' => ['api_key' => '', 'pending' => '', 'signed' => '' , 'tags' => []],  
    //         'Zapier' => ['api_key' => ''],
    //         'Salesforce' => ['api_key' => ''],
    //         'Pipedrive' => ['api_key' => ''],
    //         'SMS' => ['sms_enabled' => false],
    //         'Sales_SMS' => ['sales_sms_enabled' => false]
    //     ];
    
    //     // Fetch all app connections for the company_id
    //     $connections = AppConnection::where('company_id', $company_id)->get();
    
    //     // Loop through each connection and decode the api_key field
    //     foreach ($connections as $connection) {
    //         $decodedData = $this->isValidJson($connection->api_key) ? json_decode($connection->api_key, true) : [];
    
    //         // Based on the type, assign values to the relevant keys
    //         switch ($connection->type) {
    //             case 'Close':
    //                 $appConnections['Close'] = [
    //                     'api_key' => $decodedData['api_key'] ?? '',
    //                     'pending' => $decodedData['pending'] ?? '',
    //                     'signed' => $decodedData['signed'] ?? ''
    //                 ];
    //                 break;

    //             case 'ActiveCampaign': // Added case for ActiveCampaign
    //                     $appConnections['ActiveCampaign'] = [
    //                         'api_key' => $decodedData['api_key'] ?? '',
    //                         'pending' => $decodedData['pending'] ?? '',
    //                         'signed' => $decodedData['signed'] ?? '' ,
    //                         'tags' => $decodedData['selectedTags'] ?? [] // Retrieve saved tags here
    //                     ];
    //                 break;   
    
    //             case 'Zapier':
    //                 $appConnections['Zapier']['api_key'] = $decodedData['api_key'] ?? '';
    //                 break;
    
    //             case 'Salesforce':
    //                 $appConnections['Salesforce']['api_key'] = $decodedData['api_key'] ?? '';
    //                 break;
    
    //             case 'Pipedrive':
    //                 $appConnections['Pipedrive']['api_key'] = $decodedData['api_key'] ?? '';
    //                 break;
    
    //             case 'SMS':
    //                 $appConnections['SMS']['sms_enabled'] = $decodedData['sms_enabled'] ?? false;
    //                 break;
    
    //             case 'Sales_SMS':
    //                 $appConnections['Sales_SMS']['sales_sms_enabled'] = $decodedData['sales_sms_enabled'] ?? false;
    //                 break;
    //         }
    //     }
    
    //     // Pass all the app connection data to the view
    //     return view('AppConnections', [
    //         'appConnections' => $appConnections
    //     ]);
    // }
 




    //----------------------------------------------

    // public function saveApiKey(Request $request)
    // {
    //     $request->validate([
    //         'api_key' => 'required|string',
    //         'type' => 'required|string|in:Close,ActiveCampaign,Zapier,Salesforce,Pipedrive',
    //     ]);
    
    //     $user = Auth::user();
    //     $company_id = $user->company_id;
    
    //     // Create a JSON structure to store the api_key, pending, and signed notes together.
    //     $apiData = json_encode([
    //         'api_key' => $request->api_key,
    //         'pending' => $request->pending ?? '',
    //         'signed' => $request->signed ?? '',
    //     ]);
    
    //     if ($request->type === 'Close') {
    
    //         $client = new Client();
    
    //         try {
    //             $response = $client->request('GET', 'https://api.close.com/api/v1/me/', [
    //                 'auth' => [$request->api_key, '']
    //             ]);
    
    //             if ($response->getStatusCode() === 200) {
    //                 $appConnection = AppConnection::where('company_id', $company_id)
    //                                               ->where('type', $request->type)
    //                                               ->first();
    
    //                 if ($appConnection) {
    //                     $appConnection->update(['api_key' => $apiData]);
    //                 } else {
    //                     AppConnection::create([
    //                         'company_id' => $company_id,
    //                         'type' => $request->type,
    //                         'api_key' => $apiData,
    //                     ]);
    //                 }
    
    //                 return response()->json(['success' => 'Close API Key saved successfully.']);
    //             } else {
    //                 return response()->json(['error' => 'Invalid Close API Key.'], 400);
    //             }
    //         } catch (\Exception $e) {
    //             return response()->json([
    //                 'error' => 'Failed to validate Close API key.',
    //                 'message' => $e->getMessage(),
    //             ], 500);
    //         }
    
    //     } elseif ($request->type === 'ActiveCampaign') {
    
    //         $client = new Client();
    
    //         try {
                 
    //          //    Use POST for ActiveCampaign /contact/sync endpoint with minimal payload
    //             // $response = $client->request('POST', 'https://giacomofreddi.api-us1.com/api/3/contact/sync', [
    //             //     'headers' => [
    //             //         'Api-Token' => $request->api_key
    //             //     ],
    //             //     'json' => [
    //             //         'contact' => [
    //             //             'email' => 'airfur@gicomofreddi.it' // Minimal payload to validate
    //             //         ]
    //             //     ]
    //             // ]);

    //             $response = $client->request('GET', 'https://giacomofreddi.api-us1.com/api/3/contacts', [
    //                 'headers' => [
    //                     'Api-Token' => $request->api_key
    //                 ]
    //             ]);
                    
    //             if ($response->getStatusCode() === 200) {
    //                 $appConnection = AppConnection::updateOrCreate(
    //                     ['company_id' => $company_id, 'type' => $request->type],
    //                     ['api_key' => $apiData]
    //                 );
    
    //                 return response()->json(['success' => 'ActiveCampaign API Key saved successfully.']);
    //             } else {
    //                 return response()->json(['error' => 'Invalid ActiveCampaign API Key.'], 400);
    //             }
    //         } catch (\Exception $e) {
    //             return response()->json([
    //                 'error' => 'Failed to validate ActiveCampaign API key.',
    //                 'message' => $e->getMessage(),
    //             ], 500);
    //         }
    
    //     } else {
    //         // Update or create for other types without additional validation
    //         $appConnection = AppConnection::updateOrCreate(
    //             ['company_id' => $company_id, 'type' => $request->type],
    //             ['api_key' => $apiData]
    //         );
    
    //         return response()->json(['success' => "{$request->type} API Key saved successfully."]);
    //     }
    // }
    

    //--------------------------------------------------------

    public function getActiveCampaignLeads(Request $request)
    {
        $user = Auth::user();
        $company_id = $user->company_id;
    
        $appConnection = AppConnection::where('company_id', $company_id)
                                    ->where('type', 'ActiveCampaign')
                                    ->first();
    
        if (!$appConnection || empty($appConnection->api_key)) {
            return response()->json(['success' => false, 'error' => 'No ActiveCampaign API Key found.'], 400);
        }
    
        $apiKeyData = json_decode($appConnection->api_key, true);
        $apiKey = $apiKeyData['api_key'] ?? null;
    
        if (!$apiKey) {
            return response()->json(['success' => false, 'error' => 'ActiveCampaign API Key is invalid.'], 400);
        }
    
        try {
            $client = new Client();
            $response = $client->request('GET', 'https://giacomofreddi.api-us1.com/api/3/tags', [
                'headers' => [
                    'Api-Token' => $apiKey
                ],
                'timeout' => 30
            ]);

 
    
            $tags = json_decode($response->getBody()->getContents(), true);
            $allTags = $tags['tags'] ?? [];
    
            return response()->json([
                'success' => true,
                'data' => $allTags
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch tags from ActiveCampaign.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    
 
//----------------------------------------------------------------------------------------------------
    // public function getActiveCampaignLeads(Request $request)
    // {
    //     $user = Auth::user();
    //     $company_id = $user->company_id;
    
    //     $appConnection = AppConnection::where('company_id', $company_id)
    //                                   ->where('type', 'ActiveCampaign')
    //                                   ->first();
    
    //     if (!$appConnection || empty($appConnection->api_key)) {
    //         return response()->json(['error' => 'No ActiveCampaign API Key found.'], 400);
    //     }
    
    //     $apiKeyData = json_decode($appConnection->api_key, true);
    //     $apiKey = $apiKeyData['api_key'] ?? null;
    
    //     if (!$apiKey) {
    //         return response()->json(['error' => 'ActiveCampaign API Key is invalid.'], 400);
    //     }
    
    //     $client = new Client();
    //     $page = session('activecampaign_page', 1); // Track page number in session
    
    //     try {
    //         $response = $client->request('GET', 'https://giacomofreddi.api-us1.com/api/3/contacts', [
    //             'headers' => [
    //                 'Api-Token' => $apiKey
    //             ],
    //             'query' => [
    //                 'page' => $page,
    //                 'limit' => 20 // Fetch 20 contacts per page
    //             ],
    //             'timeout' => 30
    //         ]);
    
    //         $leads = json_decode($response->getBody()->getContents(), true);
    //         $contacts = $leads['contacts'] ?? [];
    
    //         // Update the session with the next page number
    //         if (!empty($contacts)) {
    //             session(['activecampaign_page' => $page + 1]);
    //         } else {
    //             session(['activecampaign_page' => 1]); // Reset if no more leads
    //         }
    
    //         return response()->json([
    //             'success' => true,
    //             'data' => $contacts
    //         ]);
    
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'error' => 'Failed to fetch leads from ActiveCampaign.',
    //             'message' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    // now i want to get all the tags and return as response with this method instead of contacts
    
 
    // public function saveApiKey(Request $request)
    // {
    //     $request->validate([
    //         'api_key' => 'required|string',
    //         'type' => 'required|string|in:Close,Zapier,Salesforce,Pipedrive',
    //     ]);
    
    //     $user = Auth::user();
    //     $company_id = $user->company_id;
    
    //     // Create a JSON structure to store the api_key, pending, and signed notes together.
    //     $apiData = json_encode([
    //         'api_key' => $request->api_key,
    //         'pending' => $request->pending ?? '', // Use the value or default to an empty string if not provided
    //         'signed' => $request->signed ?? '',   // Use the value or default to an empty string if not provided
    //     ]);
    
    //     if ($request->type === 'Close') {
    
    //         $client = new Client();
            
    //         try {
    //             $response = $client->request('GET', 'https://api.close.com/api/v1/me/', [
    //                 'auth' => [$request->api_key, '']
    //             ]);
    
    //             if ($response->getStatusCode() === 200) {
    //                 $appConnection = AppConnection::where('company_id', $company_id)
    //                                             ->where('type', $request->type)
    //                                             ->first();
    
    //                 if ($appConnection) {
    //                     $appConnection->update(['api_key' => $apiData]);
    //                 } else {
    //                     AppConnection::create([
    //                         'company_id' => $company_id,
    //                         'type' => $request->type,
    //                         'api_key' => $apiData,
    //                     ]);
    //                 }
    
    //                 return response()->json(['success' => 'Close API Key saved successfully.']);
    //             } else {
    //                 return response()->json(['error' => 'Invalid Close API Key.'], 400);
    //             }
    //         } catch (\Exception $e) {
    //             return response()->json([
    //                 'error' => 'Failed to validate Close API key.',
    //                 'message' => $e->getMessage(),
    //             ], 500);
    //         }
            
    //     } else {
    //         $appConnection = AppConnection::updateOrCreate(
    //             ['company_id' => $company_id, 'type' => $request->type],
    //             ['api_key' => $apiData]
    //         );
    
    //         return response()->json(['success' => "{$request->type} API Key saved successfully."]);
    //     }
    // }

    

    public function saveSMSToggle(Request $request)
        {
            $user = Auth::user();
            $company_id = $user->company_id;

            // Fetch or create the AppConnection record for SMS
            $appConnection = AppConnection::firstOrNew([
                'company_id' => $company_id,
                'type' => 'SMS',
            ]);

            // Decode the existing api_key JSON or initialize an empty object
            $apiKeyData = json_decode($appConnection->api_key) ?? new \stdClass();

            // Check if api_key is not an array or object, and initialize it as an empty stdClass
            if (!is_object($apiKeyData)) {
                $apiKeyData = new \stdClass();
            }

            // Update the sms_enabled property based on the toggle switch
            $apiKeyData->sms_enabled = $request->has('enable_sms') ? true : false;

            // Save the updated api_key back as JSON
            $appConnection->api_key = json_encode($apiKeyData);

            // Save the AppConnection record
            $appConnection->save();

            return response()->json(['success' => 'SMS setting saved successfully!']);
        }


public function saveSalesSmsToggle(Request $request)
{
    $user = Auth::user();
    $company_id = $user->company_id;

    // Fetch or create the AppConnection record for Sales SMS
    $appConnection = AppConnection::firstOrNew([
        'company_id' => $company_id,
        'type' => 'Sales_SMS', // Ensure this matches the type for Sales SMS
    ]);

    // Decode the existing api_key JSON or initialize an empty object
    $apiKeyData = json_decode($appConnection->api_key) ?? new \stdClass();

    // Ensure that the api_key is an object (stdClass)
    if (!is_object($apiKeyData)) {
        $apiKeyData = new \stdClass();
    }

    // Update the sales_sms_enabled property based on the toggle switch
    $apiKeyData->sales_sms_enabled = $request->has('enable_sales_sms') ? true : false;

    // Save the updated api_key back as JSON
    $appConnection->api_key = json_encode($apiKeyData);

    // Save the AppConnection record
    $appConnection->save();

    return response()->json(['success' => 'Sales SMS setting updated successfully.']);
}





    // testing saveApi Key ------------------------------------

    // public function saveApiKey(Request $request)
    // {
    //     $request->validate([
    //         'api_key' => 'required|string',
    //         'type' => 'required|string|in:Close,Zapier,Salesforce,Pipedrive',
    //     ]);
    
    //     $user = Auth::user();
    //     $company_id = $user->company_id;
    
    //     // Create a JSON structure to store the api_key, pending, and signed notes together.
    //     $apiData = json_encode([
    //         'api_key' => $request->api_key,
    //         'pending' => $request->pending ?? '', // Use the value or default to an empty string if not provided
    //         'signed' => $request->signed ?? '',   // Use the value or default to an empty string if not provided
    //     ]);
    
    //     if ($request->type === 'Close') {
    
    //         $client = new Client();
    
    //         try {

    //             $response = $client->request('GET', 'https://api.close.com/api/v1/me/', [
    //                 'auth' => [$request->api_key, '']
    //             ]);
    
    //             if ($response->getStatusCode() === 200) {
    //                 $appConnection = AppConnection::where('company_id', $company_id)
    //                                                 ->where('type', $request->type)
    //                                                 ->first();
    
    //                 if ($appConnection) {
    //                     $appConnection->update(['api_key' => $apiData]);
    //                 } else {
    //                     AppConnection::create([
    //                         'company_id' => $company_id,
    //                         'type' => $request->type,
    //                         'api_key' => $apiData,
    //                     ]);
    //                 }
    
    //                 return response()->json(['success' => 'Close API Key saved successfully.']);
    //             } else {
    //                 return response()->json(['error' => 'Invalid Close API Key.'], 400);
    //             }
    //         } catch (\Exception $e) {
    //             return response()->json([
    //                 'error' => 'Failed to validate Close API key.',
    //                 'message' => $e->getMessage(),
    //             ], 500);
    //         }
    
    //     } elseif ($request->type === 'Zapier') {
    //         // Initialize the HTTP client for Zapier validation
    //         $client = new Client();
    
    //         try {
    //             // Replace 'https://zapier.com/api/test-endpoint' with the actual Zapier endpoint for validation
    //             $response = $client->get('https://zapier.com/api/test-endpoint', [
    //                 'headers' => [
    //                     'Authorization' => 'Bearer ' . $request->api_key,
    //                 ]
    //             ]);

    //             // 
    
    //             if ($response->getStatusCode() === 200) {
    //                 // Assuming the response contains some data you want to use
    //                 $zapierData = json_decode($response->getBody()->getContents(), true);
    
    //                 // Save the API key
    //                 $appConnection = AppConnection::updateOrCreate(
    //                     ['company_id' => $company_id, 'type' => $request->type],
    //                     ['api_key' => $apiData]
    //                 );
    
    //                 // Return success message and data to use in the modal
    //                 return response()->json([
    //                     'success' => 'Zapier API Key saved and validated successfully.',
    //                     'data' => $zapierData  // This will contain the data to display in the modal
    //                 ]);
    //             } else {
    //                 return response()->json(['error' => 'Invalid Zapier API Key.'], 400);
    //             }
    //         } catch (\Exception $e) {
    //             return response()->json([
    //                 'error' => 'Failed to validate Zapier API key.',
    //                 'message' => $e->getMessage(),
    //             ], 500);
    //         }
    //     } else {
    //         // Default behavior for other types
    //         $appConnection = AppConnection::updateOrCreate(
    //             ['company_id' => $company_id, 'type' => $request->type],
    //             ['api_key' => $apiData]
    //         );
    
    //         return response()->json(['success' => "{$request->type} API Key saved successfully."]);
    //     }
    // }


    
    public function getLeads(Request $request)

    {
        $user = Auth::user();
        $company_id = $user->company_id;

        $type = $request->get('type', 'Close');  

        $api_key = $request->get('api_key');

        // Retrieve the existing API key for the selected type
        $appConnection = AppConnection::where('company_id', $company_id)
                                    ->where('type', $type)
                                    ->first();

        if (!$appConnection || !$appConnection->api_key) {

            return response()->json(['error' => 'API Key not found for the selected CRM type.'], 404);
        
        }

        $client = new Client();

        try {
            $response = $client->request('GET', 'https://api.close.com/api/v1/lead/', [
                'auth' => [$api_key, ''] // Use Basic Auth with the API key as the username
            ]);

            $leads = json_decode($response->getBody()->getContents(), true);
            return response()->json($leads);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch leads.',
                'message' => $e->getMessage(),
                'code' => $e->getCode(),   
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }

    public function addLead(Request $request)

    {
        $user = Auth::user();
        $company_id = $user->company_id;

        // Retrieve the existing API key
        $appConnection = AppConnection::where('company_id', $company_id)->first();

        if (!$appConnection || !$appConnection->api_key) {
            return response()->json(['error' => 'API Key not found.'], 404);
        }

        $client = new Client();
        $data = [
            'name' => $request->input('name'),
            'status_id' => $request->input('status_id'),
        ];

        try {
            $response = $client->request('POST', 'https://api.close.com/api/v1/lead/', [
                'auth' => [$appConnection->api_key, ''], // Use Basic Auth with the API key as the username
                'json' => $data, // Pass data directly as JSON
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to add lead.',
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }


    public function getLeadStatuses(Request $request)
    {
        $user = Auth::user();
        $company_id = $user->company_id;

        // Retrieve the existing API key
        $appConnection = AppConnection::where('company_id', $company_id)->first();

        if (!$appConnection || !$appConnection->api_key) {
            return response()->json(['error' => 'API Key not found.'], 404);
        }

        $client = new Client();

        try {
            $response = $client->request('GET', 'https://api.close.com/api/v1/status/lead/', [
                'auth' => [$appConnection->api_key, ''], // Use Basic Auth with the API key as the username
            ]);

            $statuses = json_decode($response->getBody()->getContents(), true);
            return response()->json($statuses);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch lead statuses.',
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }


 

    public function addComment(Request $request, $leadId)
    {
        $user = Auth::user();
        $company_id = $user->company_id;

        // Retrieve the existing API key
        $appConnection = AppConnection::where('company_id', $company_id)->first();

        if (!$appConnection || !$appConnection->api_key) {
            return response()->json(['error' => 'API Key not found.'], 404);
        }

        $client = new Client();
        $data = [
            'note' => $request->input('note'),
            'lead_id' => $leadId,
        ];

        try {
            $response = $client->request('POST', 'https://api.close.com/api/v1/activity/note/', [
                'auth' => [$appConnection->api_key, ''], // Use Basic Auth with the API key as the username
                'json' => $data, // Pass data directly as JSON
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to add comment.',
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }



}
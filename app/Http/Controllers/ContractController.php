<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Contract;  
use App\Models\VariableList; 
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use App\Models\User;

use Illuminate\Support\Facades\Auth;
 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ContractController extends Controller
{


       public function __construct()
    {
        $this->middleware('auth');
    }



    public function createContractWithUpdatePage()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Create a new contract with only the ID incremented
        $contract = new Contract();
        $contract->contract_name = "Write your contract name"; // Set the default value for contract_name
        $contract->company_id = $user->company_id; // Set the company_id from the authenticated user
        $contract->save();

        // Get the ID of the newly created contract
        $contractId = $contract->id;

        // Redirect to the edit-contract-list page with the new contract ID
        echo '<script>window.location.href = "/edit-contract-list/' . $contractId . '";</script>';
    }


    /**
     * Display a listing of the contracts.
     */
    public function index(Request $request)
    {
  
      // Get the authenticated user
      $user = Auth::user();

      // Check if the user's status is 'disactive'
      if ($user->status === 'disactive') {
          // Redirect to the disactive view if the user is disactive
          return view('disactive');
      }

      // If the user is active, proceed with the contracts fetching
      $contracts = DB::table('contracts')
          ->join('users', 'contracts.company_id', '=', 'users.company_id')
          ->where('users.id', '=', $user->id)
          ->select('contracts.*')
          ->get();

      // Fetch all variables
      $variables = VariableList::all();

      // Render the regular ContractList view if the user is active
      return view('ContractList', compact('contracts', 'variables'));
    }

  
    
 
    public function destroy($id)
    {
        $contract = Contract::find($id);
        
        if (!$contract) {
            return redirect()->back()->with('error', 'Contract not found.');
        }
        
        $contract->delete();
        
        return redirect()->back()->with('success', 'Contract deleted successfully.');
    }
 
    

    public function show()
    {
      
        $variables = VariableList::all();
        return view('ContractList', compact('variables'));
    }
    
    // public function savecontract(Request $request)
    // {
    //     // Validate the request data
    //     $validatedData = $request->validate([
    //         'contract_name' => 'required|string',
            
    //         'editor_content' => 'required|string',
    //     ]);
    
    //     // Create a new Contract instance
    //     $contract = new Contract();
    //     $contract->contract_name = $request->input('contract_name');
  
    //     $contract->editor_content = $request->input('editor_content');
    //     $contract->logged_in_user_name = auth()->user()->name; // Assuming you have authentication
    //     //$contract->last_update_name = auth()->user()->name; // Assuming you have authentication
    //     $contract->save(); // Save the contract data
    
    //     // You can return a response to the client if needed
    //     return response()->json(['message' => 'Contract saved successfully']);
    // }
    

    public function savecontract(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'contract_name' => 'required|string',
            
            'editor_content' => 'required|string',
        ]);
    
        // Create a new Contract instance
        $contract = new Contract();
        $contract->contract_name = $request->input('contract_name');
  
        $contract->editor_content = $request->input('editor_content');
        $contract->logged_in_user_name = auth()->user()->name; // Assuming you have authentication
        //$contract->last_update_name = auth()->user()->name; // Assuming you have authentication
        $contract->save(); // Save the contract data
    
        // You can return a response to the client if needed
        return response()->json(['message' => 'Contract saved successfully']);

     
    }

   // main upload method for photo without  size limitation 
    public function upload(Request $request)
    {
       if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;
            $request->file('upload')->move(public_path('media'), $fileName);
            $url = asset('media/' . $fileName);
            return response()->json(['fileName' => $fileName, 'uploaded'=> 1, 'url' => $url]);
        }
    }




   // upload photo no  more than 1M
    // public function upload(Request $request)
    // {
    //     // Define validation rules
    //     $validator = Validator::make($request->all(), [
    //        // 'upload' => 'required|image|mimes:jpeg,png|max:5000|dimensions:max_width=150,max_height=150',
    //        'upload' => 'required|image|mimes:jpeg,png|max:1024',
    
    //     ]);

    //     // Check if validation fails
    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()->first('upload')], 400);
    //     }
    //     // Process the uploaded image
    //     if ($request->hasFile('upload')) {
    //         $image = $request->file('upload');

    //         // Generate unique filename
    //         $fileName = 'image_' . time() . '.' . $image->getClientOriginalExtension();

    //         // Resize and save the image
    //         $image = Image::make($image)->fit(100, 100)->save(public_path('media/' . $fileName));

    //         // Get the URL of the resized image
    //         $url = asset('media/' . $fileName);

    //         return response()->json(['fileName' => $fileName, 'uploaded' => 1, 'url' => $url]);
    //     }
    // }

   // for update the contract 
    public function updatecontract(Request $request)
    {
        // Validation
        $request->validate([
            'id' => 'required|exists:contracts,id',
            'contract_name' => 'required|string',
            'editor_content' => 'required|string',
            // Add more validation rules as needed
        ]);
    
        // Find the contract by ID
        $contract = Contract::findOrFail($request->input('id'));
    
        // Update contract details
        $contract->update([
            'contract_name' => $request->input('contract_name'),
            'editor_content' => $request->input('editor_content'),
            // Include other fields if needed
        ]);
    
        // Return a JSON response indicating success
        return response()->json(['message' => 'Contract updated successfully'], 200);
    }
    

    // for edit 
    public function edit($id)
    {
        $contract = Contract::findOrFail($id);

        return view('contracts.edit-modal', compact('contract'));
    }

 

}


<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\VariableList;
use App\Models\contractvariablecheckbox; 

class VariableListController extends Controller
{
    
     public function index()
    {
        $variables = VariableList::all();
    
        // Encode VariableLabelValue as JSON string
        foreach ($variables as $variable) {
            if (is_array($variable->VariableLabelValue) || is_object($variable->VariableLabelValue)) {
                $variable->VariableLabelValue = json_encode($variable->VariableLabelValue);
            } else {
                // Handle cases where VariableLabelValue is null or not in expected format
                $variable->VariableLabelValue = json_encode([]);
            }
        }
    
        return view('variablelist', compact('variables'));
    }
    
    

    //to check how many time variable is checked

    public function countVariableIDs(Request $request)
    {
        // Get the VariableID from the request
        $variableID = $request->input('VariableID');

        // Count the total number of occurrences of the VariableID in the table
        $count = ContractVariableCheckbox::where('VariableID', $variableID)->count();

        // Count the number of different ContractIDs related to the VariableID in the table
        $countContract = ContractVariableCheckbox::where('VariableID', $variableID)
                                                ->distinct('ContractID')
                                                ->count('ContractID');

        // Return both counts as JSON response
        return response()->json([
            'count' => $count,
            'countContract' => $countContract
        ]);
    }

    // public function countVariableIDs(Request $request)
    // {
    //     // Get the VariableID from the request
    //     $variableID = $request->input('VariableID');

    //     // Count the number of occurrences of the VariableID in the table
    //     $count = ContractVariableCheckbox::where('VariableID', $variableID)->count();

    //     // Return the count as JSON response
    //     return response()->json(['count' => $count]);
    // }

    //to view all page
  
  /*  public function index()
    {
        $variables = VariableList::all();
        return view('variablelist', compact('variables'));
   
    }
    */
    
    

    
    public function deleteContract($id)
    {
        $variable = VariableList::findOrFail($id); // Find the variable by ID
        $variable->delete(); // Delete the variable
        return redirect()->back()->with('success', 'Variable deleted successfully'); // Redirect back with success message
    }

    public function destroy($id)
        {
            $variable = Variable::find($id);
            
            if (!$variable) {
                return redirect()->back()->with('error', 'Variable not found.');
            }
            
            $variable->delete();
            
            return redirect()->back()->with('success', 'Variable deleted successfully.');
        }
 
    
 //********************************************************** */


    public function updateVariable(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'VariableName' => 'required|string',
            'VariableType' => 'required|string',
            // Add validation for Description if necessary
            'inputFieldValues' => ['nullable', 'array'], // Validate that it's an array
            'inputFieldValues.*.id' => 'nullable|string', // Validate each element as an object with id
            'inputFieldValues.*.inputValue' => 'nullable|string', // Validate inputValue as nullable string
            'inputFieldValues.*.ckEditorContent' => 'nullable|string', // Validate ckEditorContent as nullable string
        ]);

        // Find the variable by ID
        $variable = VariableList::findOrFail($id);

        // Update variable details
        $variable->VariableName = $request->input('VariableName');
        $variable->VariableType = $request->input('VariableType');

        // Check if Description field is provided
        if ($request->has('Description')) {
            $variable->Description = $request->input('Description');
        }

        // Update VariableLabelValue based on VariableType
        if (in_array($variable->VariableType, ['Single Line Text', 'Multiple Line Text', 'Dates'])) {
            $variable->VariableLabelValue = null;
        } else {
            if ($request->has('inputFieldValues')) {
                $variable->VariableLabelValue = $request->input('inputFieldValues');
            }
        }

        // Save the updated variable
        $variable->save();

        // Return a response indicating success
        return response()->json(['success' => true, 'message' => 'Variable updated successfully']);
    }


 // previous working method 

    // public function updateVariable(Request $request, $id)
    // {
    //     // Validate the request data
    //     $request->validate([
    //         'VariableName' => 'required|string',
    //         'VariableType' => 'required|string',
    //         // Add validation for Description if necessary
    //         'inputFieldValues' => ['nullable', 'array'], // Validate that it's an array and not empty
    //         'inputFieldValues.*' => 'nullable|string', // Validate each element as nullable string
    //     ]);

    //     // Find the variable by ID
    //     $variable = VariableList::findOrFail($id);

    //     // Update variable details
    //     $variable->VariableName = $request->input('VariableName');
    //     $variable->VariableType = $request->input('VariableType');

    //     // Check if Description field is provided
    //     if ($request->has('Description')) {
    //         $variable->Description = $request->input('Description');
    //     }

    //     // Set VariableLabelValue based on VariableType
    //     if (in_array($variable->VariableType, ['Single Line Text', 'Multiple Line Text', 'Dates'])) {
    //         // Set to null for these types
    //         $variable->VariableLabelValue = null;
    //     } else {
    //         // Only set VariableLabelValue if inputFieldValues is provided
    //         if ($request->has('inputFieldValues')) {
    //             $variable->VariableLabelValue = $request->input('inputFieldValues');
    //         }
    //     }

    //     // Save the updated variable
    //     $variable->save();

    //     // Return a response indicating success
    //     return response()->json(['success' => true, 'message' => 'Variable updated successfully']);
    // }


//************************************************************************************************* */


public function checkVariableName(Request $request)
{
    $variableName = $request->get('VariableName');

    // Check if the variable name already exists
    $exists = VariableList::where('VariableName', $variableName)->exists();

    // Return the result as a JSON response
    return response()->json(['exists' => $exists]);
}



public function saveVariable(Request $request)
{
    // Validate the request data with conditional validation for inputFieldValues
    $validatedData = $request->validate([
        'VariableName' => 'required|string',
        'description' => 'required|string',
        'variableType' => 'required|string',
        // Only validate inputFieldValues as an array if the variableType is not one of the specified types
        'inputFieldValues' => [
            'nullable', 
            'array',
            function ($attribute, $value, $fail) use ($request) {
                if (in_array($request->variableType, ['Single Line Text', 'Multiple Line Text', 'Dates']) && !is_null($value)) {
                    $fail('The ' . $attribute . ' field must be null for the given variableType.');
                }
            }
        ],
        'inputFieldValues.*.id' => 'required_with:inputFieldValues|string',  
        'inputFieldValues.*.inputValue' => 'nullable|string',  
        'inputFieldValues.*.ckEditorContent' => 'nullable|string',  
    ]);

    // Create a new VariableList instance
    $variableList = new VariableList;

    // Assign values only if they exist in the validated data
    $variableList->VariableName = $validatedData['VariableName'];
    $variableList->description = $validatedData['description'];
    $variableList->variableType = $validatedData['variableType'];
    // Check if inputFieldValues is present and assign accordingly
    $variableList->VariableLabelValue = $validatedData['inputFieldValues'] ?? null; // This will be a JSON column, set to null if not present

    // Save the data to the database
    $variableList->save();

    // Optionally, you can return a response or redirect to another page
    return response()->json(['message' => 'Data saved successfully']);
}

    // previous working method 

    // public function saveVariable(Request $request)
    // {
    //     // Validate the request data
    //     $validatedData = $request->validate([
    //         'VariableName' => 'required',
    //         'description' => 'required',
    //         'variableType' => 'required',
    //         'inputFieldValues' => ['nullable', 'array'], // Validate that it's an array and not empty
    //         'inputFieldValues.*' => 'nullable|string', // Validate each element as nullable string
    //     ]);

    //     // Create a new VariableList instance
    //     $variableList = new VariableList;

    //     // Assign values only if they exist in the validated data
    //     $variableList->VariableName = $validatedData['VariableName'] ?? null;
    //     $variableList->description = $validatedData['description'] ?? null;
    //     $variableList->variableType = $validatedData['variableType'] ?? null;
    //     $variableList->VariableLabelValue = $validatedData['inputFieldValues'] ?? null;

    //     // Save the data to the database
    //     $variableList->save();

    //     // Optionally, you can return a response or redirect to another page
    //     return response()->json(['message' => 'Data saved successfully']);
    // }


    // to show data in variable button modal table
    
    public function fetchVariables()
    {
        $variables = VariableList::all();
        return response()->json($variables);
    }
        

}



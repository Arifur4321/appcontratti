<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//For the pricelist table
use App\Models\PriceList;

use Illuminate\Support\Facades\Auth;

class PriceListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
// createpricewithupdate

    public function createpricewithupdate()
    {

        // Create a new contract with only the ID incremented
        $priceLists = new PriceList();
        $priceLists->pricename = "Write your price name"; // Set the default value for contract_name

        $user = Auth::user();
        
        $priceLists->company_id = $user->company_id; // Set the company_id from the authenticated user

        $priceLists->save();

        // Get the ID of the newly created contract
        $priceListsid = $priceLists->id;

        // Redirect to the edit-contract-list page with the new contract ID
       // return redirect()->route('edit-contract-list', ['contractId' => $contractId]);
       echo '<script>window.location.href = "/edit-price/' . $priceListsid . '";</script>';
    
    }


    //  public function getpricedata()
    // {
    //     $priceLists = PriceList::all(); // Fetch all PriceList records from the database
    //     return view('Price-List', compact('priceLists'));
    // }

    public function getpricedata()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Fetch price lists where company_id matches the authenticated user's company_id
        $priceLists = PriceList::where('company_id', $user->company_id)->get();

        return view('Price-List', compact('priceLists'));
    }

    // save all data in database
    public function savePriceList(Request $request)
    {
        $priceList = new PriceList();
        // Assign received data to model properties
        $priceList->pricename = $request->pricename;
        $priceList->currency = $request->currency;
        $priceList->fixedvalue = $request->fixedValue;
        $priceList->dynamicminRange = $request->minRangeValue;
        $priceList->dynamicmaxRange = $request->maxRangeValue;
       // $priceList->selection = $request->selection;
        $priceList->enableVat = $request->vatCheckbox;
        $priceList->vatPercentage = $request->vatPercentage;
        $priceList->price = $request->includeOnPrice;
        $priceList->external = $request->external;
        $priceList->selectPriceType = $request->priceType;
        $priceList->singlePayment = $request->singlePayment;
        $priceList->multiplePayments= $request->multiplePayments;
        $priceList->paymentMinRange = $request->minPaymentRange;
        $priceList->paymentMaxRange = $request->maxPaymentRange;
        $priceList->paymentExampleText = $request->minPayment;
        $priceList->frequency=   $request->frequency;
        $priceList->EditableDates=   $request->EditableDates;
         
        // Save query the model to the database
        $priceList->save();
        // Return a response indicating success or failure
        return response()->json(['message' => 'Data saved successfully'], 200);
    }

    
    // for update and edit page
    public function editPrice($id)
    {
        $priceList = PriceList::findOrFail($id);
        return view('Edit-Price-List', compact('priceList'));
    }


    public function updatePrice(Request $request, $id)
    {
        $priceList = PriceList::findOrFail($id);

        if ($request->pricename !== $priceList->pricename) {
            $exists = PriceList::where('pricename', $request->pricename)
                ->where('company_id', $priceList->company_id)
                ->exists();
    
            if ($exists) {
                return response()->json(['error' => 'Price name already exists within the same company.'], 422);
            }
        }

        // Update the priceList with the new data
        $priceList->update([
            'pricename' => $request->pricename,
            'currency' => $request->currency,
            'fixedvalue' => $request->fixedvalue,
            'minrata'=>  $request->minrataValue,
            'dynamicminRange' => $request->dynamicminRange,
            'dynamicmaxRange' => $request->dynamicmaxRange,
            'enableVat' => $request->enableVat,
            'vatPercentage' => $request->vatPercentage,
            'price' => $request->includeOnPrice,
            'external' => $request->external,
            'selectPriceType' => $request->priceType,
            'singlePayment' => $request->singlePayment,
            'multiplePayments' => $request->multiplePayments,
            'paymentMinRange' => $request->paymentMinRange,
            'paymentMaxRange' => $request->paymentMaxRange,
            'paymentExampleText' => $request->paymentExampleText,
            'frequency' => $request->frequency,
            'EditableDates'  => $request->EditableDates,
        ]);

        return redirect()->route('get.data')->with('success', 'PriceList updated successfully');
    }


    // public function updatePrice(Request $request, $id)
    // {
    //     $priceList = PriceList::findOrFail($id);

    //     // Validate request data if necessary

    //     $priceList->update([
    //         'pricename' => $request->pricename,
    //         'currency' => $request->currency,
    //         'fixedvalue' => $request->fixedvalue,
    //         'dynamicminRange' => $request->dynamicminRange,
    //         'dynamicmaxRange' => $request->dynamicmaxRange,
    //         'enableVat' => $request->enableVat,
    //         'vatPercentage' => $request->vatPercentage,
    //         'price' => $request->includeOnPrice,
    //         'external' => $request->external,
    //         'selectPriceType' => $request->priceType,
    //         'singlePayment' => $request->singlePayment,
    //         'multiplePayments' => $request->multiplePayments,
    //         'paymentMinRange' => $request->paymentMinRange,
    //         'paymentMaxRange' => $request->paymentMaxRange,
    //         'paymentExampleText' => $request->paymentExampleText,
    //         'frequency' => $request->frequency,
    //         'EditableDates'  => $request-> EditableDates,
    //     ]);

    //     return redirect()->route('get.data')->with('success', 'PriceList updated successfully');
    // }

    public function destroy($id)
    {
        $priceList = PriceList::findOrFail($id);
        $priceList->delete();

        return response()->json(['message' => 'Price list deleted successfully']);
    }
 }

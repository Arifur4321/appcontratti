<?php

namespace App\Http\Controllers;

use App\Models\SalesListDraft;

use App\Models\Contract;
use App\Models\VariableList; 
use App\Models\Product;
use App\Models\HeaderAndFooter;
use App\Models\contractvariablecheckbox; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
//For the pricelist table
use App\Models\PriceList;
use App\Models\SalesDetails; 
use App\Models\ProductToSales;
use HelloSign\Client as HelloSignClient;

use HelloSign\Client;
use Dropbox\Sign\Api\SignatureRequestApi;
use Dropbox\Sign\ApiException;

use Dropbox\Sign\Configuration;
 
class ContractHistoryController extends Controller
{
    
    
    protected $signatureRequestApi;

    public function __construct()
    {   
        $config = Configuration::getDefaultConfiguration();
        $config->setUsername(env('HELLOSIGN_API_KEY'));
        $this->signatureRequestApi = new SignatureRequestApi($config);
    }

    public function getSignedPdfUrl($id)
    {
        $item = SalesListDraft::findOrFail($id);

        if ($item->status == 'signed') {
            try {
                $result = $this->signatureRequestApi->signatureRequestFilesAsFileUrl($item->envelope_id);
                $fileUrl = $result->getFileUrl();

                if ($fileUrl) {
                    return response()->json(['success' => true, 'file_url' => $fileUrl]);
                } else {
                    return response()->json(['success' => false, 'message' => 'Failed to retrieve the signed PDF URL.']);
                }
            } catch (ApiException $e) {
                $error = $e->getResponseObject();
                return response()->json(['success' => false, 'message' => 'Failed to retrieve the signed PDF URL: ' . print_r($error->getError(), true)]);
            }
        }

        return response()->json(['success' => false, 'message' => 'Item is not signed or could not fetch link']);
    }

    public function showAll()
    {
        // Fetch all rows from SalesListDraft table with related SalesDetails
        $salesListDraft = SalesListDraft::with('salesDetails')->get();

        // Return the view with the fetched data
        return view('Contract-History', compact('salesListDraft'));
    }  

    
    // for delete row in sales draft list table
    public function destroy($id)
    {
        $salesListDraft = SalesListDraft::find($id);

        if (!$salesListDraft) {
            return response()->json(['error' => 'Record not found'], 404);
        }

        // Delete the record
        $salesListDraft->delete();

        return response()->json(['message' => 'Record deleted successfully']);
    }
}
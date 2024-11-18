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
use Illuminate\Support\Facades\Storage;
use Dropbox\Sign\Configuration;
use Illuminate\Support\Facades\Mail;
 
use App\Models\User;
 
class ContractHistoryController extends Controller
{
    
    
    protected $signatureRequestApi;

    public function __construct()
    {   
        $config = Configuration::getDefaultConfiguration();
        $config->setUsername(env('HELLOSIGN_API_KEY'));
        $this->signatureRequestApi = new SignatureRequestApi($config);
        
        $this->middleware('auth');
    }
    


    // 
    public function uploadPdf(Request $request)
    {
        $request->validate([
            'pdfName' => 'required|string',
            'contractName' => 'required|string',
            'clientEmail' => 'required|email',
            'pdfFile' => 'required|file|mimes:pdf|max:10240', // 10 MB limit
        ]);

            // Retrieve logged-in user
        $user = Auth::user();

        // Get company_id from the user
        $companyId = $user->company_id;
    
        $pdfContent = file_get_contents($request->file('pdfFile')->getRealPath());
    
        $salesDraft = new SalesListDraft();
        $salesDraft->selected_pdf_name = $request->pdfName;
        $salesDraft->contract_name = $request->contractName;
        $salesDraft->recipient_email = $request->clientEmail;
        $salesDraft->pdf_content = $pdfContent;
        $salesDraft->status = 'signed';
        $salesDraft->company_id = $companyId;
        $salesDraft->save();
    
        return response()->json(['success' => true, 'message' => 'PDF uploaded successfully!']);
    }
    

    public function getSignedPdfUrl($id)
    {
        // Log the function call
        Log::info('getSignedPdfUrl called with ID: ' . $id);

        // Retrieve the record from SalesListDraft using the provided ID
        $item = SalesListDraft::findOrFail($id);

        // Check if the envelope_id is exactly 40 characters long (HelloSign signature)
        if (strlen($item->envelope_id) === 40 && $item->status === 'signed') {
            try {
                Log::info('Attempting to fetch signed PDF via HelloSign API for envelope ID: ' . $item->envelope_id);

                // Fetch the signed PDF URL from HelloSign API
                $result = $this->signatureRequestApi->signatureRequestFilesAsFileUrl($item->envelope_id);
                $fileUrl = $result->getFileUrl();

                if ($fileUrl) {
                    Log::info('Successfully retrieved signed PDF URL from HelloSign: ' . $fileUrl);

                    // Redirect to the HelloSign URL to download the PDF directly
                    return redirect($fileUrl);
                } else {
                    Log::error('Failed to retrieve signed PDF URL from HelloSign API.');
                }
            } catch (ApiException $e) {
                $error = $e->getResponseObject();
                Log::error('HelloSign API Error:', ['error' => $error]);
            }
        }

        // If HelloSign retrieval fails or is not valid, check the database for PDF content
        if ($item->pdf_content) {
            Log::info('PDF found in the database for ID: ' . $id);

            // Return a response to download the file from the BLOB (pdf_content column)
            $pdfContent = $item->pdf_content;
          //  $fileName = $item->selected_pdf_name ?? 'signed_contract_' . $id . '.pdf';
            $fileName = ($item->selected_pdf_name ?? 'signed_contract_' . $id) . '.pdf';

            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"')
                ->header('Content-Length', strlen($pdfContent));  // Set content length header
        }

        // If neither HelloSign nor database retrieval succeeds, respond with an error message
        return response()->json(['success' => false, 'message' => 'Signed PDF file not found. Please contact support.']);
    }



    // public function showAll()
    // {
    //     // Fetch all rows from SalesListDraft table with related SalesDetails
    //   //  $salesListDraft = SalesListDraft::with('salesDetails')->get();

    //   $user = Auth::user();
        
    //   // Fetch products where company_id matches the authenticated user's company_id
    //   $salesListDraft = SalesListDraft::where('company_id', $user->company_id)->get();

    //   // Return the view with the fetched data
    //     return view('Contract-History', compact('salesListDraft'));
    // }  

    public function showAll()
        {
            $user = Auth::user();

            // Fetch the sales draft data based on the user's company_id
            $salesListDraft = SalesListDraft::where('company_id', $user->company_id)->with('salesDetails')->get();

            // Pass both salesListDraft and user to the view
            return view('Contract-History', compact('salesListDraft', 'user'));
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


/*
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
*/
  
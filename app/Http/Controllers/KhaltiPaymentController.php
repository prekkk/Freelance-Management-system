<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KhaltiPaymentController extends Controller
{
    public function verifyJob(Request $request)
    {
        // Retrieve the data sent by Khalti Checkout
        $payload = $request->all();

        // Perform your verification logic here
        // For example, you can check if the payment is successful and process the order accordingly
        
        // Example of processing the payload data
        if ($payload['idx']) {
            // Payment successful, process the order
            // You can perform additional verification and processing here
            return response()->json(['status' => 'success', 'message' => 'Payment verified successfully']);
        } else {
            // Payment failed or verification unsuccessful
            return response()->json(['status' => 'error', 'message' => 'Payment verification failed']);
        }
    }
}

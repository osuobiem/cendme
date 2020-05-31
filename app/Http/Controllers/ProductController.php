<?php

namespace App\Http\Controllers;

use App\Product;
use App\Vendor;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Create product
     * @return json
     */
    public function create(Request $request, $vendor_id)
    {
        // Get validation rules
        $validate = $this->create_rules($request);

        // Run validation
        if ($validate->fails()) {
            return response()->json([
                "success" => false,
                "message" => $validate->errors()
            ], 400);
        }

        $vendor_id = base64_decode($vendor_id);

        if (Vendor::find($vendor_id)) {
            $request['vendor_id'] = $vendor_id;

            // Store product data
            $store = $this->cstore($request);
            $status = $store['status'];
            unset($store['status']);
            return response()->json($store, $status);
        } else {
            return response()->json([
                "success" => false,
                "message" => "No vendor exists with this ID"
            ], 404);
        }
    }

    /**
     * Process product creation
     * @return array Result of saved product data
     */
    public function cstore(Request $request)
    {
        // New vendor object
        $product = new Product();

        // Assign vendor object properties
        $product->title = $request['title'];
        $product->details = $request['details'];
        $product->quantity = $request['quantity'];
        $product->vendor_id = $request['vendor_id'];

        // Check for images
        if ($request['photo']) {
            $photo = $request['photo'];
            $stored = Storage::disk('local')->put('/products', $photo);
            $product->photo = basename($stored);
        }

        // Try vendor save or catch error if any
        try {
            $product->save();

            return ['success' => true, 'status' => 200, 'message' => 'Creation Successful'];
        } catch (\Throwable $th) {
            Log::error($th);
            return ['success' => false, 'status' => 500, 'message' => 'Internal Server Error'];
        }
    }

    /**
     * Vendor Creation Validation Rules
     * @return object The validator object
     */
    private function create_rules(Request $request)
    {
        // Make and return validation rules
        return Validator::make($request->all(), [
            'title' => 'required',
            'details' => 'required',
            'quantity' => 'required|numeric',
            'photo' => 'image|max:3072'
        ]);
    }
}

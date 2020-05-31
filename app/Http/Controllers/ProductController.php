<?php

namespace App\Http\Controllers;

use App\Product;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // CREATE PRODUCT
    /**
     * Create product
     * @return json
     */
    public function create(Request $request, $vendor_id)
    {
        // Get validation rules
        $validate = $this->rules($request);

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
                "message" => "No product exists with this ID"
            ], 404);
        }
    }

    /**
     * Process product creation
     * @return array Result of saved product data
     */
    public function cstore(Request $request)
    {
        // New product object
        $product = new Product();

        // Assign product object properties
        $product->title = $request['title'];
        $product->details = $request['details'];
        $product->quantity = $request['quantity'];
        $product->vendor_id = $request['vendor_id'];

        $stored = false;

        // Check for images
        if ($request['photo']) {
            $photo = $request['photo'];
            $stored = Storage::put('/products', $photo);
            $product->photo = $stored ? basename($stored) : 'placeholder.png';
        }

        // Try product save or catch error if any
        try {
            $product->save();

            return ['success' => true, 'status' => 200, 'message' => 'Creation Successful'];
        } catch (\Throwable $th) {
            Log::error($th);

            // Delete uploaded file if there's an error
            $stored && $product->photo != 'placeholder.png' ? Storage::delete('/products/' . $product->photo) : '';

            return ['success' => false, 'status' => 500, 'message' => 'Internal Server Error'];
        }
    }

    /**
     * Vendor Creation Validation Rules
     * @return object The validator object
     */
    private function rules(Request $request)
    {
        // Make and return validation rules
        return Validator::make($request->all(), [
            'title' => 'required',
            'details' => 'required',
            'quantity' => 'required|numeric',
            'photo' => 'image|max:3072'
        ]);
    }
    // -------------


    // UPDATE PRODUCT
    /**
     * Update product data
     * @param int $id Product id to update with
     * @return json
     */
    public function update(Request $request, $id)
    {
        // Get validation rules
        $validate = $this->rules($request);

        // Run validation
        if ($validate->fails()) {
            return response()->json([
                "success" => false,
                "message" => $validate->errors()
            ], 400);
        }

        // Store product data
        $store = $this->ustore($request, $id);
        $status = $store['status'];
        unset($store['status']);
        return response()->json($store, $status);
    }

    /**
     * Process product data update
     * @param int $id Product id to update with
     * @return array Update status
     */
    public function ustore(Request $request, $id)
    {
        // Decode product id
        $id = base64_decode($id);

        // Find product with supplied id
        $product = Product::find($id);

        if ($product) {

            // Assign product object properties
            $product->title = $request['title'];
            $product->details = $request['details'];
            $product->quantity = $request['quantity'];

            $old_photo = $product->photo;
            $stored = false;

            // Check for images
            if ($request['photo']) {
                $photo = $request['photo'];
                $stored = Storage::put('/products', $photo);

                $product->photo = $stored ? basename($stored) : '';
            }

            // Try product save or catch error if any
            try {
                $product->save();

                // Delete previous photo
                $stored && $old_photo != 'placeholder.png' ? Storage::delete('/products/' . $old_photo) : '';

                return ['success' => true, 'status' => 200, 'message' => 'Update Successful'];
            } catch (\Throwable $th) {
                Log::error($th);

                // Delete uploaded file if there's an error
                $stored && $product->photo != 'placeholder.png' ? Storage::delete('/products/' . $product->photo) : '';

                return ['success' => false, 'status' => 500, 'message' => 'Internal Server Error'];
            }
        } else {
            return ['success' => false, 'status' => 404, 'message' => 'No product exists with this ID'];
        }
    }
    // -------------


    // DELETE PRODUCT
    /**
     * Delete Product
     * @param int $id ID of product to be deleted
     * @return object Delete status
     */
    public function delete($id)
    {
        // Decode product id
        $id = base64_decode($id);

        // Find product with supplied id
        $product = Product::find($id);

        if ($product) {

            // Try product delete or catch error if any
            try {
                $product->delete();

                // Delete product photo
                $product->photo != 'placeholder.png' ? Storage::delete('/products/' . $product->photo) : '';

                return response()->json([
                    'success' => true,
                    'message' => 'Product deleted'
                ]);
            } catch (\Throwable $th) {
                Log::error($th);

                // Return failure response
                return response()->json([
                    'success' => false,
                    'message' => 'Internal Server Error'
                ]);
            }
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'No product exists with this ID'
                ],
                404
            );
        }
    }
    // --------------
}

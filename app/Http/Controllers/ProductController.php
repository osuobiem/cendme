<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use App\SubCategory;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    public function create(Request $request)
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
        $store = $this->cstore($request);
        $status = $store['status'];
        unset($store['status']);
        return response()->json($store, $status);
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
        $product->details = $request['details'] ? $request['details'] : '';
        $product->quantity = $request['quantity'];
        $product->price = $request['price'];
        $product->subcategory_id = $request['subcategory'];
        $product->vendor_id = Auth::user()->id;

        $stored = false;

        // Check for images
        if ($request['photo']) {
            $photo = $request['photo'];
            $stored = Storage::put('/public/products', $photo);
            $product->photo = $stored ? basename($stored) : 'placeholder.png';
        }

        // Try product save or catch error if any
        try {
            $product->save();

            return ['success' => true, 'status' => 200, 'message' => 'Creation Successful'];
        } catch (\Throwable $th) {
            Log::error($th);

            // Delete uploaded file if there's an error
            $stored && $product->photo != 'placeholder.png' ? Storage::delete('/public/products/' . $product->photo) : '';

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
            'quantity' => 'required|numeric',
            'price' => 'required|numeric',
            'photo' => 'image|max:5120',
            'subcategory' => 'required|numeric|exists:subcategories,id'
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
        // Find product with supplied id
        $product = Product::find($id);

        if ($product) {

            // Assign product object properties
            $product->title = $request['title'];
            $product->details = $request['details'] ? $request['details'] : '';
            $product->quantity = $request['quantity'];
            $product->price = $request['price'];
            $product->subcategory_id = $request['subcategory'];

            $old_photo = $product->photo;
            $stored = false;

            // Check for images
            if ($request['photo']) {
                $photo = $request['photo'];
                $stored = Storage::put('/public/products', $photo);

                $product->photo = $stored ? basename($stored) : '';
            }

            // Try product save or catch error if any
            try {
                $product->save();

                // Delete previous photo
                $stored && $old_photo != 'placeholder.png' ? Storage::delete('/public/products/' . $old_photo) : '';

                return ['success' => true, 'status' => 200, 'message' => 'Update Successful'];
            } catch (\Throwable $th) {
                Log::error($th);

                // Delete uploaded file if there's an error
                $stored && $product->photo != 'placeholder.png' ? Storage::delete('/public/products/' . $product->photo) : '';

                return ['success' => false, 'status' => 500, 'message' => 'Internal Server Error'];
            }
        } else {
            return ['success' => false, 'status' => 404, 'message' => 'Product not found'];
        }
    }

    /**
     * Change product status
     * @param int $id Product ID
     * @return object
     */
    public function status($id)
    {
        // Decode product id
        $id = base64_decode($id);

        // Find product with supplied id
        $product = Product::find($id);

        if ($product) {
            // Change status
            $product->status = $product->status ? 0 : 1;

            // Try product delete or catch error if any
            try {
                $product->save();

                $status = $product->status ? 'Enabled' : 'Disabled';

                return response()->json([
                    'success' => true,
                    'message' => 'Product ' . $status
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
                    'message' => 'Product not found'
                ],
                404
            );
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
                $product->forceDelete();

                // Delete product photo
                $product->photo != 'placeholder.png' ? Storage::delete('/public/products/' . $product->photo) : '';

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
                    'message' => 'Product not found'
                ],
                404
            );
        }
    }
    // --------------

    // FETCH PRODUCT
    /**
     * Get all products
     * @return html
     */
    public function get()
    {
        // Extract vendor ID
        $vendor_id = Auth::user()->id;

        // Fetch products
        $products = Product::where('vendor_id', $vendor_id)->orderBy('created_at', 'desc')->get();

        // Return view
        return view('vendor.product.list', ['products' => $products]);
    }

    /**
     * Get update modals
     * @return html
     */
    public function get_update_modals()
    {
        // Extract vendor ID
        $vendor_id = Auth::user()->id;

        // Fetch products
        $products = Product::where('vendor_id', $vendor_id)->get();

        // Fetch categories
        $categories = Category::get();

        // Fetch subcategories
        $subcategories = SubCategory::orderBy('name')->get();

        $data = [
            'products' => $products,
            'categories' => $categories,
            'subcategories' => $subcategories
        ];

        // Return view
        return view('vendor.product.update', $data);
    }
    // -------------
}

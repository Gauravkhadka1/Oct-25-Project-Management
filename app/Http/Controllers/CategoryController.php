<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subcategory;
use App\Models\AdditionalSubcategory;


class CategoryController extends Controller
{
    // Fetch subcategories based on selected category
    public function getSubcategories($categoryId)
    {
        $subcategories = Subcategory::where('category_id', $categoryId)->get();
        return response()->json($subcategories);
    }

    // Fetch additional subcategories based on selected subcategory
    public function getAdditionalSubcategories($subcategoryId)
    {
        $additionalSubcategories = AdditionalSubcategory::where('subcategory_id', $subcategoryId)->get();
        return response()->json($additionalSubcategories);
    }
}

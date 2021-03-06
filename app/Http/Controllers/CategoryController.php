<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App\Models\Categories;
use App\Http\Requests\StoreCategoryRequest;
use Validator;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $category = Categories::paginate(5);
        return view('admin.pages.category.list',compact("category"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.category.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        //
        Categories::create(
            [
                'name' => $request ->name,
                'slug' => utf8tourl($request ->name),
                'status' => $request ->status,
            ]);
        return redirect()->route('category.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Categories::find($id);
        return response()->json($category,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $validator = Validator::make($request ->all(),[
            'name' => 'required|min:2|max:255'
        ],[
            'required' => 'T??n danh m???c s???n ph???m kh??ng ???????c ????? tr???ng',
            'min' => 'T??n danh m???c s???n ph???m ph???i t??? 2 ?????n 255 k?? t???',
            'max' => 'T??n danh m???c s???n ph???m ph???i t??? 2 ?????n 255 k?? t???',
        ]); 
        if($validator -> fails()){
            return response()->json(['error' => 'true','message' => $validator ->errors()],200);
        }
        $category = Categories::find($id);
        $category -> update([
            'name' => $request ->name,
            'slug' => utf8tourl($request ->name),
            'status' => $request ->status,
        ]);
        return response()->json(['error' => 'false','message' => 'Update th??nh c??ng']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $Categories = Categories::find($id);
        $Categories ->delete();
        return response()->json(['message' => 'X??a d??? li???u th??nh c??ng']);
    }
}

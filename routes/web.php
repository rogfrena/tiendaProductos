<?php


use Illuminate\Support\Facades\Route;
//con esta clase se captura todo lo que se envia en el formulario
use Illuminate\Http\Request;
use App\Product;
use Illuminate\Support\Facades\Auth;

//en la ruta middleware vamos autenticar a los usuarios para q puedan editar los productos
Route::middleware('auth')->group(function(){

Route::get('products', function(){
    $products = Product::orderBy('created_at', 'desc')->get();//llama a todos los productos de la BD a mostrar
    return view('products.index', compact('products'));//con compact se pasa como parametros 
    //el listado de all que llamos a todos los productos de la BD
     
})->name('products.index');

Route::get('products/create', function(){
    return view('products.create');
})->name('products.create');

Route::post('products', function(Request $request){
    $request->all();
    $newProduct = new Product;
    $newProduct->description = $request->input('description');
    $newProduct->price       = $request->input('price');
    $newProduct->save();
    return redirect()->route('products.index')->with('info', 'Producto agregado exitosamente');
})->name('products.store');

Route::delete('products/{id}', function($id){
    //aqui va a buscar el producto que se desea eliminar, sino lo encuentra lanza un error 404
    $product = Product::findOrFail($id); 
    $product->delete();
    return redirect()->route('products.index')->with('info', 'Producto eliminado exitosamente');
})->name('products.destroy');

Route::get('products/{id}/edit', function($id){
    //igual que en delete
    $product = Product::findOrFail($id);
    return view('products.edit', compact('product'));
})->name('products.edit');

Route::put('products/{id}', function(Request $request, $id){
    //return $request->all();  es para probar el json de la base de datos que muestre los datos del campo seleccionado
    $product = Product::findOrFail($id);
    //return $product; probamos q realmente muestre los datos del campo seleccionado por json
    $product->description = $request->input('description');
    $product->price = $request->input('price');
    $product->save();
    return redirect()->route('products.index')->with('info', 'Producto Actualizado Exitosamente');
})->name('products.update');

});

Auth::routes();


//Route::get('/home', 'HomeController@index')->name('home');

<?php
namespace App\Http\Controllers;
use App\Models\Proveedor;
use App\Models\Persona;
use Illuminate\Http\Request;
class ProveedorController extends Controller
{
    public function index() { return view('proveedores.index',['proveedores'=>Proveedor::with('persona')->get()]); }
    public function create() { return view('proveedores.create'); }
    public function edit($id) { return view('proveedores.edit',['proveedor'=>Proveedor::with('persona')->findOrFail($id)]); }
    public function store(Request $request)
    {
        $data=$this->datos($request); $persona=Persona::create($data['persona']); unset($data['persona']);
        Proveedor::create($data+['id_persona'=>$persona->id_persona]);
        return redirect()->route('panel')->with('success','Proveedor registrado.');
    }
    public function update(Request $request,$id)
    {
        $data=$this->datos($request); $proveedor=Proveedor::lockForUpdate()->findOrFail($id);
        $persona=Persona::create($data['persona']); unset($data['persona']);
        $proveedor->update($data+['id_persona'=>$persona->id_persona]);
        return redirect()->route('panel')->with('success','Proveedor actualizado.');
    }
    private function datos(Request $request): array
    {
        return $request->validate(['id_persona'=>'prohibited','correo'=>'nullable|email|max:150','rfc'=>'nullable|string|max:13','direccion'=>'nullable|string|max:255','persona'=>'required|array:nombre,apellidos,telefono','persona.nombre'=>'required|string|max:100','persona.apellidos'=>'required|string|max:200','persona.telefono'=>'required|string|max:20']);
    }
    public function destroy($id) { abort(403); }
}

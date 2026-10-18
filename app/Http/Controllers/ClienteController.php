<?php
namespace App\Http\Controllers;
use App\Models\Cliente;
use App\Models\Persona;
use Illuminate\Http\Request;
class ClienteController extends Controller
{
    public function index() { return view('clientes.index',['clientes'=>Cliente::with('persona')->get()]); }
    public function create() { return view('clientes.create'); }
    public function edit($id) { return view('clientes.edit',['cliente'=>Cliente::with('persona')->findOrFail($id)]); }
    public function store(Request $request)
    {
        $data=$this->datos($request);
        $persona=Persona::create($data['persona']); unset($data['persona']);
        Cliente::create($data+['id_persona'=>$persona->id_persona]);
        return redirect()->route('panel')->with('success','Cliente registrado.');
    }
    public function update(Request $request,$id)
    {
        $data=$this->datos($request); $cliente=Cliente::lockForUpdate()->findOrFail($id);
        // Copy on write: editing a customer never changes the supplier's shared person.
        $persona=Persona::create($data['persona']); unset($data['persona']);
        $cliente->update($data+['id_persona'=>$persona->id_persona]);
        return redirect()->route('panel')->with('success','Cliente actualizado.');
    }
    private function datos(Request $request): array
    {
        return $request->validate(['id_persona'=>'prohibited','correo'=>'nullable|email|max:150','persona'=>'required|array:nombre,apellidos,telefono','persona.nombre'=>'required|string|max:100','persona.apellidos'=>'required|string|max:200','persona.telefono'=>'required|string|max:20']);
    }
    public function destroy($id) { abort(403); }
}

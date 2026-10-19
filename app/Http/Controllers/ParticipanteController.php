<?php
namespace App\Http\Controllers;
use App\Models\Participante; use Illuminate\Http\Request; use Illuminate\Validation\Rule;
class ParticipanteController extends Controller {
 public function index(){ $participantes=Participante::orderBy('apellido')->orderBy('nombre')->paginate(10); return view('participantes.index',compact('participantes')); }
 public function create(){ return view('participantes.create'); }
 public function store(Request $r){ $d=$r->validate(['nombre'=>'required|string|max:100','apellido'=>'required|string|max:100','ci'=>'required|string|max:30|unique:participantes,ci','email'=>'required|email|max:255|unique:participantes,email','telefono'=>'nullable|string|max:30']); Participante::create($d); return redirect()->route('participantes.index')->with('success','Participante registrado.'); }
 public function edit(Participante $participante){ return view('participantes.edit',compact('participante')); }
 public function update(Request $r, Participante $participante){ $d=$r->validate(['nombre'=>'required|string|max:100','apellido'=>'required|string|max:100','ci'=>['required','string','max:30',Rule::unique('participantes','ci')->ignore($participante->id)],'email'=>['required','email',Rule::unique('participantes','email')->ignore($participante->id)],'telefono'=>'nullable|string|max:30']); $participante->update($d); return redirect()->route('participantes.index')->with('success','Participante actualizado.'); }
 public function show(Participante $participante){ $participante->load('inscripciones.evento'); return view('participantes.show',compact('participante')); }
 public function destroy(Participante $participante){ $participante->delete(); return back()->with('success','Participante eliminado.'); }
}

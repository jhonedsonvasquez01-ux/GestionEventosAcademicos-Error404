<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:150',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'lugar' => 'required|string|max:200',
            'costo_inscripcion' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del evento es obligatorio.',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_fin.after_or_equal' => 'La fecha final no puede ser anterior a la fecha inicial.',
            'lugar.required' => 'El lugar del evento es obligatorio.',
            'costo_inscripcion.required' => 'El costo de inscripción es obligatorio.',
            'costo_inscripcion.numeric' => 'El costo debe ser un número.',
            'costo_inscripcion.min' => 'El costo no puede ser negativo.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActividadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'evento_id' => 'required|exists:eventos,id',
            'nombre' => 'required|string|max:150',
            'tipo' => 'required|in:Conferencia,Taller,Panel',
            'fecha' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'sala' => 'required|string|max:100',
            'cupo' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'evento_id.exists' => 'El evento seleccionado no existe.',
            'tipo.in' => 'Selecciona un tipo de actividad válido.',
            'hora_fin.after' => 'La hora de finalización debe ser posterior a la hora de inicio.',
            'cupo.min' => 'La capacidad debe ser como mínimo de una persona.',
        ];
    }
}


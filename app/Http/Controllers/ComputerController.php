<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ComputerController extends Controller
{
    private function urlApi(): string
    {
        return rtrim(config('services.api.url', env('URL_SERVER_API')), '/');
    }

    private function fetchDataFromApi($url)
    {
        $response = Http::acceptJson()->timeout(10)->get($url);

        if ($response->failed()) {
            return null;
        }

        return $response->json();
    }

    private function erroresDeApi($response): array
    {
        $errors = $response->json('errors');

        if (is_array($errors) && $errors !== []) {
            return $errors;
        }

        return ['number' => $response->json('message') ?? 'No se pudo guardar el computador.'];
    }

    public function index()
    {
        $computers = $this->fetchDataFromApi($this->urlApi().'/computers');

        if (! is_array($computers)) {
            $computers = [];
            session()->now('error', 'No se pudo conectar con la API de adminSena.');
        }

        return view('computers.index', compact('computers'));
    }

    public function show($id)
    {
        $computer = $this->fetchDataFromApi($this->urlApi().'/computers/'.$id);

        if (! is_array($computer)) {
            return redirect()
                ->route('computers.index')
                ->with('error', 'No se encontro el computador .');
        }

        return view('computers.show', compact('computer'));
    }

    public function create()
    {
        return view('computers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'number' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'image' => 'nullable|image|max:5120',
        ]);

        unset($validated['image']);

        $peticion = Http::acceptJson()->timeout(15);

        if ($request->hasFile('image')) {
            $archivo = $request->file('image');

            $peticion = $peticion->attach(
                'image',
                file_get_contents($archivo->getRealPath()),
                $archivo->getClientOriginalName()
            );
        }

        $response = $peticion->post($this->urlApi().'/computers', $validated);

        if ($response->failed()) {
            return back()->withInput()->withErrors($this->erroresDeApi($response));
        }

        return redirect()
            ->route('computers.index')
            ->with('success', 'Computador creado correctamente.');
    }

    public function edit($id)
    {
        $computer = $this->fetchDataFromApi($this->urlApi().'/computers/'.$id);

        if (! is_array($computer)) {
            return redirect()
                ->route('computers.index')
                ->with('error', 'No se encontró el computador solicitado.');
        }

        return view('computers.edit', compact('computer'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'number' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'image' => 'nullable|image|max:5120',
        ]);

        unset($validated['image']);

        $peticion = Http::acceptJson()->timeout(15);

        if ($request->hasFile('image')) {
            $archivo = $request->file('image');

            $validated['_method'] = 'PUT';

            $response = $peticion
                ->attach('image', file_get_contents($archivo->getRealPath()), $archivo->getClientOriginalName())
                ->post($this->urlApi().'/computers/'.$id, $validated);
        } else {
            $response = $peticion->put($this->urlApi().'/computers/'.$id, $validated);
        }

        if ($response->failed()) {
            return back()->withInput()->withErrors($this->erroresDeApi($response));
        }

        return redirect()
            ->route('computers.index')
            ->with('success', 'Computador actualizado correctamente.');
    }

    public function destroy($id)
    {
        $response = Http::acceptJson()->timeout(10)->delete($this->urlApi().'/computers/'.$id);

        if ($response->failed()) {
            return redirect()
                ->route('computers.index')
                ->with('error', $response->json('message') ?? 'No se pudo eliminar el computador.');
        }

        return redirect()
            ->route('computers.index')
            ->with('success', 'Computador eliminado correctamente.');
    }
}

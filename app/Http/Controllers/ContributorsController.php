<?php

namespace Contabilizate\Http\Controllers;

use Contabilizate\Certificado;
use Contabilizate\Contributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContributorsController extends Controller
{
    private $certificado;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->certificado = new Certificado();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contribuyentes= Contributor::all();
        $contribuyentes->each(function($contribuyentes){
            $contribuyentes->regimen_description = $contribuyentes->regimen->description;
            $date = date_create($contribuyentes->created_at);
            $contribuyentes->alta = $date->format('d/m/Y'); 
        });
        return response()->json($contribuyentes->toArray());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $contribuyente = new Contributor($request->only(['name', 'rfc', 'regimen_id', 'pass_key']));
        if ($request->hasFile('cer') && $request->hasFile('key')) {
            $fileCer = $request->file('cer');
            $fileKey = $request->file('key');
            $nombreCer = $fileCer->getClientOriginalName();
            $nombreKey = $fileKey->getClientOriginalName();
            //Guardo archivos en storage
            $this->storeFile($request->rfc, $fileCer, $nombreCer);
            $this->storeFile($request->rfc, $fileKey, $nombreKey);
            //Rutas de archivos cer y key
            $pathCer = "storage/".$request->rfc."/".$nombreCer;
            $pathKey = "storage/".$request->rfc."/".$nombreCer;
            //Archivo cer.pem
            $pemFile = $this->certificado->generaCerPem($pathCer);
            //Se obtiene la fecha de vigencia
            if ($pemFile['result']) {
                $pathCerPem = "storage/".$request->rfc."/".$nombreCer.".pem";
                $resultadoVigencia = $this->certificado->getFechaVigencia($pathCerPem);
                $resultadoInicio = $this->certificado->getFechaInicio($pathCerPem);
                $serial = $this->certificado->getSerialCert($pathCerPem);
                if ($resultadoVigencia['result'] && $resultadoInicio['result'] && $serial['result']) {
                    $contribuyente->end_date = date_create($resultadoVigencia['fecha']);
                    $contribuyente->start_date = date_create($resultadoInicio['fecha']);
                    $contribuyente->num_serie = $serial['serial'];
                    $contribuyente->save();
                    $contribuyente->id;
                } else {
                    
                }   
            }
            
            if ($request->has('pass_key')) {
                $password =  $request->pass_key; 
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contribuyente = Contributor::findOrFail($id);
        return response()->json($contribuyente->toArray());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contribuyente = Contributor::findOrFail($id);
        $contribuyente->delete();
        $respuesta = ['mensaje' => 'Usuario eliminado correctamente'];
        return response()->json($respuesta);
    }

    public function storeFile($id, $file, $name) {
        return Storage::putFileAs(
                   "public/".$id,
                   $file,
                   $name
               );
    }
}

<?php

namespace Contabilizate\Http\Controllers;

use Contabilizate\Certificado;
use Contabilizate\Contributor;
use Contabilizate\FileCer;
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
        $mensaje = "";
        try {
            $contribuyentes= Contributor::all();
            $contribuyentes->each(function($contribuyentes){
                $contribuyentes->regimen_description = $contribuyentes->regimen->description;
                $date = date_create($contribuyentes->created_at);
                $contribuyentes->alta = $date->format('d/m/Y');
                $dateIn = date_create($contribuyentes->start_date);
                $contribuyentes->inicio = $contribuyentes->start_date ?  $dateIn->format('d/m/Y'): '-';
                $dateVig = date_create($contribuyentes->end_date);
                $contribuyentes->vigencia = $contribuyentes->end_date ? $dateVig->format('d/m/Y'): '-'; 
                $contribuyentes->num_serie = $contribuyentes->num_serie ? $contribuyentes->num_serie: '-'; 
            });    
            return response()->json($contribuyentes->toArray());
        } catch (\Exception $e) {
            $mensaje = "Ocurrio un error al consultar los contribuyentes.";
            $mensaje = strpos($mensaje, "éxito") ? [$mensaje, "alert-success"]: [$mensaje, "alert-danger"];
            return response()->redirect()->route('home');
        }        
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
        $mensaje= "";
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
            $pathKey = "storage/".$request->rfc."/".$nombreKey;
            //Genera CerPem File
            $pemFile = $this->certificado->generaCerPem($pathCer);
            $pathCerPem = "storage/".$request->rfc."/".$nombreCer.".pem";
                
            //Genera KeyPem File
            if ($request->has('pass_key')) {
                $keyPemFile = $this->certificado->generaKeyPem($pathKey, $request->pass_key);
                $pathKeyPem = "storage/".$request->rfc."/".$nombreKey.".pem";
                
                //Se valida que los archivos correspondan al mismo certificado
                $certificadosPareja = $this->certificado->pareja($pathCerPem,$pathKeyPem );
            }
            
            
            //Se obtiene la fecha de vigencia
            if ($pemFile['result']) {
                $certificadoValido = $this->certificado->validarCertificado($pathCerPem);
                $resultadoVigencia = $this->certificado->getFechaVigencia($pathCerPem);
                $resultadoInicio = $this->certificado->getFechaInicio($pathCerPem);
                $serial = $this->certificado->getSerialCert($pathCerPem);
                if ($resultadoVigencia['result'] && $resultadoInicio['result'] && $serial['result']) {
                    $contribuyente->end_date = date_create($resultadoVigencia['fecha']);
                    $contribuyente->start_date = date_create($resultadoInicio['fecha']);
                    $contribuyente->num_serie = $serial['serial'];
                    try {
                        $contribuyente->save();

                        $cer = new FileCer();
                        $cer->name = $nombreCer;
                        $cer->contributor_id = $contribuyente->id;
                        $cer->save();

                        $key = new FileCer();
                        $key->name = $nombreKey;
                        $key->contributor_id = $contribuyente->id;
                        $key->save();

                        $pem = new FileCer();
                        $pem->name = $nombreCer.".pem";
                        $pem->contributor_id = $contribuyente->id;
                        $pem->save();   
                        $mensaje = "Contribuyente registrado con éxito.";  
                    } catch (\Exception $e) {
                        $mensaje = "Error al procesar los archivos.";
                    }
                } else {
                    try {
                        $contribuyente->save();
                        $mensaje = "Contribuyente registrado con éxito.";              
                    } catch (\Exception $e) {
                        $mensaje = "Error al obtener fecha de inicio y de vigencia.";
                    }
                }   
            }else{
                try {
                    $contribuyente->save();  
                    $mensaje = "Contribuyente registrado con éxito.";                  
                } catch (\Exception $e) {
                    $mensaje = "Error al generar archivo .pem.";
                }
            }
        }else{
            try {
                $contribuyente->save();      
                $mensaje = "Contribuyente registrado con éxito.";              
            } catch (\Exception $e) {
                $mensaje = "Error al registrar el contribuyente.";
            }
        }
       $mensaje = strpos($mensaje, "éxito") ? [$mensaje, "alert-success"]: [$mensaje, "alert-danger"];
        \Session::flash('respuesta', $mensaje);
        return redirect()->route('contribuyentes.all');
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $contribuyente = Contributor::findOrFail($id);
        $mensaje = "";
        $contribuyente->fill($request->only(['name', 'rfc', 'regimen_id', 'pass_key']));

        if ($request->hasFile('cer') && $request->hasFile('key')) {
            Storage::deleteDirectory("storage/".$request->rfc."/");
            $fileCer = $request->file('cer');
            $fileKey = $request->file('key');
            $nombreCer = $fileCer->getClientOriginalName();
            $nombreKey = $fileKey->getClientOriginalName();
            //Guardo archivos en storage
            $this->storeFile($request->rfc, $fileCer, $nombreCer);
            $this->storeFile($request->rfc, $fileKey, $nombreKey);
            //Rutas de archivos cer y key
            $pathCer = "storage/".$request->rfc."/".$nombreCer;
            $pathKey = "storage/".$request->rfc."/".$nombreKey;
            //Genera CerPem File
            $pemFile = $this->certificado->generaCerPem($pathCer);
            $pathCerPem = "storage/".$request->rfc."/".$nombreCer.".pem";
                
            //Genera KeyPem File
            if ($request->has('pass_key')) {
                $keyPemFile = $this->certificado->generaKeyPem($pathKey, $request->pass_key);
                $pathKeyPem = "storage/".$request->rfc."/".$nombreKey.".pem";
                
                //Se valida que los archivos correspondan al mismo certificado
                $certificadosPareja = $this->certificado->pareja($pathCerPem,$pathKeyPem );
            }
            
            
            //Se obtiene la fecha de vigencia
            if ($pemFile['result']) {
                $certificadoValido = $this->certificado->validarCertificado($pathCerPem);
                $resultadoVigencia = $this->certificado->getFechaVigencia($pathCerPem);
                $resultadoInicio = $this->certificado->getFechaInicio($pathCerPem);
                $serial = $this->certificado->getSerialCert($pathCerPem);
                if ($resultadoVigencia['result'] && $resultadoInicio['result'] && $serial['result']) {
                    $contribuyente->end_date = date_create($resultadoVigencia['fecha']);
                    $contribuyente->start_date = date_create($resultadoInicio['fecha']);
                    $contribuyente->num_serie = $serial['serial'];
                    try {
                        $contribuyente->save();

                        $cer = new FileCer();
                        $cer->name = $nombreCer;
                        $cer->contributor_id = $contribuyente->id;
                        $cer->save();

                        $key = new FileCer();
                        $key->name = $nombreKey;
                        $key->contributor_id = $contribuyente->id;
                        $key->save();

                        $pem = new FileCer();
                        $pem->name = $nombreCer.".pem";
                        $pem->contributor_id = $contribuyente->id;
                        $pem->save();
                        $mensaje = "Contribuyente actualizado con éxito.";
                    } catch (\Exception $e) {
                        $mensaje = "Error al procesar los archivos.";
                    }
                } else {
                    try {
                        $contribuyente->save(); 
                        $mensaje = "Contribuyente actualizado con éxito.";             
                    } catch (\Exception $e) {
                        $mensaje = "Error al obtener fecha de inicio y de vigencia.";
                    }
                }   
            }else{
                try {
                    $contribuyente->save();
                    $mensaje = "Contribuyente actualizado con éxito.";                 
                } catch (\Exception $e) {
                    $mensaje = "Error al generar archivo .pem.";
                }
            }
        }else{
            try {
                $contribuyente->save(); 
                $mensaje = "Contribuyente actualizado con éxito.";                   
            } catch (\Exception $e) {
                $mensaje = "Error al actualizar el contribuyente.";
            }
        }

        $mensaje = strpos($mensaje, "éxito") ? [$mensaje, "alert-success"]: [$mensaje, "alert-danger"];     
        \Session::flash('respuesta', $mensaje);
        return redirect()->route('contribuyentes.all');

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
        $mensaje = "";
        try {
            $contribuyente->delete();
            $mensaje = 'Usuario eliminado correctamente';
               
        } catch (\Exception $e) {
            $mensaje = $e->getMessage();
        }
        $respuesta = ['mensaje' => $mensaje];
        return response()->json($respuesta);
    }

    public function storeFile($id, $file, $name) {
        return Storage::putFileAs("public/".$id, $file, $name);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Evento;
use App\Models\Cliente;
use App\Models\Trabajo;
use App\Models\TrabajosEventos;

class Agenda extends Controller
{
    public function index () {
        $a = '[{
	"title": "BCH237",
	"start": "2021-12-27T10:30:00",
	"end": "2021-12-27T11:30:00",
	"description": "Lecture"
}]';


        $b = ["title"=> "BCH237",
              "start"=> "2021-12-27T10:30:00",
	          "end"=> "2021-12-27T11:30:00",
	          "description"=> "Lecture"];

        return view('dashboard');

    }


    public function eventos(){

        $eventos = Evento::with('cliente', 'trabajos_eventos', 'trabajos_eventos.trabajo')->where('estado_id', '!=' , 5)->get()->toArray();
        $eventosParaFullCalendar = [];
        $i = 0;

        foreach ($eventos as $evento){
            $eventosParaFullCalendar[$i]['id'] = $evento['id'];
            $eventosParaFullCalendar[$i]['title'] = $evento['cliente']['nombre'].' '.$evento['cliente']['apellido_1'].' '.$evento['cliente']['apellido_2'].' ('.$evento['cliente']['alias'].')';;
            $eventosParaFullCalendar[$i]['start'] = $evento['fecha_hora_inicio_esperada'];
            $eventosParaFullCalendar[$i]['end'] = $evento['fecha_hora_fin_esperada'];
            $eventosParaFullCalendar[$i]['overlap'] = false;
            $eventosParaFullCalendar[$i]['textColor'] = '#000000';

            switch ($evento['estado_id']){
                case 1:
                    $eventosParaFullCalendar[$i]['backgroundColor'] = '#CEFFFE';
                    break;
                case 2:
                    $eventosParaFullCalendar[$i]['backgroundColor'] = '#DFFFCE';
                    break;
                case 3:
                    $eventosParaFullCalendar[$i]['backgroundColor'] = '#CEE4FF';
                    break;

                case 4:
                    $eventosParaFullCalendar[$i]['backgroundColor'] = '#FECA9E';
                    break;
                case 5:
                    $eventosParaFullCalendar[$i]['backgroundColor'] = '#FEA59E';
                    break;
            }


            $precioEstimado = 0;
            foreach ($evento['trabajos_eventos'] as $trabajo_evento){
                $precioEstimado = $precioEstimado + $trabajo_evento['trabajo']['precio_estimado'];
            }

            $eventosParaFullCalendar[$i]['description'] = $precioEstimado;

            $i++;
        }

//        dd($eventosParaFullCalendar);

        $a = '[{
            "title": "BCH237",
            "start": "2021-12-27T10:30:00",
            "end": "2021-12-27T11:30:00",
            "description": "Lecture"
            }]';

//        dd('aqui');

        return response($eventosParaFullCalendar);

    }

    public function eventoDetalle(Request $request){

//        return $request->id;
        $idEvento = $request->id;

//        $evento = Evento::findOrFail($idEvento)->with('cliente', 'trabajos_eventos', 'trabajos_eventos.trabajo')->get()->toArray();

        $evento = Evento::where('id', $idEvento)->with('cliente', 'trabajos_eventos', 'trabajos_eventos.trabajo')->get()->toArray();
//        $evento = $evento->toArray();


        return $evento;

        $eventosParaFullCalendar = [];

        $eventosParaFullCalendar['id'] = $evento['id'];
        $eventosParaFullCalendar['title'] = $evento['cliente']['nombre'].' '.$evento['cliente']['apellido_1'].' '.$evento['cliente']['apellido_2'].' ('.$evento['cliente']['alias'].')';
        $eventosParaFullCalendar['start'] = $evento['fecha_hora_inicio_esperada'];
        $eventosParaFullCalendar['end'] = $evento['fecha_hora_fin_esperada'];
        $precioEstimado = 0;
        foreach ($evento['trabajos_eventos'] as $trabajo_evento){
            $precioEstimado = $precioEstimado + $trabajo_evento['trabajo']['precio_estimado'];
        }

        $eventosParaFullCalendar['description'] = $precioEstimado;




//        dd($eventosParaFullCalendar);

        $a = '[{
            "title": "BCH237",
            "start": "2021-12-27T10:30:00",
            "end": "2021-12-27T11:30:00",
            "description": "Lecture"
            }]';

//        dd('aqui');

        return response($eventosParaFullCalendar);

    }

    public function clientes(){
        $listaClientes = [];
        $i=0;
        $clientes = Cliente::all()->toArray();

        foreach ($clientes as $cliente){
            $listaClientes[$i]['id'] = $cliente['id'];
            $listaClientes[$i]['nombre'] = $cliente['nombre'].' '.$cliente['apellido_1'].' '.$cliente['apellido_2'].' ('.$cliente['alias'].')';
            $i++;
        }

        return $listaClientes;
    }

    public function trabajos(){

        $trabajos = Trabajo::all()->toArray();

//        foreach ($clientes as $cliente){
//            $listaClientes[$i]['id'] = $cliente['id'];
//            $listaClientes[$i]['nombre'] = $cliente['nombre'].' '.$cliente['apellido_1'].' '.$cliente['apellido_2'].' ('.$cliente['alias'].')';
//            $i++;
//        }

        return $trabajos;
    }

    public function trabajoDetalle(Request $request){

        $trabajoDetalle = Trabajo::find($request->id)->toArray();

        return $trabajoDetalle;
    }

    public function nuevoServicio(Request $request){


        if (!isset($request['id_cliente'])){

            $cliente = new Cliente();
            $cliente->nombre = $request['nombre'];
            $cliente->apellido_1 = $request['apellido_1'];
            $cliente->apellido_2 = $request['apellido_2'];
            $cliente->alias = $request['alias'];
            $cliente->save();
            $request['id_cliente'] = $cliente->id;

        }

        $evento = new Evento();
        $evento->fecha_hora_inicio_esperada = $request['fecha-inicio'];
        $evento->fecha_hora_fin_esperada = $request['fecha-fin'];
        $evento->cliente_id = $request['id_cliente'];
        $evento->estado_id = 1;
        $evento->save();

        foreach ($request['servicios'] as $servicio){

            if(isset($servicio['value'])){

                $trabajosEventos = new TrabajosEventos();
                $trabajosEventos->evento_id = $evento->id;
                $trabajosEventos->trabajo_id =  $servicio['value'];
                $trabajosEventos->save();

            }

        }

        //todo: Comprobar que se ha grabado correctamente en la BBDD.

        return 1;

    }

    public function noShowEvento(Request $request){

        $evento = Evento::findOrFail($request['id_servicio']);
        $evento->estado_id = 4;
        $evento->save();

        $cliente = Cliente::findOrFail($evento->cliente_id);
        $cliente->servicios_no_show = $cliente->servicios_no_show + 1;
        $cliente->save();

        //todo: Comprobar que se ha grabado correctamente en la BBDD.

        return 1;

    }

    public function CancelEvento(Request $request){

        $evento = Evento::findOrFail($request['id_servicio']);
        $evento->estado_id = 5;
        $evento->save();

        $cliente = Cliente::findOrFail($evento->cliente_id);
        $cliente->servicios_cancelados = $cliente->servicios_cancelados + 1;
        $cliente->save();
        //todo: Comprobar que se ha grabado correctamente en la BBDD.

        return 1;

    }

    public function iniciarServicio(Request $request){

        $evento = Evento::findOrFail($request['id_servicio']);
        $evento->estado_id = 2;
        $evento->save();


        //todo: Comprobar que se ha grabado correctamente en la BBDD.

        return 1;

    }    public function finalizarServicio(Request $request){

        $evento = Evento::findOrFail($request['id_servicio']);
        $evento->estado_id = 3;
        $evento->save();

        $cliente = Cliente::findOrFail($evento->cliente_id);
        $cliente->servicios_finalzados = $cliente->servicios_finalzados + 1;
        $cliente->save();


        //todo: Comprobar que se ha grabado correctamente en la BBDD.

        return 1;

    }

    public function ActualizarHorasServicio(Request $request){

        $evento = Evento::findOrFail($request['id_servicio']);
        $evento->fecha_hora_inicio_esperada = $request['fecha-inicio'];
        $evento->fecha_hora_fin_esperada = $request['fecha-fin'];
        $evento->save();




        //todo: Comprobar que se ha grabado correctamente en la BBDD.

        return 1;

    }

    public function compromisoCliente(Request $request){

        $Historicocliente = Evento::where('cliente_id', $request->id)->get()->toArray();

        $serviciosTotales = 0;
        $serviciosKO = 0;
        $compromiso = 0;

        foreach ($Historicocliente as $historico){

            if($historico['estado_id'] > 3){

                $serviciosKO ++;

            }

            if ($historico['estado_id'] >= 3){

                $serviciosTotales ++;

            }

        }

        if ($serviciosTotales != 0 && $serviciosKO != 0){

            $compromiso = round(((100/$serviciosTotales)*$serviciosKO));

        }

        return $compromiso;


    }


/**
1	previsto	realizado		Previsto = 1 y 2
2	previsto	no realizado		Realizado = 1 y 3
3	No previsto	realizado

*/
}

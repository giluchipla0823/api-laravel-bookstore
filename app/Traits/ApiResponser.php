<?php
namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use App\Libraries\Api;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response;

Trait ApiResponser
{

    /**
     * Crear respuesta de éxito
     *
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    protected function successResponse($data, $message = Api::RESPONSE_SUCCESSFUL_OPERATION, $code = Response::HTTP_OK){
        return $this->makeResponse($data, $message, $code, Api::CODE_SUCCESS);
    }

    /**
     * Crear respuesta de error
     *
     * @param string $message
     * @param int $code
     * @param int $status
     * @param array $extras
     * @return JsonResponse
     */
    protected function errorResponse($message, $code, $status = Api::CODE_ERROR, $extras = []){
        return $this->makeResponse(NULL, $message, $code, $status, $extras);
    }

    /**
     * Crear respuesta para colleciones de datos
     *
     * @param Collection $collection
     * @return JsonResponse
     */
    protected function showAll(Collection $collection){
        if(request()->get('listFormat') === 'datatables'){
            $collection = $this->transformDatatables($collection);
        }else{
            $collection = $this->transformCollection($collection);
        }

        return $this->successResponse($collection);
    }

    /**
     * Crear respuesta para instancias de un modelo
     *
     * @param Model $instance
     * @return JsonResponse
     */
    protected function showOne(Model $instance){
        $instance = $this->transformInstance($instance);

        return $this->successResponse($instance);
    }

    /**
     * Crear respuesta para mostrar mensajes
     *
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    protected function showMessage($message, $code = Response::HTTP_OK){
        return $this->successResponse(NULL, $message, $code);
    }

    /**
     * Construir estructura de respuesta json
     *
     * @param $data
     * @param string $message
     * @param int $code
     * @param int $status
     * @param array $extras
     * @return JsonResponse
     */
    protected function makeResponse($data, $message, $code, $status, $extras = []) {
        $response = (new Api)->makeResponse(
            $message,
            $status,
            $code
        );

        if(!is_null($data)){
            $response[Api::IDX_STR_JSON_DATA] = $data;
        }

        $response = array_merge($response, $extras);

        return response()->json($response, $code);
    }
}
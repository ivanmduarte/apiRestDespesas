<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Despesa;

class DespesaController extends Controller
{

    public function index()
    {
        try {
            $despesas = Despesa::select(["despesas.*","users.name AS usuario_name"])->join("users","users.id","despesas.usuario")->get();
            return response()->json([
                "status"=> 200,
                "data"  => $despesas
            ],200);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return response()->json([
                "status" => 500,
                "message" => "Ocorreu uma falha ao retornar as despesas. Tente novamente!",
                "failMessage" => $th->getMessage(),
            ],500);
        }
        
    }

    public function store(Request $request)
    {
        try {
            $fields = $request->validate([
                "descricao" => "required|string",
                "data" => "required|date|before:".date('Y-m-d H:i:s'),
                "valor" => "required|numeric|min:0.00",
            ]);

            $fields['usuario'] = $request->user()->id;
            $inserido = Despesa::create($fields);
    
            if($inserido)
            {
                return response()->json([
                    "status"  => 200,
                    "message" => "Despesa salva com sucesso!"
                ],200);
            }

            return response()->json([
                "status"  => 400,
                "message" => "Falha ao cadastrar despesa."
            ],400);
            
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return response()->json([
                "status" => 500,
                "message" => "Ocorreu uma falha ao salvar a despesa.",
                "failMessage" => $th->getMessage(),
            ],500);
        }
    }

    public function show($id)
    {
        try {
            $despesa = Despesa::with('usuario')->findOrFail($id);
            
            if(request()->user()->id == $despesa->usuario)
            {
                return response()->json([
                    "status"=> 200,
                    "data"  => $despesa
                ],200);
            }
            
            return response()->json([
                "status" => 401,
                "message"=> "Você não possuí permissão para acessar essa despesa."
            ],401);

        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return response()->json([
                "status" => 500,
                "message" => "Ocorreu uma falha ao retornar a despesa. Tente novamente!",
                "failMessage" => $th->getMessage(),
            ],500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $despesa = Despesa::findOrFail($id);
            if($request->user()->id == $despesa->usuario)
            {
                if($despesa)
                {
                    $fields = $request->validate([
                        "descricao" => "required|string",
                        "data" => "required|date|before:".date('Y-m-d H:i:s'),
                        "valor" => "required|numeric|min:0.00",
                    ]);
                    $atualizado = $despesa->update($fields);

                    if($atualizado)
                    {
                        return response()->json([
                            "status"  => 200,
                            "message" => "Despesa atualizada com sucesso."
                        ],200);
                    }
                    else
                    {
                        return response()->json([
                            "status"  => 400,
                            "message" => "Falha ao atualizar despesa."
                        ],400);
                    }
                }

                return response()->json([
                    "status"  => 400,
                    "message" => "Falha ao atualizar despesa. Registro não encontrado!"
                ],400);
            }

            return response()->json([
                "status"  => 401,
                "message" => "Você não possuí permissão para atualizar essa despesa."
            ],401);
            

        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return response()->json([
                "status" => 500,
                "message" => "Ocorreu uma falha ao atualizar despesa.",
                "failMessage" => $th->getMessage(),
            ],500);
        }
    }

    public function destroy($id)
    {
        try {
            $despesa = Despesa::findOrFail($id);
            if(request()->user()->id == $despesa->usuario)
            {
                $excluido= $despesa->delete();

                if($excluido)
                {
                    return response()->json([
                        "status"  => 200,
                        "message" => "Despesa excluida com sucesso."
                    ],200);
                }
                else
                {
                    return response()->json([
                        "status"  => 400,
                        "message" => "Falha ao excluir despesa."
                    ],400);
                }
            }

            return response()->json([
                "status"  => 401,
                "message" => "Você não possuí permissão para excluir essa despesa."
            ],401);

        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return response()->json([
                "status" => 500,
                "message" => "Ocorreu uma falha ao excluir a despesa.",
                "failMessage" => $th->getMessage(),
            ],500);
        }
    }
}

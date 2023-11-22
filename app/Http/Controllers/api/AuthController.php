<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $fields = $request->validate([
                "name" => "required|string",
                "email" => "required|email",
                "password" => "required|string"
            ]);

            $usuario = User::where("email", $fields["email"])->first();

            if(!$usuario || $usuario->password != md5($fields["password"]))
            {
                $fields["password"] = md5($fields["password"]);

                $inserido = User::create($fields);

                if($inserido)
                {
                    $nomeToken = $inserido->email.".".$inserido->password;
                    $token = $inserido->createToken($nomeToken)->plainTextToken;

                    return response()->json([
                        "status"  => 200,
                        "message" => "Registro efetuado com sucesso!",
                        "data"    => [
                            "usuario" => $inserido->name,
                            "token"   => $token
                        ]
                    ],200);
                }
            }

            return response()->json([
                "status" => 401,
                "message"=> "Email já cadastrado."
            ],401);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return response()->json([
                "status" => 500,
                "message" => "Ocorreu uma falha ao se registrar.",
                "failMessage" => $th->getMessage(),
            ],500);
        }
    }

    public function login(Request $request)
    {
        try {
            $fields = $request->validate([
                "email" => "required|email",
                "password" => "required|string"
            ]);
    
            $usuario = User::where("email", $fields["email"])->first();
    
            if(!$usuario || $usuario->password != md5($fields["password"]))
            {
                return response()->json([
                    "status" => 401,
                    "message"=> "Usuário ou senha inválidos."
                ],401);
            }
    
            $nomeToken = $usuario->email.".".$usuario->password;
            $token = $usuario->createToken($nomeToken)->plainTextToken;
    
            return response()->json([
                "status" => 201,
                "message"=> "Login efetuado com sucesso.",
                "data"   => [
                    "usuario" => $usuario->name,
                    "token"   => $token
                ]
            ],201);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return response()->json([
                "status" => 500,
                "message" => "Ocorreu uma falha ao efetuar login.",
                "failMessage" => $th->getMessage(),
            ],500);
        }
    }
}

<?php 

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Src\UseCases\Deposit;
use App\Src\UseCases\Dto\Deposit\DepositDto;
use Symfony\Component\HttpFoundation\Response as ResponseStatusCode;

class DepositController extends Controller
{
    public function __construct(private Deposit $deposit){}

    public function deposit(Request $request)
    {
        $response = $this->deposit->execute(
            new DepositDto(
                $request->numberAccountOrigin,
                $request->value,
                $request->type,
                $request->description
            )
        );

        if ($response->outcome === true) {
            return response()->json(['success' => $response->message], ResponseStatusCode::HTTP_OK);
        }

        return response()->json(['error' => $response->message], ResponseStatusCode::HTTP_UNPROCESSABLE_ENTITY);
    }
}

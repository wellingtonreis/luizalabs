<?php 

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\WithdrawRequest;
use App\Src\UseCases\Withdraw;
use App\Src\UseCases\Dto\Withdraw\WithdrawDto;
use Symfony\Component\HttpFoundation\Response as ResponseStatusCode;

class WithdrawController extends Controller
{
    public function __construct(private Withdraw $withdraw){}

    public function withdraw(WithdrawRequest $request)
    {
        $response = $this->withdraw->execute(
            new WithdrawDto(
                $request->numberAccountOrigin,
                $request->value,
                $request->type,
                $request->description
            )
        );

        if ($response->outcome) {
            return response()->json(['success' => $response->message], ResponseStatusCode::HTTP_OK);
        }

        return response()->json(['error' => $response->message], ResponseStatusCode::HTTP_UNPROCESSABLE_ENTITY);
    }
}

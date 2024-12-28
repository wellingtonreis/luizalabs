<?php 

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransferRequest;
use App\Src\UseCases\TransferFunds;
use App\Src\UseCases\Dto\TransferFunds\TransferFundsDto;
use Symfony\Component\HttpFoundation\Response as ResponseStatusCode;

class TransferController extends Controller
{
    public function __construct(private TransferFunds $transferFunds){}

    public function transferFunds(TransferRequest $request)
    {
        $response = $this->transferFunds->execute(
            new TransferFundsDto(
                $request->numberAccountOrigin,
                $request->numberAccountDestination,
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

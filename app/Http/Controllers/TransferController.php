<?php 

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Src\UseCases\TransferFunds;
use App\Src\UseCases\Dto\TransferFunds\TransferFundsDto;

class TransferController extends Controller
{
    public function __construct(private TransferFunds $transferFunds){}

    public function deposit(Request $request)
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
            return response()->json(['success' => $response->message], 200);
        }

        if(!$response->outcome) {
            return response()->json(['error' => $response->message], 422);
        }
    }

    public function withdraw(Request $request)
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
            return response()->json(['success' => $response->message], 200);
        }

        if(!$response->outcome) {
            return response()->json(['error' => $response->message], 422);
        }
    }

    public function transferFunds(Request $request)
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
            return response()->json(['success' => $response->message], 200);
        }

        if(!$response->outcome) {
            return response()->json(['error' => $response->message], 422);
        }
    }
}

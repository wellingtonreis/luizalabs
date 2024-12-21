<?php 

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Src\UseCases\Withdraw;
use App\Src\UseCases\Dto\Withdraw\WithdrawDto;

class WithdrawController extends Controller
{
    public function __construct(private Withdraw $withdraw){}

    public function withdraw(Request $request)
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
            return response()->json(['success' => $response->message], 200);
        }

        if(!$response->outcome) {
            return response()->json(['error' => $response->message], 422);
        }
    }
}
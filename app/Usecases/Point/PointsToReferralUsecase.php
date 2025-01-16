<?php

namespace App\Usecases\Point;

use App\Models\PointAddingHistory;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PointsToReferralUsecase
{
    /**
     * @param User $user
     * @param String $point
     */
    public function handle(User $user, string $point): bool
    {

        try {

            DB::beginTransaction();
            $history = new PointAddingHistory([
                'run_by' => Auth::user()->id,
                'started_at' => Carbon::now()
            ]);

            $PATH = $user->path;

            preg_match_all('#/([^/]*)#', $PATH, $matches);  //SEPERATE PATH VARIABLES

            $idList = $matches[0];

            array_pop($idList); //REMOVE LAST VARUIABLE DUE TO ITS OWN
            $leftPointIdList = [];
            $rightPointIdList = [];

            foreach ($idList as $node) {
                $position  = strpos($node, 'P');
                $reg_no = substr($node, $position  + 1);

                preg_match_all('/\d+/', $reg_no, $ids);
                $id = $ids[0];

                if (strpos($reg_no, 'SL') !== false) {
                    Log::debug($reg_no . ' SL');
                    array_push($leftPointIdList, $id);
                }
                if (strpos($reg_no, 'SR') !== false) {
                    Log::debug($reg_no . ' SR');
                    array_push($rightPointIdList, $id);
                }
            }

            if (sizeof($leftPointIdList)) {
                User::whereIn('id', $leftPointIdList)
                    ->where('er_status', User::USER_STATUS['ER'])
                    ->update(['left_points' => DB::raw('left_points +' . $point)]);
                $history->left_point_id_list = json_encode($leftPointIdList);
            }

            if (sizeof($rightPointIdList)) {
                User::whereIn('id', $rightPointIdList)
                    ->where('er_status', User::USER_STATUS['ER'])
                    ->update(['right_points' => DB::raw('right_points +' . $point)]);
                $history->right_point_id_list = json_encode($rightPointIdList);
            }
            $history->ended_at = Carbon::now();
            $history->save();
            DB::commit();
            return true;
        } catch (Exception $e) {

            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }
}

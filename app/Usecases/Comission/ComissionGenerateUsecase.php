<?php

namespace App\Usecases\Comission;

use App\Models\ComissionGeneratHistory;
use App\Models\ComissionHistory;
use App\Models\Wallet;
use App\Models\ComissionLevel;
use App\Models\User;
use App\Models\WalletHistory;
use App\Traits\ActivityLogTrait;
use App\Traits\ComissionTrait;
use App\Traits\WalletTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ComissionGenerateUsecase
{
    use WalletTrait, ComissionTrait, ActivityLogTrait;

    public $comissionLevels;

    public function handle(string $userId): bool
    {

        $this->getTableBackup();
        $this->addToLog(msg: 'startGenerateComissions STARTED');
        $history = $this->addToHistory(userId: $userId);
        $this->comissionLevels = ComissionLevel::all();

        $users = User::where('er_status', User::USER_STATUS['ER'])
            ->get();

        foreach ($users as $user) {
            $currentLevel = $user->comission_level;

            if ($currentLevel < $this->getAvailableMaxLevel()) {

                $nextLevel = $currentLevel + 1;
            } else {
                $nextLevel = $currentLevel;
            }

            $achievebleLimit = $this->getMaxLevelCanAchieve(
                leftPoints: $user->left_points,
                rightPoints: $user->right_points
            );

            if ($achievebleLimit > $nextLevel)
                $nextLevel = $achievebleLimit;

            $limit = $this->getLevelLimits(level: $nextLevel);

            if (
                $user->left_points >= $limit->left_points
                && $user->right_points >= $limit->right_points
            ) {
                try {
                    DB::beginTransaction();
                    $this->generate(
                        leftLimit: $limit->left_points,
                        rightLimit: $limit->right_points,
                        pointValue: $limit->point_value,
                        user: $user,
                        level: $nextLevel
                    );
                    DB::commit();
                } catch (Exception $e) {
                    DB::rollback();
                    Log::debug($e->getMessage());
                }
            }
        }

        try {
            $this->addToLog(msg: 'startComissionTransfer STARTED');
            $this->transferDummyCommissions();
            $this->addToLog(msg: 'startComissionTransfer ENDED');
        } catch (Exception $e) {
            $this->addToLog(msg: 'startComissionTransfer STOPPED');
            Log::debug($e->getMessage());
        }
        $history->ended_at = Carbon::now();
        $history->status = 1;
        $history->save();

        $this->addToLog(msg: 'startGenerateComissions ENDED');
        return  true;
    }

    private function getTableBackup()
    {
        $time = Carbon::now()->format('Ymdhi');
        $wallet_table_name = 'wallets_' .  $time;
        $wallet_history_table_name = 'wallet_histories_' .  $time;

        DB::statement('CREATE TABLE ' . $wallet_table_name . ' AS SELECT * FROM wallets;');
        DB::statement('CREATE TABLE ' . $wallet_history_table_name . ' AS SELECT * FROM wallet_histories;');
    }

    private function generate(
        string $leftLimit,
        string $rightLimit,
        string $pointValue,
        User $user,
        string $level
    ) {
        $this->setComissionHistory(
            userId: $user->id,
            leftPoints: $leftLimit,
            rightPoints: $rightLimit
        );
        $this->updateWallet(userId: $user->id, amount: $pointValue);

        if ($this->isPremiumAchieved(level: $level)) {
            $user->right_points = 0;
            $user->left_points = 0;
        } else {
            $user->left_points -= $leftLimit;
            $user->right_points -= $rightLimit;
        }

        $user->comission_level = $level;
        $user->save();
    }

    /**
     * ADD TO WALLET
     * @param string $userId
     * @param string $amount
     */
    public function updateWallet(string $userId, int $amount)
    {
        $wallet = Wallet::where('user_id', $userId)->first();
        if (!isset($wallet)) {
            $wallet = Wallet::create([
                'user_id' => $userId,
                'balance' => 0,
                'status' => Wallet::STATUS['ACTIVE']
            ]);
        };
        $wallet->balance += $amount;
        $wallet->save();

        $this->setWalletHistory(
            userId: $userId,
            wallet: $wallet,
            amount: $amount,
            type: Wallet::TYPE['ADDED'],
            comissionType: WalletHistory::COMISSION_TYPES['GSC']
        );
    }

    private function getLevelLimits(string $level)
    {
        foreach ($this->comissionLevels as $l) {
            if ($level == $l->level)
                return $l;
        }
    }

    private function isPremiumAchieved(string $level): bool
    {
        foreach ($this->comissionLevels as $l) {
            if ($level == $l->level) {
                if ($l->is_premium) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    private function getMaxLevelCanAchieve(?int $leftPoints, ?int $rightPoints)
    {
        $level = 0;
        if (isset($leftPoints) && isset($rightPoints)) {
            foreach ($this->comissionLevels as $l) {
                if ($leftPoints >= $l->left_points && $rightPoints >= $l->right_points) {
                    if ($level < $l->level) {
                        $level = $l->level;
                    }
                }
            }
        }
        return $level;
    }

    private function getAvailableMaxLevel()
    {
        $level = 0;
        foreach ($this->comissionLevels as $l) {
            if ($level < $l->level) {
                $level = $l->level;
            }
        }
        return $level;
    }

    private function addToHistory(string $userId)
    {

        return ComissionGeneratHistory::create([
            'run_by' => $userId,
            'started_at' => Carbon::now(),
        ]);
    }
}

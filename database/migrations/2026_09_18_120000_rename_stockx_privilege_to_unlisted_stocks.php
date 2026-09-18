<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->renameUnlistedPrivilege('stockx', 'unlisted_stocks');
    }

    public function down(): void
    {
        $this->renameUnlistedPrivilege('unlisted_stocks', 'stockx');
    }

    private function renameUnlistedPrivilege(string $from, string $to): void
    {
        DB::table('users')->whereNotNull('privilege')->select('uid', 'privilege')->get()->each(function ($user) use ($from, $to) {
            $privilege = json_decode($user->privilege, true);

            if (!is_array($privilege) || !is_array($privilege['unlisted'] ?? null) || !array_key_exists($from, $privilege['unlisted'])) {
                return;
            }

            $renamed = [];
            foreach ($privilege['unlisted'] as $key => $value) {
                if ($key === $from) {
                    if (!array_key_exists($to, $privilege['unlisted'])) {
                        $renamed[$to] = $value;
                    }
                    continue;
                }
                $renamed[$key] = $value;
            }
            $privilege['unlisted'] = $renamed;

            DB::table('users')->where('uid', $user->uid)->update(['privilege' => json_encode($privilege)]);
        });
    }
};

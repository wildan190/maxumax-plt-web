<?php

namespace App\Repositories\Preorder;

use App\Models\PreorderHistory;

class PreorderHistoryRepository
{
    public function add(int $preorderId, mixed $oldStatus, mixed $newStatus, string $note): PreorderHistory
    {
        return PreorderHistory::create([
            'preorder_id' => $preorderId,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'note' => $note,
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Helpers;

class AgentHelper
{

    public static function unsetClientSession()
    {
        unset(
            $_SESSION['nino'],
            $_SESSION['client_name'],
            $_SESSION['client_id'],
            $_SESSION['business_id'],
            $_SESSION['type_of_business'],
            $_SESSION['trading_name'],
            $_SESSION['clients_to_delete'],
            $_SESSION['agent_type'],
            $_SESSION['period_type'],
            $_SESSION['period_start_date'],
            $_SESSION['period_end_date']
        );
    }

    // used by Firm and Clients
    public static function paginate(int $total_items, int $per_page, array $get): array
    {
        $current_page = isset($get['page']) ? (int) $get['page'] : 1;
        $current_page = max($current_page, 1);

        $total_pages = (int) ceil($total_items / $per_page);
        $offset = ($current_page - 1) * $per_page;

        return [

            'per_page' => $per_page,
            'offset' => $offset,
            'total_pages' => $total_pages,
            'total_items' => $total_items,
            'current_page' => $current_page,
            'has_prev_page' => $current_page > 1,
            'has_next_page' => $current_page < $total_pages,
            'next_page' => $current_page < $total_pages ? $current_page + 1 : null,
            'prev_page' => $current_page > 1 ? $current_page - 1 : null,
        ];
    }
}

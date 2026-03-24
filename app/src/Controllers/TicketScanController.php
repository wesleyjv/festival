<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Security\Csrf;
use App\Services\TicketService;

/**
 * Mobile-friendly ticket scanner for staff (employee / admin).
 */
class TicketScanController
{
    private TicketService $ticketService;

    public function __construct(?TicketService $ticketService = null)
    {
        $this->ticketService = $ticketService ?? new TicketService();
    }

    public function index($vars = []): void
    {
        if (!$this->ensureStaff()) {
            return;
        }

        $bodyClass = 'ticket-scan-page';
        $mainClass = 'ticket-scan-main';

        require __DIR__ . '/../views/employee/ticket-scan.php';
    }

    public function scan($vars = []): void
    {
        header('Content-Type: application/json; charset=utf-8');

        if (!$this->ensureStaffJson()) {
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Csrf::validateRequest()) {
            http_response_code(403);
            echo json_encode(['ok' => false, 'error' => 'Invalid or missing CSRF token.']);

            return;
        }

        $raw = file_get_contents('php://input') ?: '';
        $data = json_decode($raw, true);
        $code = '';
        if (is_array($data) && isset($data['code'])) {
            $code = (string) $data['code'];
        }
        if ($code === '' && isset($_POST['code'])) {
            $code = (string) $_POST['code'];
        }

        $result = $this->ticketService->scanTicketByCode($code);

        $payload = [
            'ok' => true,
            'status' => $result['status'],
            'message' => $result['message'],
        ];

        if ($result['ticket'] !== null) {
            $t = $result['ticket'];
            $payload['ticket'] = [
                'id' => $t->id,
                'name' => $t->name,
                'ticketCode' => $t->ticketCode,
                'eventId' => $t->eventId,
            ];
        } else {
            $payload['ticket'] = null;
        }

        if ($result['status'] === 'invalid') {
            http_response_code(400);
        }

        echo json_encode($payload);
    }

    private function ensureStaff(): bool
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: /login');

            return false;
        }

        $role = $_SESSION['user_role'] ?? '';
        if ($role !== 'employee' && $role !== 'admin') {
            http_response_code(403);
            echo 'Access denied. This page is for festival staff.';

            return false;
        }

        return true;
    }

    private function ensureStaffJson(): bool
    {
        if (empty($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['ok' => false, 'error' => 'Authentication required.']);

            return false;
        }

        $role = $_SESSION['user_role'] ?? '';
        if ($role !== 'employee' && $role !== 'admin') {
            http_response_code(403);
            echo json_encode(['ok' => false, 'error' => 'Access denied.']);

            return false;
        }

        return true;
    }
}

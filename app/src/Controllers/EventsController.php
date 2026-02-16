<?php

namespace App\Controllers;

class EventsController
{
    private EventRepository $eventRepository;

    public function __construct()
    {
        $this->eventRepository = new EventRepository();
    }

    public function history()
    {
        $events = $this->eventRepository->getHistoryEvents();

        require __DIR__ . '/../views/events/history/overview.php';
    }

    public function jazz($vars = [])
    {
        require __DIR__ . '/../views/events/jazz/overview.php';
    }

    public function stories($vars = [])
    {
        require __DIR__ . '/../views/events/stories/overview.php';
    }

    public function yummy($vars = [])
    {
        require __DIR__ . '/../views/events/yummy/overview.php';
    }
}

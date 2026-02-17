<?php

namespace App\Controllers;

use App\Repositories\EventRepository;

/**
 * Controller responsible for handling event-related page requests.
 *
 * Manages the display of overview pages for each event category:
 * History, Jazz, Stories, and Yummy.
 */
class EventsController
{
    /**
     * @var EventRepository Repository used to retrieve event data.
     */
    private EventRepository $eventRepository;

    /**
     * Initializes the controller with a new EventRepository instance.
     */
    public function __construct()
    {
        $this->eventRepository = new EventRepository();
    }

    /**
     * Displays the history events overview page.
     *
     * Retrieves all history events from the repository and passes them
     * to the history overview view.
     *
     * @return void
     */
    public function history()
    {
        $events = $this->eventRepository->getHistoryEvents();

        require __DIR__ . '/../views/events/history/overview.php';
    }

    /**
     * Displays the jazz events overview page.
     *
     * @param array $vars Optional route parameters passed to the view.
     * @return void
     */
    public function jazz($vars = [])
    {
        require __DIR__ . '/../views/events/jazz/overview.php';
    }

    /**
     * Displays the stories events overview page.
     *
     * @param array $vars Optional route parameters passed to the view.
     * @return void
     */
    public function stories($vars = [])
    {
        require __DIR__ . '/../views/events/stories/overview.php';
    }

    /**
     * Displays the yummy events overview page.
     *
     * @param array $vars Optional route parameters passed to the view.
     * @return void
     */
    public function yummy($vars = [])
    {
        require __DIR__ . '/../views/events/yummy/overview.php';
    }
}

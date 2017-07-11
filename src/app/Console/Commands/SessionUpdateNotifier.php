<?php

namespace Shrizzer\Console\Commands;

use Illuminate\Console\Command;
use Shrizzer\Services\SessionService;

class SessionUpdateNotifier extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var SessionService
     */
    protected $sessionService;

    /**
     * @param SessionService $sessionService
     */
    public function __construct(SessionService $sessionService)
    {
        parent::__construct();

        $this->sessionService = $sessionService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->sessionService->notifyUserAboutSessionChange();
    }
}

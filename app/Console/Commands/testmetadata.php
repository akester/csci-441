<?php

namespace App\Console\Commands;

use App\Models\Document;
use Illuminate\Console\Command;

class testmetadata extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:metadata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $document = Document::findOrFail(3);
        $metadata = $document->GetMetadata();

        $metadata->save();
        $metadata->SaveBookmarks();

        return 0;
    }
}

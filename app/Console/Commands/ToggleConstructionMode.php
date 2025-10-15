<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ToggleConstructionMode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'construction:toggle {--status= : Set specific status (on/off)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Toggle or set construction mode status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $settings = app(\App\Settings\AdminSettings::class);
        $status = $this->option('status');
        
        if ($status) {
            $activate = in_array(strtolower($status), ['on', '1', 'true', 'yes']);
        } else {
            $activate = !$settings->construction['activate'];
        }
        
        $construction = $settings->construction;
        $construction['activate'] = $activate;
        $settings->construction = $construction;
        $settings->save();
        
        $statusText = $activate ? 'ACTIVÉ' : 'DÉSACTIVÉ';
        $this->info("Mode construction: {$statusText}");
        
        if ($activate) {
            $this->line("Titre: {$settings->construction['titre']}");
            $this->line("Description: {$settings->construction['description']}");
            $this->warn("Seuls les administrateurs connectés pourront voir le site.");
        } else {
            $this->line("Le site est maintenant accessible à tous les visiteurs.");
        }
        
        return 0;
    }
}

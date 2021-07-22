<?php


namespace App\Console\Commands\Eve;


use App\Models\Sde\MapDenormalize;
use Cassandra\Map;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;


class Sde extends Command
{
    use DispatchesJobs;


    protected $signature = 'eve:update:sde';


    protected $description = 'Updates the EVE Online SDE Data';


    protected $json;

    protected $storage_path;


    public function handle(){

        $this->comment('You are about to wipe and update required SDE Data');

        DB::connection()->getDatabaseName();

        if(!$this->confirm("Are you sure you want to update to the latest EVE SDE", true)){
            $this->warn('exiting');

            return;
        }
        $this->json = $this->getJson();

        if(! $this->isStorageOk()){
            $this->error("Storage path is not ok. check permissions");
            return;

        }


        $this->json->tables = ['mapDenormalize', 'staStations'];
        $this->getSde();
        $this->importSde();
        $this->explodeMap();

        $this->info('done...');
    }


    public function isStorageOk(){
        $storage = storage_path() . '/sde/' . $this->json->version . '/';
        $this->info('Storage Path is: '.$storage);

        if(File::isWritable(storage_path())){
            if(! File::exists($storage))
                File::makeDirectory($storage, 0755, true);

            $this->storage_path = $storage;

            return true;
        }

        return false;
    }

    public function getJson(){
        $result = Http::get(
            'https://raw.githubusercontent.com/eveseat/resources/master/sde.json', [
                'headers' => ['Accept' => 'application/json']
            ]
        );
        if($result->status() != 200){
            return json_encode([]);
        }
        return json_decode($result->body());
    }

    public function getProgressBar($iterations)
    {
        $bar = $this->output->createProgressBar($iterations);
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s% %memory:6s%');

        return $bar;
    }

    public function getSde(){
        $this->line('Downloading...');

        $bar = $this->getProgressBar(count($this->json->tables));

        foreach($this->json->tables as $table){

            $url = str_replace(':version', $this->json->version, $this->json->url) .
                $table .$this->json->format;

            $destination = $this->storage_path . $table .$this->json->format;

            $file_handler = fopen($destination, 'w');

            $result = Http::withOptions([
                'sink' => $file_handler
            ])->get($url);

            fclose($file_handler);

            if($result->status() != 200)
                $this->error('unable to download ' .$url . '. The HTTP response was: '.$result->status());

            $bar->advance();

        }

        $bar->finish();
        $this->line('');


    }

    public function importSde(){

        $this->line('Importing...');
        $bar = $this->getProgressBar(count($this->json->tables));

        foreach ($this->json->tables as $table) {

            $archive_path = $this->storage_path . $table . $this->json->format;
            $extracted_path = $this->storage_path . $table . '.sql';

            if (! File::exists($archive_path)) {

                $this->warn($archive_path . ' seems to be invalid. Skipping.');
                continue;
            }

            // Get 2 handles ready for both the in and out files
            $input_file = bzopen($archive_path, 'r');
            $output_file = fopen($extracted_path, 'w');

            // Write the $output_file in chunks
            while ($chunk = bzread($input_file, 4096))
                fwrite($output_file, $chunk, 4096);

            // Close the files
            bzclose($input_file);
            fclose($output_file);

            // With the output file ready, prepare the scary exec() command
            // that should be run. A sample $import_command is:
            // mysql -u root -h 127.0.0.1 seat < /tmp/sample.sql
            $import_command = 'mysql -u ' . config('database.connections.mysql.username') .
                // Check if the password is longer than 0. If not, don't specify the -p flag
                (strlen(config('database.connections.mysql.password')) ? ' -p' : '')
                // Append this regardless. Escape special chars in the password too.
                . escapeshellcmd(config('database.connections.mysql.password')) .
                ' -h ' . config('database.connections.mysql.host') .
                ' -P ' . config('database.connections.mysql.port') .
                ' ' . config('database.connections.mysql.database') .
                ' < ' . $extracted_path;


            // Run the command... (*scared_face*)
            exec($import_command, $output, $exit_code);

            if ($exit_code !== 0)
                $this->error('Warning: Import failed with exit code ' .
                    $exit_code . ' and command output: ' . implode('\n', $output));

            $bar->advance();

        }

        $bar->finish();
        $this->line('');
    }

    private function explodeMap()
    {
        // extract regions
        DB::table('regions')->truncate();
        DB::table('regions')
            ->insertUsing([
                'region_id', 'name',
            ], DB::table('mapDenormalize')->where('groupID', MapDenormalize::REGION)
                ->select('itemID', 'itemName'));

        // extract constellations
        DB::table('constellations')->truncate();
        DB::table('constellations')
            ->insertUsing([
                'constellation_id', 'region_id', 'name',
            ], DB::table('mapDenormalize')->where('groupID', MapDenormalize::CONSTELLATION)
                ->select('itemID', 'regionID', 'itemName'));

        // extract solar systems
        DB::table('solar_systems')->truncate();
        DB::table('solar_systems')
            ->insertUsing([
                'system_id', 'constellation_id', 'region_id', 'name', 'security',
            ], DB::table('mapDenormalize')->where('groupID', MapDenormalize::SYSTEM)
                ->select('itemID', 'constellationID', 'regionID', 'itemName', 'security'));

        // extract stars
        DB::table('stars')->truncate();
        DB::table('stars')
            ->insertUsing([
                'star_id', 'system_id', 'constellation_id', 'region_id', 'name', 'type_id',
            ], DB::table('mapDenormalize')->where('groupID', MapDenormalize::SUN)
                ->select('itemID', 'solarSystemID', 'constellationID', 'regionID', 'itemName', 'typeID'));

        // extract planets
        DB::table('planets')->truncate();
        DB::table('planets')
            ->insertUsing([
                'planet_id', 'system_id', 'constellation_id', 'region_id', 'name', 'type_id',
                'x', 'y', 'z', 'radius', 'celestial_index',
            ], DB::table('mapDenormalize')->where('groupID', MapDenormalize::PLANET)
                ->select('itemID', 'solarSystemID', 'constellationID', 'regionID', 'itemName', 'typeID',
                    'x', 'y', 'z', 'radius', 'celestialIndex'));

        // extract moons
        DB::table('moons')->truncate();
        DB::table('moons')
            ->insertUsing([
                'moon_id', 'planet_id', 'system_id', 'constellation_id', 'region_id', 'name', 'type_id',
                'x', 'y', 'z', 'radius', 'celestial_index', 'orbit_index',
            ], DB::table('mapDenormalize')->where('groupID', MapDenormalize::MOON)
                ->select('itemID', 'orbitID', 'solarSystemID', 'constellationID', 'regionID', 'itemName', 'typeID',
                    'x', 'y', 'z', 'radius', 'celestialIndex', 'orbitIndex'));
    }



}

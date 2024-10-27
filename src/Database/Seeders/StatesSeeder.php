<?php

namespace RubenLopezGea\LaravelCountriesStatesCities\Database\Seeders;

use Illuminate\Database\Seeder;

class StatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    private $file = 'states';

    private $model = 'RubenLopezGea\LaravelCountriesStatesCities\Models\State';

    public function run(): void
    {
        $filepath = __DIR__.'/../data/'.$this->file.'.json';
        $data = json_decode(file_get_contents($filepath), true);

        $output = new \Symfony\Component\Console\Output\ConsoleOutput();
        $progressBar = new \Symfony\Component\Console\Helper\ProgressBar($output, count($data));
        $progressBar->start();

        $data = collect($data);
        foreach ($data->chunk(1000) as $chunk) {
            $this->model::insert($chunk->toArray());
            $progressBar->advance($chunk->count());
        }
        unset($data);

        $progressBar->finish();
        $output->writeln('');
    }
}

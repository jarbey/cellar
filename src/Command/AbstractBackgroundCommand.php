<?php
namespace App\Command;


use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;

abstract class AbstractBackgroundCommand extends AbstractCommand {

    /** @var int */
    private $max_memory = 32 * 1024 * 1024;

    /** @var bool */
    protected $debug_memory = 0;

    /** @var string */
    protected $debug_memory_filename = '/home/pi/cellar/debug_memory.log';

    /** @var int */
    protected $loop_memory_flush = 10;

    /** @var int */
    protected $loop_iteration = 0;


    protected function getMaxMemory() {
        return $this->max_memory;
    }

    abstract protected function get();


    /**
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     */
    abstract protected function flush_memory();

    /**
     * Detach all entities, then fetch sensors and force garbage collecting
     *
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     * @throws \Exception
     */
    protected function manage_memory() {
        $this->flush_memory();

        gc_enable();
        gc_collect_cycles();

        $mem_usage = memory_get_usage();
        if ($mem_usage > $this->max_memory) {
            throw new \Exception('Exceed memory limit : ' . $mem_usage . ' vs ' . $this->max_memory);
        }
    }

    protected function debug_memory_usage() {
        $mem_usage = memory_get_usage();
        file_put_contents($this->debug_memory_filename, '#' . $this->loop_iteration . ': ' . round($mem_usage / 1024) . 'KB' . "\n", FILE_APPEND);
    }


}
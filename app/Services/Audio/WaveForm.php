<?php


namespace App\Services\Audio;


class WaveForm extends \maximal\audio\Waveform
{
    /**
     * Get waveform data from the audio file.
     * @param int $points Desired number of points
     * @param bool $onePhase `true` to get positive values only, `false` to get both phases
     * @return array
     * @throws \Exception
     */
    public function getWaveformDataByPoints($points, $onePhase = false)
    {
        // Calculating parameters
        $needChannels = $this->getChannels() > 1 ? 2 : 1;
        $samplesPerPixel = self::$samplesPerLine * self::$linesPerPixel / 16;
        $needRate = 1.0 * $points * $samplesPerPixel * $this->getSampleRate() / $this->getSamples();

        //if ($needRate > 4000) {
        //	$needRate = 4000;
        //}

        // Command text
        $command = self::$soxCommand . ' ' . escapeshellarg($this->filename) .
            ' -c ' . $needChannels .
            ' -r ' . $needRate . ' -e floating-point -t raw -';

        //var_dump($command);

        $outputs = [
            1 => ['pipe', 'w'],  // stdout
            2 => ['pipe', 'w'],  // stderr
        ];
        $pipes = null;
        $proc = proc_open($command, $outputs, $pipes);
        if (!$proc) {
            throw new \Exception('Failed to run `sox` command');
        }

        $lines1 = [];
        $lines2 = [];
        while ($chunk = fread($pipes[1], 4 * $needChannels * self::$samplesPerLine)) {
            $data = unpack('f*', $chunk);
            $channel1 = [];
            $channel2 = [];
            foreach ($data as $index => $sample) {
                if ($needChannels === 2 && $index % 2 === 0) {
                    $channel2 []= $sample;
                } else {
                    $channel1 []= $sample;
                }
            }
            if ($onePhase) {
                // Rectifying to get positive values only
                $lines1 []= abs(min($channel1));
                $lines1 []= abs(max($channel1));
                if ($needChannels === 2) {
                    $lines2 []= abs(min($channel2));
                    $lines2 []= abs(max($channel2));
                }
            } else {
                // Two phases
                $lines1 []= min($channel1);
                $lines1 []= max($channel1);
                if ($needChannels === 2) {
                    $lines2 []= min($channel2);
                    $lines2 []= max($channel2);
                }
            }
        }

        $err = stream_get_contents($pipes[2]);
        $ret = proc_close($proc);

        if ($ret !== 0) {
            throw new \Exception('Failed to run `sox` command. Error:' . PHP_EOL . $err);
        }

        return ['lines1' => $lines1, 'lines2' => $lines2];
    }
}

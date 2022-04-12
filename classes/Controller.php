<?php
use Illuminate\Support;  // https://laravel.com/docs/5.8/collections - provides the collect methods & collections class
use LSS\Array2Xml;
require_once('classes/Exporter.php');

class Controller {

    private $exporter;
    public function __construct($args) {
        $this->args = $args;
        $this->exporter = new Exporter();
    }

    public function exportPlayerStats($format) {
        $data = [];
        $searchArgs = ['player', 'playerId', 'team', 'position', 'country'];
        $search = $this->args->filter(function($value, $key) use ($searchArgs) {
            return in_array($key, $searchArgs);
        });
        $data = $this->exporter->getPlayerStats($search);

        if (!$data) {
            exit("Error: No data found!");
        }
        return $this->formatData($data, $format);
    }

    public function exportPlayers($format) {
        $data = [];

        $searchArgs = ['player', 'playerId', 'team', 'position', 'country'];
        $search = $this->args->filter(function($value, $key) use ($searchArgs) {
            return in_array($key, $searchArgs);
        });
        $data = $this->exporter->getPlayers($search);
        if (!$data) {
            exit("Error: No data found!");
        }
        return $this->formatData($data, $format);
    }

    public function formatData($data, $format) {
        if($format == "xml")
        {
            return $this->exporter->xmlFormat($data, $format);
        }
        if($format == "json")
        {
            return $this->exporter->jsonFormat($data, $format);
        }
        if($format == "csv")
        {
            return $this->exporter->csvFormat($data, $format);
        }
        if($format == "html")
        {
            return $this->exporter->htmlFormat($data, $format);
        }
    }
}
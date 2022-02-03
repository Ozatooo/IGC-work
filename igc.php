<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<style>
body, .points{
    display:flex;
    justify-content: center;
    align-items:center;
    flex-direction: column;
}
</style>
<?php
class igc{
    public $name;
    public $logger;
    public $version;
    public $gliderClass;
    public $gliderModel;
    public $date;
    public $points;
    public $startTime=9999999;
    public $StartPoint;
    public $landingTime=0;
    public $landingPoint;

    function __construct() 
    {
    }

    
    public function getName($line)
    {
        $find = strpos($line, "HFPLTPILOTINCHARGE");
        if($find !== false)
        {
            $line = substr($line, 19);
            $this->name = $line.'<BR>';
        }
    }


    public function getlogger($line)
    {
        $find = strpos($line, "HFFTYFRTYPE");
        if($find !== false)
        {
            $line = substr($line, 12);
            $this->logger = $line.'<BR>';
        }
    }
    
    public function getVersion($line)
    {
        $find = strpos($line, "HFRFWFIRMWAREVERSION");
        if($find !== false)
        {
            $line = substr($line, 21);
            $this->version = $line.'<BR>';
        }
    }
    
    public function getGliderClass($line)
    {
        $find = strpos($line, "HOCCLCOMPETITION CLASS");
        if($find !== false)
        {
            $line = substr($line, 23);
            $this->gliderClass = $line.'<BR>';
        }
    }

    public function getGliderModel($line)
    {
        $find = strpos($line, "HFGTYGLIDERTYPE");
        if($find !== false)
        {
            $line = substr($line, 18);
            $this->gliderModel = $line.'<BR>';
        }
    }

    public function getDate($line)
    {
        $find = strpos($line, "HFDTEDATE");
        if($find !== false)
        {
            $line = substr($line, 10,-5);
            $this->date = $line[0].$line[1].'-'.$line[2].$line[3].'-20'.$line[4].$line[5].'<BR>';
        }
    }

    public function getPoints($line)
    {
        if($line[0]=='B')
        {
            $xline = substr($line,7,8).', '.substr($line,15,9).' ';
            $this->points .='<tr> <th>'.$xline.'<th> <tr>';

            $x = substr($line,1,-32);
            $this->getStartTime($x,$line);
            $this->getLandingTime($x,$line);

        }
    }

    public function getStartTime($time,$line)
    {
        
        if($this->startTime>$time)
        {
            $this->startTime=$time;
            $this->startPoint = substr($line,7,8).','.substr($line,15,9).'<br>';

        }
    }
    
    public function getLandingTime($time,$line)
    {
        
        if($this->startTime<$time)
        {
            $this->landingTime=$time;
            $this->landingPoint = substr($line,7,8).','.substr($line,15,9);
        }
    }

    public function readFile()
    {
        $file = fopen('https://xcportal.pl/sites/default/files/tracks/2022-02-02/2022-02-02-xct-luk-04300303267.igc','r');

        while(!feof($file))
        {
        $line = fgets($file);
        $this->getName($line);
        $this->getlogger($line);
        $this->getVersion($line);
        $this->getGliderClass($line);
        $this->getGliderModel($line);
        $this->getDate($line);
        $this->getPoints($line);
        }
        echo '<table class="table table-dark">';
        echo '<tr> <th> Imię pilota <th>'.$this->name.'<tr>';
        echo '<tr> <th> Model rejestratora <th>'.$this->logger.'<tr>';
        echo '<tr> <th> Wersja <th>'.$this->version.'<tr>';
        echo '<tr> <th> Klasa Szybowca <th>'.$this->gliderClass.'<tr>';
        echo '<tr> <th> Model Szybowca <th>'.$this->gliderModel.'<tr>';
        echo '<tr> <th> Data <th>'.$this->date.'<tr>';
        echo '<tr> <th> Punkt startu<th>'.$this->startPoint.'<tr>';
        echo '<tr> <th> Punkt lądowania <th>'.$this->landingPoint.'<tr>';
        echo '</table>';


        echo '<table class="table table-dark points" style="width:20vw">';
        echo '<tr> <th scope="row"> Wszystkie punkty <th><tr>';
        echo $this->points;
        echo '</table>';

    }

}
$obj = new igc();

$obj->readFile();

?>


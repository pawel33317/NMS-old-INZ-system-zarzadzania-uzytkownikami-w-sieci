<?php
class Database {
    
    protected $host;
    protected $user;
    protected $pwd;
    protected $dbName;
    protected $dbLink;
    protected $result;
    protected $resultObj;

    function __construct($host, $user, $pwd, $dbName){
        $this->host = $host;
        $this->user = $user;
        $this->pwd = $pwd;
        $this->dbName = $dbName;
        $this->connect();
    }

    // Połącz się z serwerem mySQL i wybierz bazę danych
    public function connect() {
        try {
            $this->dbLink = @mysqli_connect($this->host, $this->user, $this->pwd, $this->dbName);
            if (!$this->dbLink) {
                throw new Exception ("Nie można było połączyć użytkownika $this->user z bazą $this->dbName");
            }
        }
        catch (Exception $e) {
            echo $e->getMessage();
            exit();
        }
        return $this->dbLink;
    }

    // Wykonaj zapytanie SQL
    public function query($query) {
        try {
            $this->result = mysqli_query($this->dbLink, $query);
            if (!$this->result) {
                throw new Exception ('Błąd MySQL: ' . mysqli_error($this->dbLink));
            }
        }
        catch (Exception $e) {
            echo $e->getMessage();
            exit();
        }
        // zapisz wynik do nowego obiektu, który będzie imitować interfejs mysqli OO
        $this->resultObj = new MyResult($this->result);
        return $this->resultObj;
    }
    
    public function sl($co, $skad, $where, $rowne) {
        $wiecej_niz_jeden  = strpos($co, ',');
        $gwiazdka  = strpos($co, '*');
        if ($wiecej_niz_jeden || $gwiazdka){
            $this->result = mysqli_query($this->dbLink, 'select '.$co.' from '.$skad.' where '.$where.' = "'.$rowne.'"'); 
            $this->resultObj = new MyResult($this->result);
            $this->resultObj = $this->resultObj->fetch_assoc();
            return $this->resultObj;
        }
        else{
            $this->result = mysqli_query($this->dbLink, 'select '.$co.' from '.$skad.' where '.$where.' = "'.$rowne.'"'); 
            $this->resultObj = new MyResult($this->result);
            $this->resultObj = $this->resultObj->fetch_assoc();
            $this->resultObj = $this->resultObj[$co];
            return $this->resultObj; 
        }
    }
    
    public function get_result(){
        return $this->resultObj;
    }
    // zamknij połączenie MySQL
    public function close(){
        mysqli_close($this->dbLink);
        }   
    }

class MyResult {

    protected $theResult;
    public $num_rows;
 
    function __construct($r) {
        if (is_bool($r)) {
            $this->num_rows = 0;
        }
        else {
            $this->theResult = $r;
            // pobierz całkowitą liczbę znalezionych rekordów
            $this->num_rows = mysqli_num_rows($r);
        }
    }
 
    // pobierz asocjacyjną tablicę wyników (przetwarza naraz jeden wiersz)  
    function fetch_assoc() {
        $newRow = mysqli_fetch_assoc($this->theResult);
        return $newRow;
    }
}
?>
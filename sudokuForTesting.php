<?php

    //include('connectserver.php');


    class SudokuBoard {

        /* A static board to mutate */
    /*       public static $board = array(
            array(0, 0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0, 0)
        );
        */

     public static $board = array(
            array(6,5,4,8,9,7,2,3,1),
            array(3,2,8,4,1,5,9,6,7),
            array(1,9,7,2,3,6,5,8,4),
            array(5,7,3,6,2,9,4,1,8),
            array(8,4,6,5,7,1,3,2,9),
            array(2,1,9,3,8,4,7,5,6),
            array(7,3,2,1,4,8,6,9,5),
            array(9,6,1,7,5,3,8,4,2),
            array(4,8,5,9,6,2,1,7,1) //invalid row
        );
        /* A to string method for the board.
        Echos the values of the board in order.*/

        public static function toBoard() {
          $boardd='';

            for ($i = 0; $i < 9; $i++) {
                for ($j = 0; $j < 9; $j++) {
                    $boardd = $boardd . self::$board[$i][$j] . " ";
                    // uncomment for testing in console
                    /*if ($j % 3 == 2) {
                        echo " ";
                    }*/
                }

      // uncomment for testing in console
                /*echo "\n";
                if($i % 3 == 2) {
                    echo "\n";
                }*/
            }
            return $boardd;
        }

        /*  Param: A row index starting from 0
            Checks to see if a row of the sudoku board is valid
            (This method is n^2: compares two numbers as the nested loop iterates)
        */
        function isRowValid($row) {

            $result = true;
            for($i = 0; $i < 9; $i++ ) {
                for($j = $i + 1; $j < 9; $j++ ) {
                    if(self::$board[$row][$i] > 0 && self::$board[$row][$i] == self::$board[$row][$j]) {
                        $result = false;
                    }
                }
            }

            return $result;
        }

        /*  Param: A column index starting from 0
            Checks to see if a column of the sudoku board is valid
            (This method is n^2: compares two numbers as the nested loop iterates)
        */
        function isColumnValid($column) {

            $result = true;
            for($i = 0; $i < 9; $i++ ) {
                for($j = $i + 1; $j < 9; $j++ ) {
                    if (self::$board[$i][$column] > 0 && self::$board[$i][$column] == self::$board[$j][$column]) {
                        $result = false;
                    }
                }
            }

            return $result;
        }

        /*  Param: A column and row index starting from 0
            Checks to see if a square is valid
        */
        function isBoxValid($boxRow, $boxColumn) {

            $result = true;
            for($i = 0; $i < 9; $i++ ) {
                for($j = $i + 1; $j < 9; $j++ ) {
                    $iVal = self::$board[($boxRow * 3) + floor(($i / 3))][($boxColumn * 3) + ($i % 3)];
                    $jVal = self::$board[($boxRow * 3) + floor(($j / 3))][($boxColumn * 3) + ($j % 3)];
                    if ($iVal > 0 && $iVal == $jVal) {
                        $result = false;
                    }
                }
            }

            return $result;
        }

        /*
        * Returns the indices of where a sudoku box can be found
        * by the indices of the sudoku board (0..9)
        * Return values can only be between 0 and 2
        */
        function getBoxIndices($row, $column) {

            $result = array(0, 0);

            $result[0] = floor($row / 3);
            $result[1] = floor($column /3);

            return $result;

        }

        /* Clears a board back to it's original state */
        function clear() {
            for ($i = 0; $i < 9; $i++) {
                for ($j = 0; $j < 9; $j++) {
                    self::$board[$i][$j] = 0;
                }
            }
        }
    }

    /* Creates a randomly generated board
    Uses boardIsInvalid method to figure out what number to generate*/
    function generateBoard() {

        for($i = 0; $i < 9; $i++ ) {
            for($j = 0; $j < 9; $j++ ) {
                $numTries = 0;
                do {
                    SudokuBoard::$board[$i][$j] = generateRandomSudokuNumber();
                    $numTries ++;
                } while (boardIsInvalid($i, $j) && $numTries < 100);
                if($numTries >= 100) {
                    $i = 0;
                    $j = -1;
                    SudokuBoard::clear();
                }
            }
        }
    }

    /* Generates a random sudoku number 1 .. 9 */
    function generateRandomSudokuNumber() {
        return rand(1,9);
    }

    /* A method to tell if the board is invalid*/
    function boardIsInvalid($row, $column) {

        $result = true;
        $boxIndices = SudokuBoard::getBoxIndices($row, $column);

        if(SudokuBoard::isRowValid($row) && SudokuBoard::isColumnValid($column) && SudokuBoard::isBoxValid($boxIndices[0], $boxIndices[1])) {
            $result = false;
        }

        return $result;
    }

    /* translates an index 0 .. 80 into a board index (row and column index)
    Usually the index is for the html id's */
    function indexToBoardIndex($index) {
        $result = array(0, 0);

        $tempRow = floor($index / 3);
        $row = floor($tempRow / 3);

        $column = $index - ($row * 9);

        $result[0] = $row;
        $result[1] = $column;

        return $result;
    }


    /* Creates a board that has not been solved
    Checks for a difficulty that has been declared on the client
    which is stored globally and used within this method */
    function createBlankBoard() {

         $difficulty = 1;
        global $servername, $username, $password, $db;

        /* Holds the indexes of how the boxes would be indexed from 0 .. 80 */
        $box1 = array(0, 1, 2, 9, 10, 11, 18, 19, 20);
        $box2 = array(3, 4, 5, 12, 13, 14, 21, 22, 23);
        $box3 = array(6, 7, 8, 15, 16, 17, 24, 25, 26);
        $box4 = array(27, 28, 29, 36, 37, 38, 45, 46, 47);
        $box5 = array(30, 31, 32, 39, 40, 41, 48, 49, 50);
        $box6 = array(33, 34, 35, 42, 43, 44, 51, 52, 53);
        $box7 = array(54, 55, 56, 63, 64, 65, 72, 73, 74);
        $box8 = array(57, 58, 59, 66, 67, 68, 75, 76, 77);
        $box9 = array(60, 61, 62, 69, 70, 71, 78, 79, 80);

        /* array_rand randomly picks one or more elements from an array
        where param1 is the array and param2 is the number of elements
        returns a key or keys (think index) */
        $randomElements = array();
        $rand_keys1 = array_rand($box1, $difficulty);
        $rand_keys2 = array_rand($box2, $difficulty);
        $rand_keys3 = array_rand($box3, $difficulty);
        $rand_keys4 = array_rand($box4, $difficulty);
        $rand_keys5 = array_rand($box5, $difficulty);
        $rand_keys6 = array_rand($box6, $difficulty);
        $rand_keys7 = array_rand($box7, $difficulty);
        $rand_keys8 = array_rand($box8, $difficulty);
        $rand_keys9 = array_rand($box9, $difficulty);

        /* If difficulty is greater than one, pick n indexes from each box
        and add it to the randomElements array */
        if($difficulty > 1) {
            for($i = 0; $i < $difficulty; $i++) {
                array_push($randomElements, $box1[$rand_keys1[$i]]);
                array_push($randomElements, $box2[$rand_keys2[$i]]);
                array_push($randomElements, $box3[$rand_keys3[$i]]);
                array_push($randomElements, $box4[$rand_keys4[$i]]);
                array_push($randomElements, $box5[$rand_keys5[$i]]);
                array_push($randomElements, $box6[$rand_keys6[$i]]);
                array_push($randomElements, $box7[$rand_keys7[$i]]);
                array_push($randomElements, $box8[$rand_keys8[$i]]);
                array_push($randomElements, $box9[$rand_keys9[$i]]);
            }
        }
        /* If it's just one only push that one
        (this probably doesn't need an if/else*/
        else {
            for($i = 0; $i < $difficulty; $i++) {
                array_push($randomElements, $box1[$rand_keys1]);
                array_push($randomElements, $box2[$rand_keys2]);
                array_push($randomElements, $box3[$rand_keys1]);
                array_push($randomElements, $box4[$rand_keys1]);
                array_push($randomElements, $box5[$rand_keys1]);
                array_push($randomElements, $box6[$rand_keys1]);
                array_push($randomElements, $box7[$rand_keys1]);
                array_push($randomElements, $box8[$rand_keys1]);
                array_push($randomElements, $box9[$rand_keys1]);
            }
        }

        /* Puts zeros where the blanks should be (based off the randomElements) */
        $randomElementsSize = sizeof($randomElements);
        for($i = 0; $i < $randomElementsSize; $i++ ) {
            $boardIndexes = indexToBoardIndex($randomElements[$i]);
            SudokuBoard::$board[$boardIndexes[0]][$boardIndexes[1]] = 0;
        }

        // echos the board with blanks
        return SudokuBoard::toBoard();

    /*Sarah   $conn = new mysqli($servername, $username, $password, $db);
        if($conn->connect_error) {
            die("Connection failed:" . $conn->connect_error);
        }

        /* For each row of the board, insert it as a sql record
        Note: rowNum just gives a way to keep the order of the rows*/
        for($i = 0; $i < 9; $i++) {
            $val1 = SudokuBoard::$board[$i][0];
            $val2 = SudokuBoard::$board[$i][1];
            $val3 = SudokuBoard::$board[$i][2];
            $val4 = SudokuBoard::$board[$i][3];
            $val5 = SudokuBoard::$board[$i][4];
            $val6 = SudokuBoard::$board[$i][5];
            $val7 = SudokuBoard::$board[$i][6];
            $val8 = SudokuBoard::$board[$i][7];
            $val9 = SudokuBoard::$board[$i][8];


          /* Sarah $sql = "INSERT INTO SudokuUnsolved (rowNum, col0, col1, col2, col3, col4, col5, col6, col7, col8)
            VALUES ($i, $val1, $val2, $val3, $val4, $val5, $val6, $val7, $val8, $val9)";


            if ($conn->query($sql) === TRUE) {
                //echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            } */
        }


    }

    /* Gets the current game from the database */
  /* Sarah function getCurrentGame() {
        global $servername, $username, $password, $db;

        $conn = new mysqli($servername, $username, $password, $db);
        if($conn->connect_error) {
            die("Connection failed:" . $conn->connect_error);
        }

        $sql = "SELECT col0, col1, col2, col3, col4, col5, col6, col7, col8 FROM SudokuUnsolved";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo $row["col0"] . " " . $row["col1"] . " " . $row["col2"] . " " . $row["col3"] . " " . $row["col4"] . " " . $row["col5"] . " " . $row["col6"] . " " . $row["col7"] . " " . $row["col8"] . " ";
            }
        }
    }

    /* Attempts to update the database with a value (number)
    and the index (html id) the user is trying to update   */
/* Sarah   function updateGame($number, $id) {
        global $servername, $username, $password, $db;

        $conn = new mysqli($servername, $username, $password, $db);
        if($conn->connect_error) {
            die("Connection failed:" . $conn->connect_error);
        }

        // (id - 1) because the html id's start at 1
        $indexes = indexToBoardIndex($id - 1);
        $row = $indexes[0];
        $col = "col" . $indexes[1];

        /* Gets the correct value from the solved state (SudokuSolved) */
  /*  Sarah    $sql = "SELECT $col FROM SudokuSolved WHERE rowNum = '$row'";
        $result = $conn->query($sql);
        while($rom = $result->fetch_assoc()) {
            $correctSudokuVal = $rom["$col"];
        }

        /* If the number was correct (based on SudokuSolved database)
        Echo correct and update the unsolved database to keep the value, update the time, and reset the LastPlayed table
        else simply echo incorrect */
  /* Sarah     if($number == $correctSudokuVal) {
            echo "correct";
            $sql = "UPDATE SudokuUnsolved SET $col = '$number' WHERE rowNum = '$row'";
            $result = $conn->query($sql);

            resetLastPlayedTable();
            updateCurrentTime();

        } else {
            echo "incorrect";
        }

    }

    /* Resets a table that keeps one record, the last time a move was made */
  /*Sarah  function resetLastPlayedTable() {
        global $servername, $username, $password, $db;

        $conn = new mysqli($servername, $username, $password, $db);
        if($conn->connect_error) {
            die("Connection failed:" . $conn->connect_error);
        }

        $dropLastPlayed = "DROP TABLE LastPlayed";

        if ($conn->query($dropLastPlayed) === TRUE) {
        }

        $createLastPlayed = "CREATE TABLE LastPlayed (lastMove TIMESTAMP)";

        if ($conn->query($createLastPlayed) === TRUE) {
        }
    }

    /* For a new game, resets the game */
/*Sarah    function resetSudokuSolved() {
        global $servername, $username, $password, $db;

        $conn = new mysqli($servername, $username, $password, $db);
        if($conn->connect_error) {
            die("Connection failed:" . $conn->connect_error);
        }

        $dropSudokuSolved = "DROP TABLE SudokuSolved";
        if ($conn->query($dropSudokuSolved) === TRUE) {
        }

        $createSudokuSolved = "CREATE TABLE SudokuSolved (rowNum int(2), col0 int(2), col1 int(2), col2 int(2), col3 int(2), col4 int(2), col5 int(2), col6 int(2), col7 int(2), col8 int(2))";
        if ($conn->query($createSudokuSolved) === TRUE) {
        }

    }

    /* Resets the SudokuUnsolved table */
  /*Sarah  function resetSudokuUnsolved() {
        global $servername, $username, $password, $db;

        $conn = new mysqli($servername, $username, $password, $db);
        if($conn->connect_error) {
            die("Connection failed:" . $conn->connect_error);
        }

        $dropSudokuUnsolved = "DROP TABLE SudokuUnsolved";
        if ($conn->query($dropSudokuUnsolved) === TRUE) {
        }

        $createSudokuUnsolved = "CREATE TABLE SudokuUnsolved (rowNum int(2), col0 int(2), col1 int(2), col2 int(2), col3 int(2), col4 int(2), col5 int(2), col6 int(2), col7 int(2), col8 int(2))";
        if ($conn->query($createSudokuUnsolved) === TRUE) {
        }
    }

    /* A  method to return a hint to the user
    (fills in random missing sudoku number) */
  /*Sarah  function getHint() {
        global $servername, $username, $password, $db;

        $conn = new mysqli($servername, $username, $password, $db);
        if($conn->connect_error) {
            die("Connection failed:" . $conn->connect_error);
        }

        /* Finds a 0 in the unsolved state */
/*Sarah        while($rowNum == "") {

            $col = rand(0,8);
            $randomCol = "col" . $col;

            $select = "SELECT * FROM SudokuUnsolved WHERE $randomCol = 0 limit 1";

            $result = $conn->query($select);
            while($rom = $result->fetch_assoc()) {
                $rowNum = $rom["rowNum"];
            }
        }

        $sqlGetNumber = "SELECT $randomCol FROM SudokuSolved WHERE rowNum = '$rowNum'";
        $result = $conn->query($sqlGetNumber);
        while($rom = $result->fetch_assoc()) {
            $correctSudokuVal = $rom["$randomCol"];
        }


        $sqlUpdateUnSolved = "UPDATE SudokuUnsolved SET $randomCol = '$correctSudokuVal' WHERE rowNum = '$rowNum'";
        $result = $conn->query($sqlUpdateUnSolved);

        updateCurrentTime();

        echo $rowNum . " " . $col . " " . $correctSudokuVal;

    }

    /* Updates the current time to the LastPlayed board */
/*Sarah   function updateCurrentTime() {

        global $servername, $username, $password, $db;

        $conn = new mysqli($servername, $username, $password, $db);
        if($conn->connect_error) {
            die("Connection failed:" . $conn->connect_error);
        }

        $insertCurrentTime = "INSERT INTO LastPlayed (lastMove) VALUES (NOW())";

        if ($conn->query($insertCurrentTime) === TRUE) {
        }
    }

    /* Returns the time of the last move */
/*Sarah    function getlastMove() {
        global $servername, $username, $password, $db;

        $conn = new mysqli($servername, $username, $password, $db);
        if($conn->connect_error) {
            die("Connection failed:" . $conn->connect_error);
        }

        $select = "SELECT lastMove FROM LastPlayed";
        $result = $conn->query($select);
        while($rom = $result->fetch_assoc()) {
            $lastMove = $rom["lastMove"];
        }

        echo $lastMove;
    }

    /* Creates a new sudoku game */
    function createNewGame() {


  /*Sarah      global $servername, $username, $password, $db;

        $conn = new mysqli($servername, $username, $password, $db);
        if($conn->connect_error) {
            die("Connection failed:" . $conn->connect_error);
        }

  Sarah*/
        resetSudokuSolved();
        resetSudokuUnsolved();
        resetLastPlayedTable();

        updateCurrentTime();

        generateBoard();

        for($i = 0; $i < 9; $i++) {
            $val1 = SudokuBoard::$board[$i][0];
            $val2 = SudokuBoard::$board[$i][1];
            $val3 = SudokuBoard::$board[$i][2];
            $val4 = SudokuBoard::$board[$i][3];
            $val5 = SudokuBoard::$board[$i][4];
            $val6 = SudokuBoard::$board[$i][5];
            $val7 = SudokuBoard::$board[$i][6];
            $val8 = SudokuBoard::$board[$i][7];
            $val9 = SudokuBoard::$board[$i][8];


  /*Sarah          $sql = "INSERT INTO SudokuSolved (rowNum, col0, col1, col2, col3, col4, col5, col6, col7, col8)
            VALUES ($i, $val1, $val2, $val3, $val4, $val5, $val6, $val7, $val8, $val9)";


            if ($conn->query($sql) === TRUE) {
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

        }
Sarah*/
        createBlankBoard();

    }
  }

    /* Stores the difficulty from the client */
/*Sarah    $difficulty = strtolower($_POST['d']);

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'createNewGame':
                createNewGame();
                break;
            case 'getCurrentGame':
                getCurrentGame();
                break;
            case 'updateGame':
                updateGame($_POST['number'], $_POST['box']);
                break;
            case 'getlastMove':
                getlastMove();
                break;
            case 'getHint':
                getHint();
                break;
        }
    }
Sarah*/
function addTwoNumbers($x, $y){
  $z = $x +$y;
  return $z;
}

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub\ConsecutiveCalls as ConsecutiveCallsStub;
use PHPUnit\Framework\MockObject\Stub\Exception as ExceptionStub;
use PHPUnit\Framework\MockObject\Stub\ReturnArgument as ReturnArgumentStub;
use PHPUnit\Framework\MockObject\Stub\ReturnCallback as ReturnCallbackStub;
use PHPUnit\Framework\MockObject\Stub\ReturnSelf as ReturnSelfStub;
use PHPUnit\Framework\MockObject\Stub\ReturnStub;
use PHPUnit\Framework\MockObject\Stub\ReturnValueMap as ReturnValueMapStub;

final class testSudoku extends TestCase
{

	public function testaddition(){
	  $expected = 5;
		$result = addTwoNumbers(2,3);
		$this->assertEquals($expected,$result);

	}
//row is valid
  public function testisRowValid1(){
    $board= new SudokuBoard;
    $expected = true;
    $result = $board::isRowValid(0);
    $this->assertEquals($expected, $result);
  }
  //row is invalid
  public function testisRowValid2(){
    $board= new SudokuBoard;
    $expected = false;
    $result = $board::isRowValid(8);
    $this->assertEquals($expected, $result);
  }
//col is valid
  public function testisColValid1(){
    $board= new SudokuBoard;
    $expected = true;
    $result = $board::isColumnValid(0);
    $this->assertEquals($expected, $result);
  }
  //col is invalid
  public function testisColValid2(){
    $board= new SudokuBoard;
    $expected = false;
    $result = $board::isColumnValid(8);
    $this->assertEquals($expected, $result);
  }
//box is valid
  public function testisBoxValid1(){
    $board= new SudokuBoard;
    $expected = true;
    $result = $board::isBoxValid(0,0);
    $this->assertEquals($expected, $result);
  }
  //box is invalid
  public function testisBoxValid2(){
    $board= new SudokuBoard;
    $expected =false;
    $result = $board::isBoxValid(2,2);
    $this->assertEquals($expected, $result);
  }
  //toBoard
  public function testtoBoard(){
    $board= new SudokuBoard();
    $expected ="6 5 4 8 9 7 2 3 1 3 2 8 4 1 5 9 6 7 1 9 7 2 3 6 5 8 4 5 7 3 6 2 9 4 1 8 8 4 6 5 7 1 3 2 9 2 1 9 3 8 4 7 5 6 7 3 2 1 4 8 6 9 5 9 6 1 7 5 3 8 4 2 4 8 5 9 6 2 1 7 1 ";
    $result = $board->toBoard();
    $this->assertEquals($expected, $result);
  }
  //return indices of small box box at right bottom
  public function testgetBoxIndicies(){
    $board= new SudokuBoard;
    $expected = array(2,2);
    $result = $board::getBoxIndices(8,7);
    $this->assertEquals($expected, $result);
  }
//clears the board
  public function testclearBoard(){
    $board= new SudokuBoard;
    $boardClear= "0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 ";
    $board::clear();
    $expected = $board::toBoard();
    $this->assertEquals($boardClear, $expected);
  }
  //test row, column, and box
  public function testboardIsInvalid1(){
    $board= new SudokuBoard;
    $expected = false; //board is valid
    $result = boardIsInvalid(0,0);
    $this->assertEquals($expected, $result);
  }
  //test random number generateRandomSudokuNumber
  public function testgenerateRandomSudokuNumber(){
    $board= new SudokuBoard;
    $inArray= array(1,2,3,4,5,6,7,8,9);
    $result = generateRandomSudokuNumber();
    $this->assertContains($result, $inArray); //the result must be in the array
  }
//display new board and test that its not null
  public function testgenerateBoard(){
    $board = new SudokuBoard;
    $board::clear();
    generateBoard();
    //echo $board::toBoard();
    $this->assertNotNull($board);
  }

  public function testindexToBoardIndex(){
    $expected = array(8,7);
    $result = indexToBoardIndex(79);
    $this->assertEquals($expected, $result);
  }

  public function testcreateBlankBoard(){
    $board = new SudokuBoard;
    $boardWithBlanks = createBlankBoard();
    //echo "org board: " . $board::toBoard();
    //echo "board with blanks: " . $boardWithBlanks;
    $this->assertNotEquals($board, $boardWithBlanks);

  }


}




?>

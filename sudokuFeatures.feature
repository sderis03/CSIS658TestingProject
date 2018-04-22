Feature: Choose level
As a player
In order to improve my skills
I want to be able to choose my difficulty

Scenario: Choosing level
Given I am on "http://153.9.205.25/~CSIS604g/658Sudoku/src/sudoku.html"
When I press the dropdown arrow
And I press 4
When I click on the "New Game" button
Then I should see 4 blank blocks in each 3x3 square

Feature: Get Hint
As a player
In order to get help when I am stuck
I want to get a Hint

Scenario: I am stuck
Given I am on "http://153.9.205.25/~CSIS604g/658Sudoku/src/sudoku.html"
When I click on the "Hint" button
Then a blank square should have a number

Feature: Choose wrong answer
As a player
In order to know when I get an answer wrong
I want my guess to turn red.

Scenario: I picked the wrong answer
Given I am on "http://153.9.205.25/~CSIS604g/658Sudoku/src/sudoku.html"
And the first row is '7, 8, 5, , 4, 6, 3, 1, 2'
When I click on the "2" button
And then press the blank space after 5
Then the number 2 should turn red

Feature: Choose correct answer
As a player
In order to know when I get an answer right
I want my guess to turn green.

Scenario: I picked the right answer
Given I am on "http://153.9.205.25/~CSIS604g/658Sudoku/src/sudoku.html"
And the first row is '7, 8, 5, , 4, 6, 3, 1, 2'
When I click on the "9" button
And then press the blank space after 5
Then the number 9 should turn green

Feature: See last move
As a player
In order to see how long it has been since someone played the Game
I want to see a times of when the last move was made

Scenario: See last move made
Given I am on "http://153.9.205.25/~CSIS604g/658Sudoku/src/sudoku.html"
And the time is '2018-04-22 13:51:10'
When I click on the "Hint" button
I should see 'Last move: 2018-04-22 13:51:10'
